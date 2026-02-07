<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MigrateKunjunganFilesToPrivateStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:kunjungan-files';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Migrate kunjungan files from public storage to private storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of kunjungan files from public to private storage...');

        // Migrate permohonan-kunjungan folder (user uploads)
        $publicPath = 'storage/app/public/permohonan-kunjungan';
        if (is_dir($publicPath)) {
            $this->migrateFolder($publicPath, 'permohonan-kunjungan');
            $this->info('✓ Migrated permohonan-kunjungan folder');
        } else {
            $this->warn('! Folder permohonan-kunjungan tidak ditemukan di public storage');
        }

        // Migrate permohonan/kunjungan folder (admin uploads)
        $publicPath2 = 'storage/app/public/permohonan/kunjungan';
        if (is_dir($publicPath2)) {
            $this->migrateFolder($publicPath2, 'permohonan/kunjungan');
            $this->info('✓ Migrated permohonan/kunjungan folder');
        }

        $this->info('Migration completed!');
        $this->warn('Important: You can now safely delete the files from public storage after verifying the migration was successful.');
    }

    /**
     * Migrate files from public storage to local (private) storage
     */
    private function migrateFolder($sourcePath, $relativePath)
    {
        // Ensure target directory exists
        Storage::disk('local')->makeDirectory($relativePath);

        // Get all files from source
        $files = File::allFiles($sourcePath);

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $contents = File::get($file->getPathname());
            
            // Store in local (private) storage
            Storage::disk('local')->put($relativePath . '/' . $filename, $contents);
            
            $this->line("  → Migrated: $filename");
        }
    }
}
