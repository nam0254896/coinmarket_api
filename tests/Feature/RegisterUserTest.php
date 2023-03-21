<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test registration with valid data.
     *
     * @return void
     */
    public function testRegisterWithValidData()
    {
        $userData = [
            'username' => $this->faker->userName,
            'password' => $this->faker->password,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
                 ->assertJson(['message' => 'User registered successfully.']);
    }

    /**
     * Test registration with missing fields.
     *
     * @return void
     */
    public function testRegisterWithMissingFields()
    {
        $userData = [];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
                 ->assertJson(['errors' => ['username' => ['The username field is required.']]]);

        $userData['username'] = $this->faker->userName;

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
                 ->assertJson(['errors' => ['password' => ['The password field is required.']]]);

        $userData['password'] = $this->faker->password;

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
                 ->assertJson(['errors' => ['email' => ['The email field is required.']]]);

        $userData['email'] = $this->faker->email;

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
                 ->assertJson(['errors' => ['phone' => ['The phone field is required.']]]);    
    }
}

