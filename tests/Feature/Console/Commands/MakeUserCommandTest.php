<?php

use App\Models\User;

use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseHas;

it('creates a user with valid options', function () {
    $user = User::factory()->make();

    artisan(command: 'make:filament-user')
        ->expectsQuestion(question: 'Name', answer: $user->name)
        ->expectsQuestion(question: 'Email address', answer: $user->email)
        ->expectsQuestion(question: 'Username', answer: $user->username)
        ->expectsQuestion(question: 'Password', answer: 'password')
        ->assertSuccessful()
        ->assertExitCode(0);

    assertDatabaseHas(User::class, [
        'name' => $user->name,
        'email' => $user->email,
        'username' => $user->username,
    ]);
});

it('validates required Name', function () {
    artisan(command: 'make:filament-user')
        ->expectsQuestion(question: 'Name', answer: '')
        ->assertFailed()
        ->assertExitCode(1);
});

it('validates unique email', function () {
    $user = User::factory()->create();
    $newUser = User::factory()->make();

    artisan(command: 'make:filament-user')
        ->expectsQuestion(question: 'Name', answer: $newUser->name)
        ->expectsQuestion(question: 'Email address', answer: $user->email)
        ->assertFailed()
        ->assertExitCode(1);
});

it('validates valid email', function () {
    $newUser = User::factory()->make();

    artisan(command: 'make:filament-user')
        ->expectsQuestion(question: 'Name', answer: $newUser->name)
        ->expectsQuestion(question: 'Email address', answer: 'invalid-email')
        ->assertFailed()
        ->assertExitCode(1);
});

it('validates unique username', function () {
    $user = User::factory()->create();
    $newUser = User::factory()->make();

    artisan(command: 'make:filament-user')
        ->expectsQuestion(question: 'Name', answer: $newUser->name)
        ->expectsQuestion(question: 'Email address', answer: $newUser->email)
        ->expectsQuestion(question: 'Username', answer: $user->username)
        ->assertFailed()
        ->assertExitCode(1);
});
