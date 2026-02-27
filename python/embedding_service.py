"""
Embedding Service
Generates embeddings for text using Google's Gemini Embedding API
"""
import logging
import re
import xml.etree.ElementTree as ET
from typing import List
import google.generativeai as genai
from config import GOOGLE_AI_KEY, EMBEDDING_MODEL

logger = logging.getLogger(__name__)

# Configure Google AI
genai.configure(api_key=GOOGLE_AI_KEY)


class EmbeddingService:
    """
    Service for generating embeddings using Gemini Embedding API
    Handles text preprocessing and batch embedding generation
    """
    
    def __init__(self):
        """Initialize the embedding service"""
        self.model_name = EMBEDDING_MODEL
        self.embedding_dimension = 768  # Gemini embedding dimension
        logger.info(f"✅ EmbeddingService initialized with model: {self.model_name}")
    
    def generate_embedding(self, text: str) -> List[float]:
        """
        Generate embedding for a single text chunk
        
        Args:
            text (str): The text to embed
            
        Returns:
            List[float]: The embedding vector (768 dimensions)
            
        Raises:
            Exception: If embedding generation fails
        """
        try:
            # Clean and preprocess text
            text = self._preprocess_text(text)
            
            # Generate embedding using Gemini API
            response = genai.embed_content(
                model=self.model_name,
                content=text,
                task_type="RETRIEVAL_DOCUMENT"
            )
            
            embedding = response['embedding']
            logger.debug(f"✅ Generated embedding for text (len={len(text)} chars): {embedding[:5]}...")
            return embedding
            
        except Exception as e:
            logger.error(f"❌ Error generating embedding: {str(e)}")
            raise
    
    def generate_query_embedding(self, query: str) -> List[float]:
        """
        Generate embedding for a user query
        Uses RETRIEVAL_QUERY task type for better query-document matching
        
        Args:
            query (str): The query text
            
        Returns:
            List[float]: The embedding vector
            
        Raises:
            Exception: If embedding generation fails
        """
        try:
            # Clean and preprocess text
            query = self._preprocess_text(query)
            
            # Generate embedding using Gemini API with RETRIEVAL_QUERY task type
            response = genai.embed_content(
                model=self.model_name,
                content=query,
                task_type="RETRIEVAL_QUERY"
            )
            
            embedding = response['embedding']
            logger.debug(f"✅ Generated query embedding: {embedding[:5]}...")
            return embedding
            
        except Exception as e:
            logger.error(f"❌ Error generating query embedding: {str(e)}")
            raise
    
    def generate_batch_embeddings(self, texts: List[str]) -> List[List[float]]:
        """
        Generate embeddings for multiple texts in batch
        More efficient than generating one at a time
        
        Args:
            texts (List[str]): List of texts to embed
            
        Returns:
            List[List[float]]: List of embedding vectors
            
        Raises:
            Exception: If batch embedding generation fails
        """
        try:
            # Clean and preprocess all texts
            cleaned_texts = [self._preprocess_text(text) for text in texts]
            
            # Generate embeddings in batch
            response = genai.embed_content(
                model=self.model_name,
                content=cleaned_texts,
                task_type="RETRIEVAL_DOCUMENT"
            )
            
            embeddings = response['embedding']
            logger.info(f"✅ Generated {len(embeddings)} embeddings in batch")
            return embeddings
            
        except Exception as e:
            logger.error(f"❌ Error generating batch embeddings: {str(e)}")
            raise
    
    @staticmethod
    def _preprocess_text(text: str) -> str:
        """
        Preprocess text before embeddings
        Removes HTML tags, extra whitespace, and normalizes text
        
        Args:
            text (str): Raw text
            
        Returns:
            str: Cleaned text
        """
        # Remove HTML tags
        text = re.sub(r'<[^>]+>', '', text)
        
        # Remove XML tags
        try:
            ET.fromstring(text)
            text = re.sub(r'<[^>]+>', '', text)
        except:
            pass
        
        # Remove extra whitespace
        text = ' '.join(text.split())
        
        # Remove special characters but keep basic punctuation
        text = re.sub(r'[^\w\s\.,!?;:\-\'"]', '', text)
        
        # Normalize whitespace
        text = text.strip()
        
        return text
