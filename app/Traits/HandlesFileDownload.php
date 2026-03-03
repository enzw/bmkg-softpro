<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HandlesFileDownload
{
    /**
     * Generate temporary download URL from S3/R2 storage
     * More scalable than streaming - direct download from Cloudflare R2
     */
    protected function getTemporaryDownloadUrl($filePath, $expirationMinutes = 60)
    {
        $defaultDisk = config('filesystems.default');
        $disk = in_array($defaultDisk, ['s3', 'r2']) ? $defaultDisk : 's3';

        try {
            // Generate presigned URL valid for specified minutes
            return Storage::disk($disk)->temporaryUrl(
                $filePath,
                now()->addMinutes($expirationMinutes)
            );
        } catch (\Exception $e) {
            \Log::error("Error generating temporary URL on disk [{$disk}]: " . $e->getMessage());

            // Fallback for local development or if S3 fails
            if (Storage::disk('local')->exists($filePath)) {
                \Log::info("Falling back to local storage URL for: {$filePath}");
                // Note: local driver doesn't support temporaryUrl by default, 
                // so we return the path to a local download route or use url()
                // For now, let's just use url() if it's local, or return the path
                return Storage::disk('local')->url($filePath);
            }

            throw $e;
        }
    }

    /**
     * Redirect user to temporary download URL
     * File downloads directly from cloud storage, not through Laravel
     */
    protected function redirectToTemporaryUrl($filePath, $expirationMinutes = 60)
    {
        try {
            $url = $this->getTemporaryDownloadUrl($filePath, $expirationMinutes);

            // Use away() for external URLs to ensure Laravel doesn't try to resolve it locally
            return redirect()->away($url);
        } catch (\Exception $e) {
            \Log::error("Failed to redirect to temporary URL: " . $e->getMessage());
            return back()->with('error', 'Gagal mengakses file: ' . $e->getMessage());
        }
    }

    /**
     * Stream download from S3/R2 storage through Laravel
     * Use this if you need to log downloads or apply custom logic
     */
    protected function streamDownloadFromS3($filePath, $downloadName = null)
    {
        $disk = config('filesystems.default') === 's3' || config('filesystems.default') === 'r2' ? config('filesystems.default') : 's3';

        if (!Storage::disk($disk)->exists($filePath)) {
            \Log::error("File not found for streaming on disk [{$disk}]: {$filePath}");
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk($disk)->download($filePath, $downloadName);
    }
}
