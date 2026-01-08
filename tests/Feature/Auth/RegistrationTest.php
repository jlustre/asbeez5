<?php

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertStatus(200);
});

test('new users can register with sponsor', function () {
    $this->withoutMiddleware(ValidateCsrfToken::class);
    $sponsor = User::factory()->create();

    $response = $this->post(route('register.store'), [
        'username' => 'newuser',
        'sponsor_id' => $sponsor->id,
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();

    $user = User::where('email', 'test@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->sponsor_id)->toBe($sponsor->id);
});

test('sponsor persists in session across navigation', function () {
    $this->withoutMiddleware(ValidateCsrfToken::class);
    $sponsor = User::factory()->create();

    // Hit register page with ?sp= to set session
    $this->get(route('register', ['sp' => $sponsor->id]))->assertStatus(200);

    // Navigate to register page and ensure sponsor username is displayed
    $register = $this->get(route('register'));
    $register->assertStatus(200);
    $register->assertSee($sponsor->username, false);

    // Register without explicitly sending sponsor_id; server should use session value
    $response = $this->post(route('register.store'), [
        'username' => 'anotheruser',
        'email' => 'another@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $user = User::where('email', 'another@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->sponsor_id)->toBe($sponsor->id);
});

test('invalid sponsor link shows error message', function () {
    // 10-char invalid hash that won't decode
    $invalid = 'invalid0000';

    $response = $this->get(route('register', ['sp' => $invalid]));
    $response->assertStatus(200);
    $response->assertSeeText('Invalid referral link');
    // Submit button should be disabled
    $response->assertSee('data-test="register-user-button"', false);
    $response->assertSee('disabled', false);
});