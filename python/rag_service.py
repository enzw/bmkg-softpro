"""
RAG Service
Orchestrates the complete RAG pipeline:
1. Retrieval: Find relevant context
2. Augmentation: Prepare context for prompt injection
3. Generation: Generate response using Gemini with context
"""
import logging
from typing import List, Dict, Tuple
from sqlalchemy.orm import Session
import google.generativeai as genai
from config import GOOGLE_AI_KEY, SIMILARITY_THRESHOLD
from embedding_service import EmbeddingService
from retrieval_service import RetrievalService

logger = logging.getLogger(__name__)

# Configure Google AI
genai.configure(api_key=GOOGLE_AI_KEY)


class RAGService:
    """
    Main RAG service that orchestrates the complete pipeline
    Handles retrieval, context preparation, and response generation
    """
    
    def __init__(self, db: Session):
        """
        Initialize the RAG service
        
        Args:
            db (Session): SQLAlchemy database session
        """
        self.db = db
        self.retrieval_service = RetrievalService(db)
        self.embedding_service = EmbeddingService()
        self.model = genai.getGenerativeModel(model_name='gemini-2.5-flash')
        logger.info("✅ RAGService initialized")
    
    def generate_response(
        self,
        query: str,
        system_prompt: str = None,
        use_rag: bool = True,
        top_k: int = 5,
        max_output_tokens: int = 1024
    ) -> Dict:
        """
        Generate a response using RAG pipeline
        Retrieves relevant context and injects into Gemini prompt
        
        Args:
            query (str): User query
            system_prompt (str, optional): System prompt for context
            use_rag (bool): Whether to use RAG (default True)
            top_k (int): Number of relevant chunks to retrieve
            max_output_tokens (int): Maximum tokens in response
            
        Returns:
            Dict: Response with text, sources, and RAG metadata
        """
        try:
            response_data = {
                "success": True,
                "query": query,
                "response": None,
                "use_rag": use_rag,
                "sources": [],
                "relevant_chunks": [],
                "similarity_scores": [],
                "fallback": False
            }
            
            if not use_rag:
                logger.info("⚙️  RAG disabled - using direct generation")
                response_data["response"] = self._generate_direct_response(
                    query, system_prompt, max_output_tokens
                )
                return response_data
            
            # Step 1: Retrieve relevant chunks
            logger.info("📖 RAG Step 1: Retrieving relevant context...")
            retrieved_results = self.retrieval_service.retrieve_relevant_chunks(
                query, top_k=top_k
            )
            
            # Check if we have relevant context
            if not retrieved_results:
                logger.warning("⚠️  No relevant context found - using fallback response")
                response_data["fallback"] = True
                response_data["response"] = self._generate_fallback_response(query, system_prompt)
                return response_data
            
            # Extract chunks, similarities, and sources
            chunks = []
            similarities = []
            documents_set = set()
            
            for chunk, similarity, document in retrieved_results:
                chunks.append(chunk.chunk_text)
                similarities.append(float(similarity))
                documents_set.add((document.id, document.name, document.source))
                
                logger.debug(f"  📄 Retrieved: {chunk.chunk_text[:100]}... (sim={similarity:.4f})")
            
            response_data["relevant_chunks"] = chunks
            response_data["similarity_scores"] = similarities
            response_data["sources"] = [
                {
                    "id": doc_id,
                    "name": doc_name,
                    "source": doc_source
                }
                for doc_id, doc_name, doc_source in documents_set
            ]
            
            # Step 2: Prepare augmented prompt with context
            logger.info("📝 RAG Step 2: Augmenting prompt with context...")
            augmented_prompt = self._prepare_aug_prompt(
                query, chunks, system_prompt
            )
            
            # Step 3: Generate response using Gemini with context
            logger.info("🤖 RAG Step 3: Generating response with Gemini...")
            response_text = self._generate_rag_response(
                augmented_prompt, max_output_tokens
            )
            
            response_data["response"] = response_text
            logger.info("✅ RAG response generated successfully")
            
            return response_data
            
        except Exception as e:
            logger.error(f"❌ Error generating RAG response: {str(e)}")
            return {
                "success": False,
                "error": str(e),
                "query": query,
                "fallback": True,
                "response": self._generate_fallback_response(query, system_prompt)
            }
    
    def _prepare_aug_prompt(
        self,
        query: str,
        relevant_chunks: List[str],
        system_prompt: str = None
    ) -> str:
        """
        Prepare the augmented prompt with retrieved context
        
        Args:
            query (str): User query
            relevant_chunks (List[str]): Retrieved relevant chunks
            system_prompt (str, optional): Base system prompt
            
        Returns:
            str: Augmented prompt for Gemini
        """
        # Format context from chunks
        context = "\n\n".join([
            f"Sumber {i+1}:\n{chunk}"
            for i, chunk in enumerate(relevant_chunks)
        ])
        
        if system_prompt:
            base_prompt = system_prompt
        else:
            base_prompt = ""
        
        # Create augmented prompt
        augmented = f"""{base_prompt}

=== KONTEKS RELEVAN DARI DATABASE ===
{context}

=== PERTANYAAN PENGGUNA ===
{query}

Petunjuk: Gunakan konteks RELEVAN di atas untuk menjawab pertanyaan pengguna. 
Jika konteks tidak cukup untuk menjawab, setidaknya gunakan informasi yang tersedia.
Selalu sebutkan sumber informasi jika relevan."""
        
        return augmented
    
    def _generate_rag_response(
        self,
        prompt: str,
        max_output_tokens: int = 1024
    ) -> str:
        """
        Generate response from Gemini with context
        
        Args:
            prompt (str): Augmented prompt with context
            max_output_tokens (int): Max tokens in response
            
        Returns:
            str: Generated response text
        """
        try:
            message = self.model.generate_content(
                prompt,
                generation_config=genai.types.GenerationConfig(
                    max_output_tokens=max_output_tokens,
                    temperature=0.7,
                    top_p=0.95,
                    top_k=40
                )
            )
            
            return message.text
            
        except Exception as e:
            logger.error(f"❌ Error generating Gemini response: {str(e)}")
            raise
    
    def _generate_direct_response(
        self,
        query: str,
        system_prompt: str = None,
        max_output_tokens: int = 1024
    ) -> str:
        """
        Generate response without RAG context
        Used as fallback or when RAG is disabled
        
        Args:
            query (str): User query
            system_prompt (str, optional): System prompt
            max_output_tokens (int): Max tokens
            
        Returns:
            str: Generated response
        """
        if system_prompt:
            prompt = f"{system_prompt}\n\nPertanyaan pengguna: {query}"
        else:
            prompt = query
        
        try:
            message = self.model.generate_content(
                prompt,
                generation_config=genai.types.GenerationConfig(
                    max_output_tokens=max_output_tokens,
                    temperature=0.7,
                    top_p=0.95,
                    top_k=40
                )
            )
            
            return message.text
            
        except Exception as e:
            logger.error(f"❌ Error generating direct response: {str(e)}")
            raise
    
    def _generate_fallback_response(
        self,
        query: str,
        system_prompt: str = None
    ) -> str:
        """
        Generate fallback response when no relevant context found
        
        Args:
            query (str): User query
            system_prompt (str, optional): System prompt
            
        Returns:
            str: Fallback response text
        """
        fallback_prompt = """Anda adalah asisten pelayanan Stasiun Geofisika Sleman.
Maaf, saat ini kami tidak memiliki informasi spesifik di database kami untuk pertanyaan Anda.

Namun, kami masih dapat membantu:
1. Silakan hubungi tim BMKG Sleman melalui:
   - WhatsApp untuk pertanyaan cepat
   - Email untuk pertanyaan formal
   - Website resmi Stasiun Geofisika Sleman

2. Jika Anda tertarik dengan layanan kami, ada 7 jenis permohonan yang kami sediakan.

Pertanyaan Anda: """ + query
        
        return self._generate_direct_response(
            fallback_prompt, system_prompt
        )
