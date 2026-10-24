<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('verify-otp', absolute: false));
        $response->assertSessionHas('pending_user_id');
        $this->assertDatabaseHas('user_otps', [
            'user_id' => User::where('email', 'test@example.com')->value('id'),
            'used' => false,
        ]);
    }

    public function test_otp_verification_screen_displays_pending_database_otp(): void
    {
        $user = User::factory()->create();

        DB::table('user_otps')->insert([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->addMinutes(5),
            'used' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withSession([
            'pending_user_id' => $user->id,
            'otp_expiry' => now()->addMinutes(5),
        ])->get('/verify-otp');

        $response->assertOk();
        $response->assertSee('123456');
    }

    public function test_otp_verification_screen_shows_message_if_otp_lookup_fails(): void
    {
        DB::shouldReceive('table')
            ->once()
            ->with('user_otps')
            ->andThrow(new RuntimeException('Database unavailable'));
        Log::shouldReceive('error')
            ->once()
            ->with('Unable to load pending OTP.', Mockery::type('array'));

        $response = $this->withSession(['pending_user_id' => 1])
            ->get('/verify-otp');

        $response->assertOk();
        $response->assertSee('We could not load your verification code right now.');
    }

    public function test_valid_database_otp_authenticates_user_and_marks_code_as_used(): void
    {
        $user = User::factory()->create();

        DB::table('user_otps')->insert([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->addMinutes(5),
            'used' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withSession([
            'pending_user_id' => $user->id,
            'otp_expiry' => now()->addMinutes(5),
        ])->post('/verify-otp', ['otp' => '123456']);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertDatabaseHas('user_otps', [
            'user_id' => $user->id,
            'code' => '123456',
            'used' => true,
        ]);
    }
}
