<?php

use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\User;
use Filament\Actions\DeleteAction;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertModelMissing;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can retrieve data', function () {
    $user = User::factory()->create();

    livewire(name: EditUser::class, params: [
        'record' => $user->getRouteKey(),
    ])
        ->assertFormSet([
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'password' => null,
        ]);
});

it('can mark password non required', function () {
    $user = User::factory()->create();

    livewire(name: EditUser::class, params: [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'password' => null,
        ])
        ->assertActionExists('save')
        ->call('save')
        ->assertHasNoFormErrors();
});

it('can save', function () {
    $user = User::factory()->create();
    $newUser = User::factory()->make();

    livewire(name: EditUser::class, params: [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'name' => $newUser->name,
            'username' => $newUser->username,
            'email' => $newUser->email,
        ])
        ->assertActionExists('save')
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->refresh())
        ->name->toEqual($newUser->name)
        ->username->toEqual($newUser->username)
        ->email->toEqual($newUser->email);
});

it('can delete a user', function () {
    $user = User::factory()->create();

    livewire(name: EditUser::class, params: [
        'record' => $user->getRouteKey(),
    ])
        ->assertActionExists('delete')
        ->callAction(DeleteAction::class);

    assertModelMissing($user);
});
