<?php

use App\Filament\Resources\UserResource\Pages\ViewUser;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can view user information', function () {
    $user = User::factory()->create();

    livewire(name: ViewUser::class, params: ['record' => $user->getRouteKey()])
        ->assertSee($user->name)
        ->assertSee($user->email)
        ->assertSee($user->username)
        ->assertSee('LOCAL');
});
