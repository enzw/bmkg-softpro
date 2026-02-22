<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class GuestUserSeeder extends Seeder
{
    /**
     * Run the database seed.
     */
    public function run(): void
    {
        // Create guest user with id=999 for chatbot feedback
        $guestExists = User::where('id', 999)->exists();
        
        if (!$guestExists) {
            User::forceCreate([
                'id' => 999,
                'name' => 'Guest User (Chatbot)',
                'email' => 'guest@chatbot.local',
                'no_identitas' => '9999999999',
                'pekerjaan' => 'Guest',
                'pendidikan' => 'N/A',
                'telp' => '082199999999',
                'alamat' => 'N/A',
                'password' => bcrypt('guest_chatbot_999'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
