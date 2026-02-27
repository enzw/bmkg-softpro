"""
Ingestion Service
Handles document ingestion, text chunking, and storage
Splits documents into overlapping chunks for efficient RAG
"""
import logging
import hashlib
from typing import List, Tuple
import tiktoken
from sqlalchemy.orm import Session
from config import CHUNK_SIZE, CHUNK_OVERLAP
from models import Document, TextChunk
from embedding_service import EmbeddingService

logger = logging.getLogger(__name__)


class IngestionService:
    """
    Service for ingesting documents into the RAG system
    Handles document processing, chunking, and embedding storage
    """
    
    def __init__(self, db: Session):
        """
        Initialize the ingestion service
        
        Args:
            db (Session): SQLAlchemy database session
        """
        self.db = db
        self.embedding_service = EmbeddingService()
        self.tokenizer = tiktoken.encoding_for_model("gpt-3.5-turbo")
        logger.info("✅ IngestionService initialized")
    
    def ingest_document(
        self,
        name: str,
        content: str,
        source: str = None
    ) -> Tuple[Document, List[TextChunk]]:
        """
        Ingest a document into the RAG system
        Chunks the document, generates embeddings, and stores in database
        
        Args:
            name (str): Document name/title
            content (str): Full document content
            source (str, optional): Source URL or file path
            
        Returns:
            Tuple[Document, List[TextChunk]]: Created document and its chunks
            
        Raises:
            ValueError: If document already exists (deduplication)
        """
        try:
            # Calculate content hash for deduplication
            content_hash = hashlib.sha256(content.encode()).hexdigest()
            
            # Check if document already exists
            existing = self.db.query(Document).filter(
                Document.content_hash == content_hash
            ).first()
            
            if existing:
                logger.warning(f"⚠️  Document already exists: {existing.name} (hash: {content_hash[:8]}...)")
                return existing, existing.chunks
            
            # Create document record
            document = Document(
                name=name,
                content=content,
                source=source,
                content_hash=content_hash
            )
            self.db.add(document)
            self.db.flush()  # Get the document ID
            
            logger.info(f"📄 Processing document: {name}")
            
            # Split document into chunks
            chunks = self._split_into_chunks(content, document.id)
            logger.info(f"✂️  Split into {len(chunks)} chunks")
            
            # Generate embeddings for each chunk
            chunk_texts = [chunk.chunk_text for chunk in chunks]
            embeddings = self.embedding_service.generate_batch_embeddings(chunk_texts)
            
            # Store embeddings in chunks
            for chunk, embedding in zip(chunks, embeddings):
                chunk.embedding = embedding
                self.db.add(chunk)
            
            # Commit all changes
            self.db.commit()
            logger.info(f"✅ Successfully ingested document: {name}")
            
            return document, chunks
            
        except Exception as e:
            self.db.rollback()
            logger.error(f"❌ Error ingesting document: {str(e)}")
            raise
    
    def update_document(
        self,
        document_id: int,
        new_content: str
    ) -> Tuple[Document, List[TextChunk]]:
        """
        Update an existing document with new content
        Removes old chunks and creates new ones
        
        Args:
            document_id (int): ID of document to update
            new_content (str): New document content
            
        Returns:
            Tuple[Document, List[TextChunk]]: Updated document and new chunks
        """
        try:
            # Get existing document
            document = self.db.query(Document).filter(
                Document.id == document_id
            ).first()
            
            if not document:
                raise ValueError(f"Document with ID {document_id} not found")
            
            # Delete old chunks
            self.db.query(TextChunk).filter(
                TextChunk.document_id == document_id
            ).delete()
            
            # Update document content
            document.content = new_content
            document.content_hash = hashlib.sha256(new_content.encode()).hexdigest()
            
            # Create new chunks
            chunks = self._split_into_chunks(new_content, document_id)
            
            # Generate embeddings
            chunk_texts = [chunk.chunk_text for chunk in chunks]
            embeddings = self.embedding_service.generate_batch_embeddings(chunk_texts)
            
            # Store embeddings
            for chunk, embedding in zip(chunks, embeddings):
                chunk.embedding = embedding
                self.db.add(chunk)
            
            self.db.commit()
            logger.info(f"✅ Successfully updated document: {document.name}")
            
            return document, chunks
            
        except Exception as e:
            self.db.rollback()
            logger.error(f"❌ Error updating document: {str(e)}")
            raise
    
    def delete_document(self, document_id: int) -> bool:
        """
        Delete a document and all its chunks
        
        Args:
            document_id (int): ID of document to delete
            
        Returns:
            bool: True if successful
        """
        try:
            document = self.db.query(Document).filter(
                Document.id == document_id
            ).first()
            
            if not document:
                logger.warning(f"⚠️  Document with ID {document_id} not found")
                return False
            
            # Delete document (cascades to chunks)
            self.db.delete(document)
            self.db.commit()
            
            logger.info(f"✅ Successfully deleted document: {document.name}")
            return True
            
        except Exception as e:
            self.db.rollback()
            logger.error(f"❌ Error deleting document: {str(e)}")
            raise
    
    def _split_into_chunks(
        self,
        content: str,
        document_id: int
    ) -> List[TextChunk]:
        """
        Split document content into overlapping chunks
        Uses token-based chunking for consistent sizes
        
        Args:
            content (str): Document content
            document_id (int): ID of parent document
            
        Returns:
            List[TextChunk]: List of chunks
        """
        # Tokenize content
        tokens = self.tokenizer.encode(content)
        
        chunks = []
        chunk_index = 0
        start_char = 0
        
        # Create overlapping chunks
        for i in range(0, len(tokens), CHUNK_SIZE - CHUNK_OVERLAP):
            # Get chunk tokens
            chunk_tokens = tokens[i:i + CHUNK_SIZE]
            
            if len(chunk_tokens) == 0:
                break
            
            # Decode chunk back to text
            chunk_text = self.tokenizer.decode(chunk_tokens)
            
            # Find character positions in original content
            end_char = content.find(chunk_text, start_char) + len(chunk_text)
            if end_char == -1 + len(chunk_text):  # Not found
                end_char = start_char + len(chunk_text)
            
            # Create chunk object
            chunk = TextChunk(
                document_id=document_id,
                chunk_text=chunk_text,
                chunk_index=chunk_index,
                start_char=start_char,
                end_char=end_char
            )
            
            chunks.append(chunk)
            
            # Update positions for next iteration
            start_char = end_char
            chunk_index += 1
            
            # Stop if chunk is too small
            if len(chunk_tokens) < CHUNK_SIZE // 2:
                break
        
        logger.debug(f"📦 Created {len(chunks)} chunks with overlap")
        return chunks
    
    def get_document_stats(self, document_id: int) -> dict:
        """
        Get statistics about a document and its chunks
        
        Args:
            document_id (int): ID of document
            
        Returns:
            dict: Statistics about the document
        """
        document = self.db.query(Document).filter(
            Document.id == document_id
        ).first()
        
        if not document:
            return None
        
        chunks = self.db.query(TextChunk).filter(
            TextChunk.document_id == document_id
        ).all()
        
        return {
            "document_id": document.id,
            "name": document.name,
            "source": document.source,
            "num_chunks": len(chunks),
            "total_content_length": len(document.content),
            "created_at": document.created_at.isoformat(),
            "updated_at": document.updated_at.isoformat()
        }
