<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Password;

class TestPasswordReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:password-reset {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test password reset email. Usage: php artisan test:password-reset user@example.com';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        
        $this->info("🔍 Testing password reset for: {$email}");
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ User dengan email '{$email}' tidak ditemukan!");
            return 1;
        }
        
        $this->info("✅ User ditemukan: {$user->name}");
        
        try {
            $status = Password::sendResetLink(['email' => $email]);
            
            if ($status === Password::RESET_LINK_SENT) {
                $this->info("✅ Password reset link berhasil dikirim!");
                $this->line("📧 Cek email atau Mailpit UI: http://localhost:8025");
                return 0;
            } else {
                $this->error("❌ Gagal mengirim password reset link!");
                $this->error("Status: " . $status);
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return 1;
        }
    }
}
