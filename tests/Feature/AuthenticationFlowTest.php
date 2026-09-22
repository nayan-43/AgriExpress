<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_register_are_separate_pages(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Welcome Back')
            ->assertSee('Create one')
            ->assertDontSee('Phone Number');

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Create an Account')
            ->assertSee('Phone Number')
            ->assertSee('register-password-confirmation')
            ->assertDontSee('Sign in to your account and continue shopping');
    }

    public function test_registration_stores_phone_and_returns_to_login_with_message(): void
    {
        $response = $this->post(route('store'), [
            'name' => 'Test Customer',
            'phone' => '+91 98765 43210',
            'email' => 'customer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'))
            ->assertSessionHas('status', 'Registration successful. Please sign in to continue.');
        $this->assertGuest('web');
        $this->assertDatabaseHas('users', [
            'email' => 'customer@example.com',
            'phone' => '+91 98765 43210',
        ]);
    }

    public function test_login_uses_previous_url_and_sets_success_message(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.com',
            'password' => 'password123',
            'role' => 'customer',
            'status' => true,
        ]);

        $this->get(route('cart'))->assertRedirect(route('login'));

        $response = $this->post(route('authenticate'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('cart'))
            ->assertSessionHas('status', 'Welcome back! You are now signed in.');
        $this->assertAuthenticatedAs($user, 'web');
    }

    public function test_registration_requires_phone_and_password_confirmation(): void
    {
        $response = $this->from(route('register'))->post(route('store'), [
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertRedirect(route('register'))
            ->assertSessionHasErrors(['phone', 'password']);
    }

    public function test_invalid_login_returns_to_login_with_entered_email(): void
    {
        $response = $this->from(route('login'))->post(route('authenticate'), [
            'email' => 'wrong@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'))
            ->assertSessionHasErrors('email')
            ->assertSessionHasInput('email', 'wrong@example.com');
    }

    public function test_cart_does_not_apply_a_coupon_by_default(): void
    {
        $user = User::factory()->create(['role' => 'customer', 'status' => true]);

        $this->actingAs($user, 'web')
            ->get(route('cart'))
            ->assertOk()
            ->assertSee('Enter coupon code')
            ->assertDontSee('WELCOME10')
            ->assertSee('$0.00');
    }
}
