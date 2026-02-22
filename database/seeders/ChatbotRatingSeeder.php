<?php

namespace Database\Seeders;

use App\Models\Chatbot;
use App\Models\ServiceRating;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatbotRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample reviews
        $reviews = [
            'Chatbot ini sangat membantu! Jawaban yang diberikan akurat dan cepat.',
            'Pelayanan chatbot BMKG luar biasa, sangat responsif.',
            'Bagus sekali, dapat menjawab pertanyaan meteorologi dengan detail.',
            'Chatbot sudah cukup baik untuk menjawab pertanyaan umum.',
            'Membantu saya memahami layanan BMKG dengan lebih baik.',
            'Sangat memuaskan, rekomendasi ke teman dan keluarga.',
            'Chatbot bisa lebih baik lagi, kadang kurang detail.',
            'Cukup membantu, meskipun ada beberapa pertanyaan yang belum dijawab.',
            'Rating 5 bintang untuk kemudahan penggunaan.',
            'Layanan chatbot terbaik yang pernah saya gunakan.',
            'Informasi yang diberikan sangat bermanfaat.',
            'Responsif dan profesional.',
            'Membantu, tapi terkadang agak lambat merespons.',
            'Sangat puas dengan pelayanan chatbot BMKG.',
            'Chatbot informatif dan mudah digunakan.',
            'Pertanyaan saya terjawab dengan baik.',
            'Pelayanan memuaskan, terus tingkatkan.',
            'Membantu saya mendapatkan informasi yang dibutuhkan.',
            'Chatbot responsif dan memberikan informasi akurat.',
            'Sangat membantu untuk pemula yang ingin belajar tentang BMKG.',
        ];

        // Get authenticated users
        $authenticatedUsers = User::where('role', 'member')->get();

        // Create 15 ratings from authenticated users
        for ($i = 0; $i < 15; $i++) {
            $user = $authenticatedUsers->random();
            $rating = rand(3, 5);

            $chatbot = Chatbot::create([
                'session_id' => 'chatbot-' . time() . '-' . uniqid(),
                'user_id' => $user->id,
                'status' => 'completed',
            ]);

            ServiceRating::create([
                'user_id' => $user->id,
                'rateable_id' => $chatbot->id,
                'rateable_type' => Chatbot::class,
                'rating' => $rating,
                'review' => $reviews[array_rand($reviews)],
            ]);
        }

        // Create 5 guest ratings (null user_id)
        for ($i = 0; $i < 5; $i++) {
            $rating = rand(3, 5);

            $chatbot = Chatbot::create([
                'session_id' => 'chatbot-guest-' . time() . '-' . uniqid(),
                'user_id' => null,
                'status' => 'completed',
            ]);

            ServiceRating::create([
                'user_id' => null,
                'rateable_id' => $chatbot->id,
                'rateable_type' => Chatbot::class,
                'rating' => $rating,
                'review' => $reviews[array_rand($reviews)],
            ]);
        }

        $this->command->info('✅ Chatbot ratings seeded successfully!');
        $this->command->info('  - Created 15 authenticated user ratings');
        $this->command->info('  - Created 5 guest ratings');
    }
}
