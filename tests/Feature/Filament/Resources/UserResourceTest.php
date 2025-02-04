<?php

use App\Filament\Resources\UserResource;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can render List page', function () {
    get(UserResource::getUrl())
        ->assertSuccessful();
});

it('can render Create page', function () {
    get(UserResource::getUrl(name: 'create'))
        ->assertSuccessful();
});

it('can render Edit page', function () {
    get(UserResource::getUrl(
        name: 'edit',
        parameters: ['record' => User::factory()->create()]
    ))
        ->assertSuccessful();
});

it('can render View page', function () {
    get(UserResource::getUrl(
        name: 'view',
        parameters: ['record' => User::factory()->create()]
    ))
        ->assertSuccessful();
});
