<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Chatbot;
use App\Models\ServiceRating;

class ChatbotRatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_submit_chatbot_rating()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/chatbot/rate', [
            'session_id' => 'session-' . uniqid(),
            'rating' => 5,
            'review' => 'Chatbot ini sangat membantu!',
        ]);

        $response->assertSuccessful();
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('message', 'Rating telah disimpan. Terima kasih atas feedback Anda!');

        // Verify rating was saved
        $this->assertDatabaseCount('service_ratings', 1);
        $this->assertDatabaseHas('service_ratings', [
            'user_id' => $user->id,
            'rating' => 5,
            'rateable_type' => Chatbot::class,
        ]);

        // Verify chatbot session was created
        $this->assertDatabaseCount('chatbots', 1);
        $chatbot = Chatbot::first();
        $this->assertEquals($user->id, $chatbot->user_id);
        $this->assertEquals('completed', $chatbot->status);
    }

    public function test_guest_user_can_submit_chatbot_rating()
    {
        $response = $this->postJson('/api/chatbot/rate', [
            'session_id' => 'guest-session-' . uniqid(),
            'rating' => 4,
            'review' => 'Bagus, tapi bisa lebih baik',
        ]);

        $response->assertSuccessful();
        $response->assertJsonPath('success', true);

        // Verify rating was saved with null user_id
        $this->assertDatabaseHas('service_ratings', [
            'user_id' => null,
            'rating' => 4,
            'rateable_type' => Chatbot::class,
        ]);
    }

    public function test_duplicate_session_rating_updates_existing()
    {
        $user = User::factory()->create();
        $sessionId = 'session-' . uniqid();

        // First rating
        $this->actingAs($user)->postJson('/api/chatbot/rate', [
            'session_id' => $sessionId,
            'rating' => 3,
            'review' => 'Cukup membantu',
        ]);

        $this->assertDatabaseCount('service_ratings', 1);
        $this->assertDatabaseCount('chatbots', 1);

        // Update with same session_id
        $this->actingAs($user)->postJson('/api/chatbot/rate', [
            'session_id' => $sessionId,
            'rating' => 5,
            'review' => 'Sangat membantu!',
        ]);

        // Should still have 1 rating (updated)
        $this->assertDatabaseCount('service_ratings', 1);
        $this->assertDatabaseHas('service_ratings', [
            'rating' => 5,
            'review' => 'Sangat membantu!',
        ]);
    }

    public function test_validation_requires_session_id_and_rating()
    {
        $response = $this->postJson('/api/chatbot/rate', [
            'rating' => 5,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('session_id');

        $response = $this->postJson('/api/chatbot/rate', [
            'session_id' => 'test-session',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('rating');
    }

    public function test_rating_must_be_between_1_and_5()
    {
        $response = $this->postJson('/api/chatbot/rate', [
            'session_id' => 'test-session',
            'rating' => 0,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('rating');

        $response = $this->postJson('/api/chatbot/rate', [
            'session_id' => 'test-session',
            'rating' => 6,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('rating');
    }
}
