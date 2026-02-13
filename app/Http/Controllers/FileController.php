<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FilePathHelper;

class FileController extends Controller
{
    /**
     * Get list of files from a specific service folder
     */
    public function listByFolder($serviceType)
    {
        // Authorize: only admins can list files
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $folderPath = FilePathHelper::getServiceFolder($serviceType);
            
            // List all files in the folder
            $files = Storage::disk('r2')->files($folderPath);
            
            $result = [];
            foreach ($files as $file) {
                $result[] = [
                    'path' => $file,
                    'name' => basename($file),
                    'size' => Storage::disk('r2')->size($file),
                    'last_modified' => Storage::disk('r2')->lastModified($file),
                ];
            }

            return response()->json([
                'message' => 'Success',
                'folder' => $folderPath,
                'service' => FilePathHelper::getFolderName($folderPath),
                'files' => $result,
                'total' => count($result),
            ], 200);

        } catch (\Exception $error) {
            \Log::error('File List Error: ' . $error->getMessage());
            return response()->json([
                'message' => 'Gagal mengambil daftar file: ' . $error->getMessage()
            ], 500);
        }
    }

    /**
     * Get folder storage statistics
     */
    public function folderStats($serviceType)
    {
        // Authorize: only admins
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $folderPath = FilePathHelper::getServiceFolder($serviceType);
            $files = Storage::disk('r2')->files($folderPath);
            
            $totalSize = 0;
            foreach ($files as $file) {
                $totalSize += Storage::disk('r2')->size($file);
            }

            return response()->json([
                'folder' => $folderPath,
                'service' => FilePathHelper::getFolderName($folderPath),
                'file_count' => count($files),
                'total_size_bytes' => $totalSize,
                'total_size_mb' => round($totalSize / 1024 / 1024, 2),
            ], 200);

        } catch (\Exception $error) {
            \Log::error('Folder Stats Error: ' . $error->getMessage());
            return response()->json([
                'message' => 'Gagal mengambil statistik folder: ' . $error->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a file from R2 storage
     */
    public function delete(Request $request, $filename)
    {
        // Authorize: only authenticated users can delete files
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        try {
            // Secure: validate filename to prevent directory traversal
            if (str_contains($filename, '..') || str_contains($filename, '/')) {
                return response()->json([
                    'message' => 'Invalid filename'
                ], 400);
            }

            $path = "uploads/" . $filename;

            // Check if file exists in R2
            if (!Storage::disk('r2')->exists($path)) {
                return response()->json([
                    'message' => 'File tidak ditemukan'
                ], 404);
            }

            // Delete file from R2
            Storage::disk('r2')->delete($path);

            return response()->json([
                'message' => 'File berhasil dihapus',
                'filename' => $filename
            ], 200);

        } catch (\Exception $error) {
            \Log::error('File Delete Error: ' . $error->getMessage());
            return response()->json([
                'message' => 'Gagal menghapus file: ' . $error->getMessage()
            ], 500);
        }
    }

    /**
     * Delete file by full path (for uploaded documents from models)
     */
    public function deleteByPath(Request $request)
    {
        // Authorize: only admins can delete files
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'path' => 'required|string'
        ]);

        try {
            $path = $request->input('path');

            // Check if file exists in R2
            if (!Storage::disk('r2')->exists($path)) {
                return response()->json([
                    'message' => 'File tidak ditemukan'
                ], 404);
            }

            // Delete file from R2
            Storage::disk('r2')->delete($path);

            return response()->json([
                'message' => 'File berhasil dihapus',
                'path' => $path
            ], 200);

        } catch (\Exception $error) {
            \Log::error('File Delete Error: ' . $error->getMessage());
            return response()->json([
                'message' => 'Gagal menghapus file: ' . $error->getMessage()
            ], 500);
        }
    }

    /**
     * Delete all files in a folder (admin only)
     */
    public function deleteFolderContents(Request $request, $serviceType)
    {
        // Authorize: only admins
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $folderPath = FilePathHelper::getServiceFolder($serviceType);
            $files = Storage::disk('r2')->files($folderPath);
            
            $deletedCount = 0;
            foreach ($files as $file) {
                Storage::disk('r2')->delete($file);
                $deletedCount++;
            }

            return response()->json([
                'message' => 'Folder berhasil dihapus',
                'folder' => $folderPath,
                'deleted_files' => $deletedCount,
            ], 200);

        } catch (\Exception $error) {
            \Log::error('Folder Delete Error: ' . $error->getMessage());
            return response()->json([
                'message' => 'Gagal menghapus folder: ' . $error->getMessage()
            ], 500);
        }
    }
}
