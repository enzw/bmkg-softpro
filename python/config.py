import os
import sys
import logging
from pathlib import Path
from dotenv import load_dotenv
from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker
from sqlalchemy.ext.declarative import declarative_base

# Load from root .env (parent directory of python folder)
root_env_path = Path(__file__).parent.parent / '.env'
load_dotenv(root_env_path)

# Also load from python/.env jika ada
local_env_path = Path(__file__).parent / '.env'
load_dotenv(local_env_path)

# ============================================================================
# ENVIRONMENT VARIABLES
# ============================================================================
GOOGLE_AI_KEY = os.getenv('GOOGLE_AI_KEY')
EMBEDDING_MODEL = os.getenv('EMBEDDING_MODEL', 'models/embedding-001')

# Database Configuration (dari root .env)
POSTGRES_HOST = os.getenv('POSTGRES_HOST', '127.0.0.1')
POSTGRES_PORT = os.getenv('POSTGRES_PORT', '5432')
POSTGRES_USER = os.getenv('POSTGRES_USER', 'postgres')
POSTGRES_PASSWORD = os.getenv('POSTGRES_PASSWORD', 'root')
POSTGRES_DB = os.getenv('POSTGRES_DB', 'bmkg_softpro')

# RAG Configuration
CHUNK_SIZE = int(os.getenv('CHUNK_SIZE', '500'))
CHUNK_OVERLAP = int(os.getenv('CHUNK_OVERLAP', '100'))
SIMILARITY_THRESHOLD = float(os.getenv('SIMILARITY_THRESHOLD', '0.5'))
TOP_K_RESULTS = int(os.getenv('TOP_K_RESULTS', '5'))

# Server Configuration
FLASK_PORT = int(os.getenv('FLASK_PORT', '5000'))
FLASK_ENV = os.getenv('FLASK_ENV', 'development')

# Logging
LOG_LEVEL = os.getenv('LOG_LEVEL', 'INFO')

# ============================================================================
# DATABASE SETUP
# ============================================================================
DATABASE_URL = f"postgresql+psycopg2://{POSTGRES_USER}:{POSTGRES_PASSWORD}@{POSTGRES_HOST}:{POSTGRES_PORT}/{POSTGRES_DB}"

engine = create_engine(DATABASE_URL, echo=False)
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
Base = declarative_base()

# ============================================================================
# LOGGING SETUP
# ============================================================================
logging.basicConfig(
    level=getattr(logging, LOG_LEVEL),
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

# ============================================================================
# VALIDATION
# ============================================================================
def validate_configuration():
    """Validate configuration"""
    errors = []
    
    if not GOOGLE_AI_KEY:
        errors.append("❌ GOOGLE_AI_KEY is not set")
    
    logger.info(f"✅ Using database: {POSTGRES_DB} @ {POSTGRES_HOST}")
    
    if errors:
        for error in errors:
            logger.error(error)
        raise ValueError("Missing required configuration")
    
    logger.info("✅ Configuration validation passed")