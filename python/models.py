"""
Database models for RAG system
Defines Document and TextChunk tables with vector embeddings
"""
from sqlalchemy import Column, String, Integer, DateTime, Text, ForeignKey, func
from sqlalchemy.orm import relationship
from pgvector.sqlalchemy import Vector
from datetime import datetime
from config import Base

# ============================================================================
# MODELS
# ============================================================================

class Document(Base):
    """
    Represents a source document for RAG
    Stores document metadata and original content
    """
    __tablename__ = 'documents'
    
    id = Column(Integer, primary_key=True, index=True)
    name = Column(String(255), nullable=False)  # Document name/title
    source = Column(String(255), nullable=True)  # Source URL or file path
    content = Column(Text, nullable=False)  # Full document content
    content_hash = Column(String(64), nullable=True, unique=True)  # For deduplication
    created_at = Column(DateTime, default=datetime.utcnow, index=True)
    updated_at = Column(DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    
    # Relationship with chunks
    chunks = relationship("TextChunk", back_populates="document", cascade="all, delete-orphan")
    
    def __repr__(self):
        return f"<Document(id={self.id}, name='{self.name}', chunks={len(self.chunks)})>"


class TextChunk(Base):
    """
    Represents a text chunk with embedding vector
    Split from source documents for efficient retrieval
    """
    __tablename__ = 'text_chunks'
    
    id = Column(Integer, primary_key=True, index=True)
    document_id = Column(Integer, ForeignKey('documents.id'), nullable=False, index=True)
    chunk_text = Column(Text, nullable=False)  # The actual chunk text (500-800 tokens)
    chunk_index = Column(Integer, nullable=False)  # Position in document
    start_char = Column(Integer, nullable=True)  # Character position in original document
    end_char = Column(Integer, nullable=True)  # End character position
    embedding = Column(Vector(768), nullable=True)  # Embedding vector (768 dimensions for Gemini)
    similarity_score = Column(None, nullable=True)  # Reserved for search results
    created_at = Column(DateTime, default=datetime.utcnow, index=True)
    updated_at = Column(DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    
    # Relationship with document
    document = relationship("Document", back_populates="chunks")
    
    def __repr__(self):
        return f"<TextChunk(id={self.id}, doc_id={self.document_id}, chunk_idx={self.chunk_index})>"
