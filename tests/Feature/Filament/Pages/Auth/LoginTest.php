<?php

use App\Filament\Pages\Auth\Login;
use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

it('can render Login page', function () {
    get(route('filament.admin.auth.login'))
        ->assertSuccessful();
});

it('can validate required', function ($column) {
    livewire(name: Login::class)
        ->fillForm([
            $column => null,
        ])
        ->assertActionExists('authenticate')
        ->call('authenticate')
        ->assertHasFormErrors([$column => 'required']);
})
    ->with(['username', 'password']);

it('authenticates registered user with username', function () {
    $user = User::factory()->create();

    livewire(name: Login::class)
        ->fillForm([
            'username' => $user->username,
            'password' => 'password',
        ])
        ->assertActionExists('authenticate')
        ->call('authenticate')
        ->assertHasNoFormErrors();
});

it('authenticates registered user with email', function () {
    $user = User::factory()->create();

    livewire(name: Login::class)
        ->fillForm([
            'username' => $user->email,
            'password' => 'password',
        ])
        ->assertActionExists('authenticate')
        ->call('authenticate')
        ->assertHasNoFormErrors();
});

it('can not authenticates unregistered user', function () {
    livewire(name: Login::class)
        ->fillForm([
            'username' => 'unregistered-user',
            'password' => 'password',
        ])
        ->assertActionExists('authenticate')
        ->call('authenticate')
        ->assertHasFormErrors(['username']);
});
