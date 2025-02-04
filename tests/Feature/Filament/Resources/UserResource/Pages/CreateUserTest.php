<?php

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Models\User;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can create', function () {
    $user = User::factory()->make();

    livewire(name: CreateUser::class)
        ->fillForm([
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertActionExists('create')
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(User::class, [
        'name' => $user->name,
        'username' => $user->username,
        'email' => $user->email,
    ]);
});

it('can validate required', function (string $column) {
    livewire(name: CreateUser::class)
        ->fillForm([
            $column => null,
        ])
        ->assertActionExists('create')
        ->call('create')
        ->assertHasFormErrors([$column => 'required']);
})
    ->with(['name', 'username', 'email', 'password']);

it('can validate unique', function (string $column) {
    $user = User::factory()->create();

    livewire(name: CreateUser::class)
        ->fillForm([
            $column => $user->$column,
        ])
        ->assertActionExists('create')
        ->call('create')
        ->assertHasFormErrors([$column => 'unique:users']);
})
    ->with(['username', 'email']);

it('can validate input max length', function ($column) {
    livewire(name: CreateUser::class)
        ->fillForm([
            $column => Str::random(256),
        ])
        ->assertActionExists('create')
        ->call('create')
        ->assertHasFormErrors([$column => 'max:255']);
})
    ->with(['name', 'username', 'email']);
