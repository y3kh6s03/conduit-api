<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_register(): void
    {
        $response = $this->postJson('api/users', [
            'user' => [
                'username' => 'yosuke',
                'email' => 'yosuke@email',
                'email_verified_at' => now(),
                'password' => Hash::make('yosuke123'),
                'remember_token' => '1234567890',
            ]
        ]);

        $response->assertStatus(200);
    }

    public function test_login(): void
    {
        $user = User::create([
            'username' => 'yosuke',
            'email' => 'yosuke@email',
            'email_verified_at' => now(),
            'password' => Hash::make('yosuke123'),
            'remember_token' => '1234567890',
        ]);
        $response = $this->postJson('api/users/login', [
            'user' => [
                'email' => 'yosuke@email',
                'password' => 'yosuke123'
            ]
        ]);

        $response->assertStatus(200);
    }
}
