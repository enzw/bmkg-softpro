# Cloudflare R2 Storage - Folder Structure Documentation

## Overview
BMKG application files are organized per service type in Cloudflare R2 bucket for better management, organization, and access control.

## Folder Structure
```
bmkg/ (AWS_BUCKET)
├── permohonan/
│   ├── sewa-alat/                    # Jasa Sewa Alat MKG
│   ├── kunjungan/                    # Permohonan Kunjungan
│   ├── kunjungan-teknis/             # Layanan Kunjungan Teknis
│   ├── survey/                       # Layanan Survey
│   ├── layanan-data/                 # Layanan Data Geofisika
│   ├── jasa-konsultasi/              # Jasa Konsultasi
│   ├── asuransi/                     # Layanan Klaim Asuransi
│   └── magang/                       # Magang
```

## Service Type Mapping

| Service | Folder Path | Table | Admin Route |
|---------|------------|-------|------------|
| Sewa Alat MKG | `permohonan/sewa-alat` | `sewa_alats` | `/admin/sewa-alat` |
| Permohonan Kunjungan | `permohonan/kunjungan` | `kunjungans` | `/admin/permohonan-kunjungan` |
| Layanan Kunjungan Teknis | `permohonan/kunjungan-teknis` | `magangs` | `/admin/pelayanan-jasa` |
| Layanan Survey | `permohonan/survey` | `surveys` | `/admin/survey` |
| Layanan Data Geofisika | `permohonan/layanan-data` | `layanan_datas` | `/admin/layanan-data` |
| Jasa Konsultasi | `permohonan/jasa-konsultasi` | `jasa_konsultasis` | `/admin/jasa-konsultasi` |
| Layanan Klaim Asuransi | `permohonan/asuransi` | `asuransis` | `/admin/klaim-asuransi` |
| Magang | `permohonan/magang` | `magangs` | `/admin/pelayanan-jasa` |

## File Naming Convention

All files follow this naming pattern:
```
{uniqid()}_{timestamp}.{extension}
```

Example: `61e4b5f52d1e0_1673456789.pdf`

This ensures:
- Unique file names even if uploaded simultaneously
- Chronological ordering based on timestamp
- Prevention of filename conflicts

## File Types & Size Limits

- **Allowed MIME Types**: pdf, jpg, jpeg, png
- **Max File Size**: 2048 KB (2 MB)

## Access Control

| Operation | Who Can | How |
|-----------|---------|-----|
| Upload | Service users | Via their service controller |
| Download | Authenticated users | Via presigned URLs (60 min expiration) |
| Delete | Admin only | Via admin panel or API |
| List Folder | Admin only | Via `/admin/files/folder/{serviceType}` |
| Get Stats | Admin only | Via `/admin/files/stats/{serviceType}` |

## API Endpoints (Admin Only)

### List Files in Folder
```
GET /admin/files/folder/{serviceType}
```
Returns all files in that service folder with metadata.

### Folder Statistics
```
GET /admin/files/stats/{serviceType}
```
Returns file count and total size for the folder.

### Delete File
```
DELETE /admin/file/{filename}
```
Deletes a specific file by name.

### Delete by Full Path
```
POST /admin/file/delete-by-path
Body: { "path": "permohonan/sewa-alat/filename.pdf" }
```

### Delete All Files in Folder
```
DELETE /admin/files/folder/{serviceType}
```
⚠️ Be careful - this deletes ALL files in that folder!

## Presigned URL Download

All file downloads use presigned URLs:
- Generated on-request via `HandlesFileDownload` trait
- Valid for **60 minutes**
- Allows direct download from Cloudflare R2 edge servers
- No server bandwidth used for file transfer

## Migration & Database

### Asuransi Table Schema
After migrations, the `asuransis` table includes:
- `nama_user` - User name
- `no_whatsapp` - WhatsApp number
- `tanggal` - Date of request  
- `lokasi` - Location
- `latitude` - Latitude coordinate
- `longitude` - Longitude coordinate
- `surat_permohonan` - Upload file path
- `ktp` - ID document file path
- `user_id` - Associated user (UUID)

## Security Notes

1. **No Public Access**: All folders are private - access via authenticated URLs only
2. **CSRF Protection**: All delete operations require CSRF token
3. **Authorization**: Model policies enforce role-based access
4. **Path Validation**: Directory traversal attempts are blocked
5. **Audit Logging**: All delete operations are logged via Laravel's logging system

## Usage Example

### Upload file via controller
```php
$directory = FilePathHelper::getServiceFolder('sewa-alat');
$path = $request->file('document')->storeAs($directory, $fileName, 's3');
```

### Access folder stats via admin
```bash
GET http://localhost:8000/admin/files/stats/sewa-alat
```

### Download presigned URL
```php
return $this->redirectToTemporaryUrl($filePath, 60); // 60 min expiration
```

## Cloudflare R2 Configuration

From `.env`:
```
AWS_ENDPOINT=https://c0239ec19f4a67091c337d8489b1a62f.r2.cloudflarestorage.com
AWS_BUCKET=bmkg
AWS_USE_PATH_STYLE_ENDPOINT=true
AWS_DEFAULT_REGION=auto
```

This enables:
- Private files in R2
- Path-style URL access
- Presigned temporary URL generation
- Automatic credential signing
