"""
Retrieval Service
Handles vector similarity search and retrieval of relevant document chunks
Uses pgvector for efficient cosine similarity search
"""
import logging
from typing import List, Tuple
from sqlalchemy import func
from sqlalchemy.orm import Session
from pgvector.sqlalchemy import Vector
from config import SIMILARITY_THRESHOLD, TOP_K_RESULTS
from models import TextChunk, Document
from embedding_service import EmbeddingService

logger = logging.getLogger(__name__)


class RetrievalService:
    """
    Service for retrieving relevant document chunks
    Performs vector similarity search on stored embeddings
    """
    
    def __init__(self, db: Session):
        """
        Initialize the retrieval service
        
        Args:
            db (Session): SQLAlchemy database session
        """
        self.db = db
        self.embedding_service = EmbeddingService()
        logger.info("✅ RetrievalService initialized")
    
    def retrieve_relevant_chunks(
        self,
        query: str,
        top_k: int = TOP_K_RESULTS,
        threshold: float = SIMILARITY_THRESHOLD
    ) -> List[Tuple[TextChunk, float, Document]]:
        """
        Retrieve the most relevant chunks for a query
        Uses cosine similarity search on vector embeddings
        
        Args:
            query (str): User query
            top_k (int): Number of top results to return
            threshold (float): Minimum similarity score (0-1)
            
        Returns:
            List[Tuple]: List of (chunk, similarity_score, document) tuples
        """
        try:
            logger.info(f"🔍 Retrieving relevant chunks for query: {query[:100]}...")
            
            # Generate query embedding
            query_embedding = self.embedding_service.generate_query_embedding(query)
            logger.debug(f"✅ Generated query embedding (dim={len(query_embedding)})")
            
            # Perform vector similarity search using pgvector
            # cosine similarity: 1 - cosine_distance
            results = self.db.query(
                TextChunk,
                Document,
                # Calculate cosine similarity: 1 - ||a - b|| / (||a|| * ||b||)
                # pgvector's <=> operator gives cosine distance
                (1 - (TextChunk.embedding <-> Vector(query_embedding))).label('similarity')
            ).join(
                Document, TextChunk.document_id == Document.id
            ).order_by(
                TextChunk.embedding.cosine_distance(query_embedding)  # Order by distance (closest first)
            ).limit(top_k + 10).all()  # Get more results to filter by threshold
            
            # Filter results by similarity threshold
            relevant_results = []
            for chunk, document, similarity in results:
                if similarity >= threshold:
                    relevant_results.append((chunk, float(similarity), document))
                    
                    logger.debug(f"  📄 Chunk {chunk.id}: similarity={similarity:.4f}")
                
                if len(relevant_results) >= top_k:
                    break
            
            logger.info(f"✅ Retrieved {len(relevant_results)} relevant chunks (threshold={threshold})")
            
            return relevant_results
            
        except Exception as e:
            logger.error(f"❌ Error retrieving chunks: {str(e)}")
            raise
    
    def retrieve_by_document(
        self,
        document_id: int,
        top_k: int = TOP_K_RESULTS
    ) -> List[TextChunk]:
        """
        Retrieve all chunks from a specific document
        
        Args:
            document_id (int): ID of document
            top_k (int): Maximum chunks to return
            
        Returns:
            List[TextChunk]: List of chunks from document
        """
        try:
            chunks = self.db.query(TextChunk).filter(
                TextChunk.document_id == document_id
            ).order_by(TextChunk.chunk_index).limit(top_k).all()
            
            logger.info(f"✅ Retrieved {len(chunks)} chunks from document {document_id}")
            return chunks
            
        except Exception as e:
            logger.error(f"❌ Error retrieving document chunks: {str(e)}")
            raise
    
    def search_by_keyword(
        self,
        keyword: str,
        top_k: int = TOP_K_RESULTS
    ) -> List[TextChunk]:
        """
        Search chunks by text keyword (basic full-text search)
        Falls back to keyword matching if vector search is unavailable
        
        Args:
            keyword (str): Search keyword
            top_k (int): Maximum results to return
            
        Returns:
            List[TextChunk]: List of matching chunks
        """
        try:
            chunks = self.db.query(TextChunk).filter(
                TextChunk.chunk_text.ilike(f'%{keyword}%')
            ).limit(top_k).all()
            
            logger.info(f"✅ Retrieved {len(chunks)} chunks for keyword: {keyword}")
            return chunks
            
        except Exception as e:
            logger.error(f"❌ Error retrieving chunks by keyword: {str(e)}")
            raise
    
    def get_retrieval_stats(self) -> dict:
        """
        Get statistics about the retrieval system
        
        Returns:
            dict: Statistics about stored embeddings and chunks
        """
        try:
            total_documents = self.db.query(Document).count()
            total_chunks = self.db.query(TextChunk).count()
            
            stats = {
                "total_documents": total_documents,
                "total_chunks": total_chunks,
                "embedding_dimension": 768,  # Gemini embedding dimension
                "similarity_threshold": SIMILARITY_THRESHOLD,
                "top_k_results": TOP_K_RESULTS
            }
            
            logger.info(f"📊 Retrieval stats: {stats}")
            return stats
            
        except Exception as e:
            logger.error(f"❌ Error getting retrieval stats: {str(e)}")
            raise
