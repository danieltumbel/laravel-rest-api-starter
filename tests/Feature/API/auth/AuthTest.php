<?php

namespace Tests\Feature\API\auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    // 1. PERBAIKAN: Wajib aktifkan RefreshDatabase agar database bersih setiap kali fungsi tes berjalan
    use RefreshDatabase; 

    /**
     * Pengujian fitur login user.
     */
    public function test_user_dapat_login(): void
    {
        // Pembuatan data user tiruan untuk login
        $user = User::factory()->create([
            'email' => 'login-test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Melakukan request POST ke API Login
        $response = $this->postJson('/api/auth/login', [
            'email' => 'login-test@example.com',
            'password' => 'password123',
        ]);

        // Assertions (Penegasan hasil)
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ],
        ]);   
    }

    /**
     * Pengujian fitur pendaftaran/register user baru.
     */
    public function test_user_dapat_register(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'register-test@example.com', // Menggunakan email berbeda agar lebih aman
            'password' => 'password123',
        ];

        // Melakukan request POST ke API Register
        $response = $this->postJson('/api/auth/register', $userData);

        // Memastikan status response adalah 201 Created
        $response->assertCreated();

        // 2. PERBAIKAN: Ubah nama tabel dari 'user' menjadi 'users' (ditambah s)
        $this->assertDatabaseHas('users', [
            'email' => $userData['email']
        ]);
    }

    public function test_user_dapat_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200);
    }
}
