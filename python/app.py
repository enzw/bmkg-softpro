"""
Flask RAG API Server
Provides REST API endpoints for document ingestion, retrieval, and RAG response generation
"""
import logging
import os
from flask import Flask, request, jsonify
from flask_cors import CORS
from config import (
    FLASK_PORT, FLASK_ENV, get_db_session, close_db_session,
    validate_configuration, logger
)
from models import Base, Document
from config import engine
from ingestion_service import IngestionService
from retrieval_service import RetrievalService
from rag_service import RAGService

# ============================================================================
# FLASK APP INITIALIZATION
# ============================================================================

app = Flask(__name__)
CORS(app)

# Set logging
logging.basicConfig(level=logging.INFO)

# ============================================================================
# DATABASE INITIALIZATION
# ============================================================================

def init_db():
    """Initialize database tables"""
    try:
        logger.info("🔧 Initializing database...")
        Base.metadata.create_all(bind=engine)
        logger.info("✅ Database initialized successfully")
    except Exception as e:
        logger.error(f"❌ Error initializing database: {str(e)}")
        raise

# Initialize database on startup
init_db()

# ============================================================================
# HEALTH CHECK ENDPOINTS
# ============================================================================

@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint"""
    return jsonify({
        "status": "OK",
        "service": "RAG API",
        "environment": FLASK_ENV
    }), 200

@app.route('/stats', methods=['GET'])
def get_stats():
    """Get system statistics"""
    db = get_db_session()
    try:
        retrieval_service = RetrievalService(db)
        stats = retrieval_service.get_retrieval_stats()
        return jsonify({
            "success": True,
            "stats": stats
        }), 200
    except Exception as e:
        logger.error(f"❌ Error getting stats: {str(e)}")
        return jsonify({
            "success": False,
            "error": str(e)
        }), 500
    finally:
        close_db_session(db)

# ============================================================================
# DOCUMENT INGESTION ENDPOINTS
# ============================================================================

@app.route('/api/documents', methods=['POST'])
def ingest_document():
    """
    Ingest a new document into the RAG system
    
    Request JSON:
    {
        "name": "Document Title",
        "content": "Full document content...",
        "source": "optional_source_url"
    }
    
    Response:
    {
        "success": true,
        "document": { "id": 1, "name": "...", "chunks": 5 },
        "message": "Document ingested successfully"
    }
    """
    try:
        data = request.get_json()
        
        if not data or 'name' not in data or 'content' not in data:
            return jsonify({
                "success": False,
                "error": "Missing required fields: name, content"
            }), 400
        
        db = get_db_session()
        try:
            ingestion_service = IngestionService(db)
            
            document, chunks = ingestion_service.ingest_document(
                name=data['name'],
                content=data['content'],
                source=data.get('source', None)
            )
            
            return jsonify({
                "success": True,
                "message": "Document ingested successfully",
                "document": {
                    "id": document.id,
                    "name": document.name,
                    "source": document.source,
                    "num_chunks": len(chunks),
                    "content_length": len(document.content)
                }
            }), 201
            
        finally:
            close_db_session(db)
            
    except ValueError as e:
        logger.warning(f"⚠️  Validation error: {str(e)}")
        return jsonify({
            "success": False,
            "error": str(e)
        }), 409
    except Exception as e:
        logger.error(f"❌ Error ingesting document: {str(e)}")
        return jsonify({
            "success": False,
            "error": str(e)
        }), 500

@app.route('/api/documents/<int:doc_id>', methods=['PUT'])
def update_document(doc_id):
    """
    Update an existing document
    
    Request JSON:
    {
        "content": "Updated document content..."
    }
    """
    try:
        data = request.get_json()
        
        if not data or 'content' not in data:
            return jsonify({
                "success": False,
                "error": "Missing required field: content"
            }), 400
        
        db = get_db_session()
        try:
            ingestion_service = IngestionService(db)
            
            document, chunks = ingestion_service.update_document(
                document_id=doc_id,
                new_content=data['content']
            )
            
            return jsonify({
                "success": True,
                "message": "Document updated successfully",
                "document": {
                    "id": document.id,
                    "name": document.name,
                    "num_chunks": len(chunks)
                }
            }), 200
            
        finally:
            close_db_session(db)
            
    except ValueError as e:
        return jsonify({
            "success": False,
            "error": str(e)
        }), 404
    except Exception as e:
        logger.error(f"❌ Error updating document: {str(e)}")
        return jsonify({
            "success": False,
            "error": str(e)
        }), 500

@app.route('/api/documents/<int:doc_id>', methods=['DELETE'])
def delete_document(doc_id):
    """Delete a document and its chunks"""
    try:
        db = get_db_session()
        try:
            ingestion_service = IngestionService(db)
            
            success = ingestion_service.delete_document(doc_id)
            
            if not success:
                return jsonify({
                    "success": False,
                    "error": f"Document with ID {doc_id} not found"
                }), 404
            
            return jsonify({
                "success": True,
                "message": f"Document {doc_id} deleted successfully"
            }), 200
            
        finally:
            close_db_session(db)
            
    except Exception as e:
        logger.error(f"❌ Error deleting document: {str(e)}")
        return jsonify({
            "success": False,
            "error": str(e)
        }), 500

@app.route('/api/documents/<int:doc_id>/stats', methods=['GET'])
def get_document_stats(doc_id):
    """Get statistics about a specific document"""
    try:
        db = get_db_session()
        try:
            ingestion_service = IngestionService(db)
            
            stats = ingestion_service.get_document_stats(doc_id)
            
            if not stats:
                return jsonify({
                    "success": False,
                    "error": f"Document with ID {doc_id} not found"
                }), 404
            
            return jsonify({
                "success": True,
                "stats": stats
            }), 200
            
        finally:
            close_db_session(db)
            
    except Exception as e:
        logger.error(f"❌ Error getting document stats: {str(e)}")
        return jsonify({
            "success": False,
            "error": str(e)
        }), 500

# ============================================================================
# RETRIEVAL ENDPOINTS
# ============================================================================

@app.route('/api/search', methods=['POST'])
def search_chunks():
    """
    Search for relevant chunks based on a query
    
    Request JSON:
    {
        "query": "Search query",
        "top_k": 5,
        "threshold": 0.5
    }
    """
    try:
        data = request.get_json()
        
        if not data or 'query' not in data:
            return jsonify({
                "success": False,
                "error": "Missing required field: query"
            }), 400
        
        query = data['query']
        top_k = data.get('top_k', 5)
        threshold = data.get('threshold', 0.5)
        
        db = get_db_session()
        try:
            retrieval_service = RetrievalService(db)
            
            results = retrieval_service.retrieve_relevant_chunks(
                query=query,
                top_k=top_k,
                threshold=threshold
            )
            
            chunks_data = [
                {
                    "id": chunk.id,
                    "text": chunk.chunk_text[:500],  # Truncate for API response
                    "document_id": document.id,
                    "document_name": document.name,
                    "similarity_score": similarity
                }
                for chunk, similarity, document in results
            ]
            
            return jsonify({
                "success": True,
                "query": query,
                "num_results": len(chunks_data),
                "results": chunks_data
            }), 200
            
        finally:
            close_db_session(db)
            
    except Exception as e:
        logger.error(f"❌ Error searching chunks: {str(e)}")
        return jsonify({
            "success": False,
            "error": str(e)
        }), 500

# ============================================================================
# RAG ENDPOINT (Main - used by chatbot)
# ============================================================================

@app.route('/api/rag/chat', methods=['POST'])
def rag_chat():
    """
    Main RAG endpoint for generating grounded responses
    
    Request JSON:
    {
        "query": "User question",
        "system_prompt": "optional system prompt",
        "use_rag": true,
        "top_k": 5,
        "max_output_tokens": 1024
    }
    
    Response:
    {
        "success": true,
        "query": "...",
        "response": "Generated response with context",
        "sources": [{"id": 1, "name": "..."}],
        "relevant_chunks": ["chunk1", "chunk2"],
        "similarity_scores": [0.9, 0.8],
        "fallback": false
    }
    """
    try:
        data = request.get_json()
        
        if not data or 'query' not in data:
            return jsonify({
                "success": False,
                "error": "Missing required field: query"
            }), 400
        
        query = data['query']
        system_prompt = data.get('system_prompt', None)
        use_rag = data.get('use_rag', True)
        top_k = data.get('top_k', 5)
        max_output_tokens = data.get('max_output_tokens', 1024)
        
        db = get_db_session()
        try:
            rag_service = RAGService(db)
            
            response_data = rag_service.generate_response(
                query=query,
                system_prompt=system_prompt,
                use_rag=use_rag,
                top_k=top_k,
                max_output_tokens=max_output_tokens
            )
            
            return jsonify(response_data), 200 if response_data.get('success') else 500
            
        finally:
            close_db_session(db)
            
    except Exception as e:
        logger.error(f"❌ Error in RAG chat: {str(e)}")
        return jsonify({
            "success": False,
            "error": str(e),
            "response": "Terjadi kesalahan saat memproses permintaan Anda."
        }), 500

# ============================================================================
# ERROR HANDLERS
# ============================================================================

@app.errorhandler(404)
def not_found(error):
    """Handle 404 errors"""
    return jsonify({
        "success": False,
        "error": "Endpoint not found"
    }), 404

@app.errorhandler(500)
def server_error(error):
    """Handle 500 errors"""
    return jsonify({
        "success": False,
        "error": "Internal server error"
    }), 500

# ============================================================================
# SERVER STARTUP
# ============================================================================

if __name__ == '__main__':
    try:
        # Validate configuration
        validate_configuration()
        
        logger.info("=" * 60)
        logger.info("🚀 Starting RAG API Server")
        logger.info("=" * 60)
        logger.info(f"Environment: {FLASK_ENV}")
        logger.info(f"Port: {FLASK_PORT}")
        logger.info(f"Database: PostgreSQL with pgvector")
        logger.info("")
        logger.info("📚 Available Endpoints:")
        logger.info("  POST   /api/documents       - Ingest document")
        logger.info("  PUT    /api/documents/<id>  - Update document")
        logger.info("  DELETE /api/documents/<id>  - Delete document")
        logger.info("  GET    /api/documents/<id>/stats - Document stats")
        logger.info("  POST   /api/search         - Search chunks")
        logger.info("  POST   /api/rag/chat       - RAG response generation (Main)")
        logger.info("  GET    /health             - Health check")
        logger.info("  GET    /stats              - System stats")
        logger.info("=" * 60)
        
        app.run(
            host='0.0.0.0',
            port=FLASK_PORT,
            debug=(FLASK_ENV == 'development'),
            use_reloader=False
        )
        
    except ValueError as e:
        logger.error(f"❌ Configuration error: {str(e)}")
        logger.error("Please check your .env file and try again")
        exit(1)
    except Exception as e:
        logger.error(f"❌ Error starting server: {str(e)}")
        exit(1)
