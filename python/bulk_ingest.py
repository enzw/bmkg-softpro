#!/usr/bin/env python3
"""
Bulk Document Ingestion Script
Ingest multiple documents from files or API
Supports: TXT, PDF, and JSON formats
"""
import json
import os
import sys
from pathlib import Path
import logging

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

def ingest_text_file(text_file_path: str, db_session):
    """Ingest a .txt file"""
    try:
        with open(text_file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        from ingestion_service import IngestionService
        service = IngestionService(db_session)
        
        document_name = Path(text_file_path).stem
        document, chunks = service.ingest_document(
            name=document_name,
            content=content,
            source=text_file_path
        )
        
        logger.info(f"✅ Ingested {document_name}: {len(chunks)} chunks")
        return document
        
    except Exception as e:
        logger.error(f"❌ Error ingesting {text_file_path}: {str(e)}")
        return None

def ingest_json_documents(json_file_path: str, db_session):
    """
    Ingest documents from JSON file
    Expected format:
    [
        {
            "name": "Document Title",
            "content": "Full content...",
            "source": "optional_source"
        },
        ...
    ]
    """
    try:
        with open(json_file_path, 'r', encoding='utf-8') as f:
            documents = json.load(f)
        
        from ingestion_service import IngestionService
        service = IngestionService(db_session)
        
        ingested = []
        for doc in documents:
            try:
                document, chunks = service.ingest_document(
                    name=doc.get('name'),
                    content=doc.get('content'),
                    source=doc.get('source')
                )
                ingested.append(document)
                logger.info(f"✅ Ingested: {document.name} ({len(chunks)} chunks)")
            except ValueError as e:
                logger.warning(f"⚠️  {str(e)}")
            except Exception as e:
                logger.error(f"❌ Error ingesting {doc.get('name')}: {str(e)}")
        
        return ingested
        
    except Exception as e:
        logger.error(f"❌ Error reading JSON file: {str(e)}")
        return None

def ingest_directory(directory_path: str, db_session, file_extension: str = '*.txt'):
    """Ingest all files from a directory"""
    try:
        directory = Path(directory_path)
        if not directory.exists():
            logger.error(f"❌ Directory not found: {directory_path}")
            return []
        
        from ingestion_service import IngestionService
        service = IngestionService(db_session)
        
        ingested = []
        for file_path in sorted(directory.glob(file_extension)):
            logger.info(f"📄 Processing: {file_path.name}")
            
            try:
                with open(file_path, 'r', encoding='utf-8') as f:
                    content = f.read()
                
                document, chunks = service.ingest_document(
                    name=file_path.stem,
                    content=content,
                    source=str(file_path)
                )
                ingested.append(document)
                logger.info(f"✅ Ingested {file_path.name}: {len(chunks)} chunks")
                
            except Exception as e:
                logger.error(f"❌ Error ingesting {file_path.name}: {str(e)}")
        
        logger.info(f"\n✅ Total documents ingested: {len(ingested)}")
        return ingested
        
    except Exception as e:
        logger.error(f"❌ Error processing directory: {str(e)}")
        return None

def create_sample_bmkg_documents(db_session):
    """Create sample BMKG documents for testing"""
    sample_docs = [
        {
            "name": "BMKG Sleman - Layanan Sewa Alat",
            "content": """
            Jasa Sewa Alat Meteorologi (MKG)
            
            Stasiun Geofisika Sleman menyediakan berbagai alat meteorologi untuk disewa.
            
            Peralatan yang tersedia:
            - Pluviograph (pencatat hujan otomatis)
            - Barograph (pencatat tekanan udara)
            - Thermograph (pencatat suhu)
            - Anemometer (pengukur kecepatan angin)
            - Weather Station Portable
            
            Prosedur Penyewaan:
            1. Isi form "Jasa Sewa Alat MKG" di aplikasi
            2. Pilih alat yang akan disewa
            3. Tentukan periode penyewaan
            4. Siapkan dokumen pendukung (surat permohonan, KTP)
            5. Tunggu konfirmasi via WhatsApp
            
            Harga dan Syarat:
            - Tarif per hari/minggu/bulan
            - Deposit keamanan diperlukan
            - Asuransi alat ditanggung penyewa
            - Pengiriman dapat diatur
            
            Informasi: hubungi via WhatsApp untuk detail terkini.
            """
        },
        {
            "name": "BMKG Sleman - Program Magang",
            "content": """
            Program Magang (Internship)
            
            Stasiun Geofisika Sleman menerima mahasiswa untuk program magang.
            
            Persyaratan:
            - Mahasiswa aktif dari universitas terakreditasi
            - Minimal semester 4
            - Memiliki minat pada geofisika/meteorologi
            - Rekomendasi dari kampus
            
            Program Magang Meliputi:
            - Pelatihan penggunaan alat geofisika
            - Praktek lapangan pengumpulan data
            - Analisis dan interpretasi data
            - Pemahaman mitigasi bencana alam
            - Sertifikat kelulusan program
            
            Durasi: 
            - Minimum 3 bulan
            - Maksimum 6 bulan
            - Fleksibel sesuai kurikulum kampus
            
            Proses Pendaftaran:
            1. Isi form "Pelayanan Informasi Geofisika" → pilih "Magang"
            2. Siapkan surat dari kampus
            3. Siapkan kartu mahasiswa
            4. Tunggu penerimaan dari tim
            
            Benefit:
            - Pengalaman praktis geofisika
            - Sertifikat magang resmi BMKG
            - Jaringan profesional di bidang geofisika
            - Portfolio untuk karir masa depan
            """
        },
        {
            "name": "BMKG Sleman - Layanan Konsultasi",
            "content": """
            Layanan Konsultasi Geofisika
            
            Kami menyediakan konsultasi profesional di bidang geofisika dan meteorologi.
            
            Topik Konsultasi:
            - Analisis bahaya gempa untuk AMDAL/RTRW
            - Perlindungan infrastruktur dari bahaya alam
            - Penelitian geofisika khusus
            - Pelatihan interpretasi data geofisika
            - Studi mitigasi bencana
            
            Konsultan:
            - Tim expert dengan sertifikasi internasional
            - Pengalaman lebih dari 15 tahun
            - Pernah menangani proyek nasional
            
            Proses Konsultasi:
            1. Isi form "Layanan Konsultasi"
            2. Jelaskan masalah/kebutuhan secara detail
            3. Tim akan menghubungi untuk penjadwalan
            4. Pertemuan awal untuk scope definition
            5. Pelaksanaan konsultasi
            6. Laporan final dengan rekomendasi
            
            Waktu Turnaround:
            - Konsultasi sederhana: 1-2 minggu
            - Studi menengah: 2-4 minggu
            - Proyek besar: sesuai kesepakatan
            
            Biaya:
            - Konsultasi jam: Rp X per jam
            - Fixed project: sesuai scope
            - Gratis konsultasi awal
            """
        },
        {
            "name": "BMKG Sleman - Kriteria File Upload",
            "content": """
            Panduan Upload File dan Dokumen
            
            Setiap permohonan memerlukan dokumen pendukung. Berikut panduan lengkap.
            
            Format File yang Diterima:
            - PDF
            - JPG/JPEG
            - PNG
            - GIF (untuk dokumen foto)
            
            Ukuran File:
            - Maksimum 2 MB per file
            - Untuk dokumen multi-halaman, total tidak boleh > 2 MB
            
            Kualitas Dokumen:
            - Wajib JELAS dan TERBACA BAIK
            - Tidak blur, tidak rusak, tidak putus
            - Untuk ID/KTP: kedua sisi harus terlihat
            - Untuk surat: semua halaman harus terbaca
            
            Spesifikasi Foto/Scan:
            - Resolusi minimum 300 DPI untuk scan
            - Cahaya cukup dan merata
            - Background netral, tidak gelap
            - Teks harus mudah dibaca
            
            Tips Upload Sukses:
            1. Scan/foto dengan posisi lurus (tidak miring)
            2. Pastikan seluruh dokumen termasuk dalam frame
            3. Cek ukuran file sebelum upload
            4. Kompresi jika perlu dengan tool online
            5. Coba buka file untuk memastikan kualitas
            
            Jika Dokumen Ditolak:
            - Anda akan dihubungi via WhatsApp
            - Silakan re-upload dengan kualitas lebih baik
            - Bantuan teknis tersedia
            """
        }
    ]
    
    from ingestion_service import IngestionService
    service = IngestionService(db_session)
    
    logger.info("📄 Creating sample BMKG documents...")
    ingested_count = 0
    
    for doc in sample_docs:
        try:
            document, chunks = service.ingest_document(
                name=doc['name'],
                content=doc['content']
            )
            logger.info(f"✅ Created: {document.name} ({len(chunks)} chunks)")
            ingested_count += 1
        except ValueError as e:
            logger.warning(f"⚠️  Document might already exist: {doc['name']}")
        except Exception as e:
            logger.error(f"❌ Error creating document: {str(e)}")
    
    logger.info(f"\n✅ Created {ingested_count} sample documents")
    return ingested_count > 0

def main():
    """Main function"""
    import argparse
    
    parser = argparse.ArgumentParser(
        description='Bulk ingest documents into RAG system'
    )
    parser.add_argument(
        '--file', '-f',
        help='Single file to ingest (.txt or .json)'
    )
    parser.add_argument(
        '--directory', '-d',
        help='Directory of .txt files to ingest'
    )
    parser.add_argument(
        '--sample',
        action='store_true',
        help='Create sample BMKG documents for testing'
    )
    
    args = parser.parse_args()
    
    # Initialize database session
    from config import get_db_session, close_db_session
    
    db = get_db_session()
    
    try:
        if args.sample:
            create_sample_bmkg_documents(db)
        elif args.file:
            if args.file.endswith('.json'):
                ingest_json_documents(args.file, db)
            else:
                ingest_text_file(args.file, db)
        elif args.directory:
            ingest_directory(args.directory, db)
        else:
            parser.print_help()
    finally:
        close_db_session(db)

if __name__ == '__main__':
    main()
