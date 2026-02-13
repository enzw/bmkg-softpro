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
        if (!Storage::disk('s3')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Generate presigned URL valid for specified minutes
        return Storage::disk('s3')->temporaryUrl(
            $filePath,
            now()->addMinutes($expirationMinutes)
        );
    }

    /**
     * Redirect user to temporary download URL
     * File downloads directly from R2, not through Laravel
     */
    protected function redirectToTemporaryUrl($filePath, $expirationMinutes = 60)
    {
        $url = $this->getTemporaryDownloadUrl($filePath, $expirationMinutes);
        return redirect($url);
    }

    /**
     * Stream download from S3/R2 storage through Laravel
     * Use this if you need to log downloads or apply custom logic
     */
    protected function streamDownloadFromS3($filePath, $downloadName = null)
    {
        if (!Storage::disk('s3')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('s3')->download($filePath, $downloadName);
    }
}
