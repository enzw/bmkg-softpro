#!/usr/bin/env python3
"""
RAG System Initialization Script
Sets up the database, creates tables, and tests the RAG system
Run this script once to initialize the RAG system
"""
import sys
import os
import logging
from pathlib import Path

# Add parent directory to path
sys.path.insert(0, str(Path(__file__).parent))

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

def check_dependencies():
    """Check if required dependencies are installed"""
    logger.info("🔍 Checking dependencies...")
    
    required_packages = [
        'flask',
        'google.generativeai',
        'sqlalchemy',
        'psycopg2',
        'pgvector',
        'nltk',
        'tiktoken'
    ]
    
    missing = []
    for package in required_packages:
        try:
            __import__(package.replace('-', '_'))
            logger.info(f"  ✅ {package}")
        except ImportError:
            logger.error(f"  ❌ {package}")
            missing.append(package)
    
    if missing:
        logger.error(f"\n❌ Missing packages: {', '.join(missing)}")
        logger.info("\nTo install missing packages, run:")
        logger.info(f"  pip install -r requirements.txt")
        return False
    
    logger.info("✅ All dependencies installed\n")
    return True

def check_environment():
    """Check if environment variables are properly configured"""
    logger.info("🔍 Checking environment configuration...")
    
    from config import GOOGLE_AI_KEY, POSTGRES_HOST, POSTGRES_DB
    
    errors = []
    
    if not GOOGLE_AI_KEY:
        errors.append("GOOGLE_AI_KEY not set")
    else:
        logger.info(f"  ✅ GOOGLE_AI_KEY is set")
    
    logger.info(f"  ✅ PostgreSQL Host: {POSTGRES_HOST}")
    logger.info(f"  ✅ PostgreSQL Database: {POSTGRES_DB}")
    
    if errors:
        logger.error(f"\n❌ Configuration errors:")
        for error in errors:
            logger.error(f"  - {error}")
        logger.info("\nPlease set missing environment variables in .env file")
        return False
    
    logger.info("✅ Environment configured\n")
    return True

def initialize_database():
    """Initialize the database and create tables"""
    logger.info("📊 Initializing database...")
    
    try:
        from config import engine, Base
        from models import Document, TextChunk
        
        # Create tables
        Base.metadata.create_all(bind=engine)
        logger.info("✅ Database tables created successfully\n")
        return True
        
    except Exception as e:
        logger.error(f"❌ Error initializing database: {str(e)}\n")
        logger.error("Make sure PostgreSQL is running and pgvector extension is installed")
        logger.error("\nTo install pgvector on PostgreSQL, run:")
        logger.error("  CREATE EXTENSION IF NOT EXISTS vector;")
        return False

def test_embeddings():
    """Test embedding generation"""
    logger.info("🧪 Testing embedding service...")
    
    try:
        from embedding_service import EmbeddingService
        
        service = EmbeddingService()
        test_text = "Ini adalah teks uji coba untuk embedding"
        embedding = service.generate_embedding(test_text)
        
        if embedding and len(embedding) == 768:
            logger.info(f"✅ Embedding generated successfully (dimension: 768)\n")
            return True
        else:
            logger.error(f"❌ Embedding has wrong dimension: {len(embedding)}\n")
            return False
            
    except Exception as e:
        logger.error(f"❌ Error testing embeddings: {str(e)}\n")
        return False

def test_database_connection():
    """Test database connection"""
    logger.info("🧪 Testing database connection...")
    
    try:
        from config import get_db_session, close_db_session
        
        db = get_db_session()
        # Simple query to test connection
        db.execute("SELECT 1")
        close_db_session(db)
        
        logger.info("✅ Database connection successful\n")
        return True
        
    except Exception as e:
        logger.error(f"❌ Database connection failed: {str(e)}\n")
        logger.error("Make sure PostgreSQL is running and credentials are correct")
        return False

