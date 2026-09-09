<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    #[Test]
    public function register_page_loads_for_guest(): void
    {
        $this->get(route('register'))->assertOk();
    }

    #[Test]
    public function guest_can_register_with_valid_data(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'secret-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Registration successful! Please login.');
        $this->assertDatabaseHas('users', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);
    }

    #[Test]
    public function registered_password_is_hashed_not_stored_plain(): void
    {
        $this->post(route('register'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'secret-password',
        ]);

        $user = User::where('email', 'jane@example.com')->firstOrFail();

        $this->assertNotSame('secret-password', $user->password);
        $this->assertTrue(Hash::check('secret-password', $user->password));
    }

    #[Test]
    public function registration_requires_name(): void
    {
        $response = $this->post(route('register'), [
            'name' => '',
            'email' => 'jane@example.com',
            'password' => 'secret-password',
        ]);

        $response->assertInvalid('name');
        $this->assertDatabaseCount('users', 0);
    }

    #[Test]
    public function registration_requires_valid_email(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Jane Doe',
            'email' => 'not-an-email',
            'password' => 'secret-password',
        ]);

        $response->assertInvalid('email');
        $this->assertDatabaseCount('users', 0);
    }

    #[Test]
    public function registration_requires_unique_email(): void
    {
        User::factory()->create(['email' => 'jane@example.com']);

        $response = $this->post(route('register'), [
            'name' => 'Another Jane',
            'email' => 'jane@example.com',
            'password' => 'secret-password',
        ]);

        $response->assertInvalid('email');
        $this->assertDatabaseCount('users', 1);
    }

    #[Test]
    public function registration_requires_password(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);

        $response->assertInvalid('password');
        $this->assertDatabaseCount('users', 0);
    }
}