def create_sample_document(db):
    """Create a sample document for testing"""
    logger.info("📄 Creating sample document...")
    
    try:
        from ingestion_service import IngestionService
        
        service = IngestionService(db)
        
        sample_content = """
        Stasiun Geofisika Sleman adalah bagian dari Badan Meteorologi, Klimatologi, dan Geofisika (BMKG) 
        yang melayani masyarakat dengan berbagai permohonan layanan geofisika.
        
        Kami menyediakan 7 jenis permohonan layanan:
        1. Jasa Sewa Alat Meteorologi
        2. Permohonan Kunjungan
        3. Permohonan Magang
        4. Layanan Data Geofisika
        5. Layanan Konsultasi
        6. Layanan Survey
        7. Klaim Asuransi
        
        Untuk setiap permohonan, silakan isi form yang tersedia di aplikasi dengan data yang lengkap dan akurat.
        Tim kami akan menghubungi Anda melalui WhatsApp untuk follow-up.
        """
        
        document, chunks = service.ingest_document(
            name="BMKG Sleman - Panduan Layanan",
            content=sample_content,
            source="https://bmkg.go.id"
        )
        
        logger.info(f"✅ Sample document created: {document.name} ({len(chunks)} chunks)\n")
        return True
        
    except Exception as e:
        logger.error(f"❌ Error creating sample document: {str(e)}\n")
        return False

def test_rag_pipeline(db):
    """Test the complete RAG pipeline"""
    logger.info("🧪 Testing RAG pipeline...")
    
    try:
        from rag_service import RAGService
        
        service = RAGService(db)
        
        test_query = "Apa saja layanan yang disediakan BMKG Sleman?"
        
        response = service.generate_response(
            query=test_query,
            use_rag=True,
            top_k=3
        )
        
        if response.get('success'):
            logger.info(f"✅ RAG pipeline test successful")
            logger.info(f"   Query: {test_query}")
            logger.info(f"   Sources: {len(response.get('sources', []))} documents")
            logger.info(f"   Response: {response['response'][:100]}...\n")
            return True
        else:
            logger.error(f"❌ RAG pipeline test failed: {response.get('error')}\n")
            return False
            
    except Exception as e:
        logger.error(f"❌ Error testing RAG pipeline: {str(e)}\n")
        return False

def main():
    """Run all initialization steps"""
    logger.info("=" * 60)
    logger.info("🚀 RAG System Initialization")
    logger.info("=" * 60 + "\n")
    
    # Step 1: Check dependencies
    if not check_dependencies():
        logger.error("\n❌ Dependency check failed")
        return False
    
    # Step 2: Check environment
    if not check_environment():
        logger.error("\n❌ Environment check failed")
        return False
    
    # Step 3: Test database connection
    if not test_database_connection():
        logger.error("\n❌ Database connection failed")
        return False
    
    # Step 4: Initialize database
    if not initialize_database():
        logger.error("\n❌ Database initialization failed")
        return False
    
    # Step 5: Test embeddings
    if not test_embeddings():
        logger.warning("\n⚠️  Embedding test failed - check GOOGLE_AI_KEY")
    
    # Step 6: Test with sample document
    try:
        from config import get_db_session, close_db_session
        
        db = get_db_session()
        try:
            if create_sample_document(db):
                if test_rag_pipeline(db):
                    logger.info("✅ All tests passed! RAG system is ready to use")
                    logger.info("\n" + "=" * 60)
                    logger.info("📝 Next Steps:")
                    logger.info("=" * 60)
                    logger.info("\n1. Start the RAG API server:")
                    logger.info("   python app.py")
                    logger.info("\n2. In another terminal, start the chatbot:")
                    logger.info("   node chatbot-server-rag.js")
                    logger.info("\n3. Test the chatbot:")
                    logger.info("   curl -X POST http://localhost:3001/chat \\")
                    logger.info('     -H "Content-Type: application/json" \\')
                    logger.info('     -d \'{"message": "Apa layanan BMKG Sleman?"}\'')
                    logger.info("\n" + "=" * 60 + "\n")
                    return True
        finally:
            close_db_session(db)
    except Exception as e:
        logger.error(f"❌ Error in final steps: {str(e)}")
        return False

if __name__ == '__main__':
    success = main()
    sys.exit(0 if success else 1)
