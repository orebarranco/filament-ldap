<?php

use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertModelMissing;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can list users', function () {
    $users = User::factory()->count(5)->create();

    livewire(name: ListUsers::class)
        ->assertCanSeeTableRecords($users);
});

it('can get user type', function () {
    $users = User::factory()->count(5)->create();

    $user = $users->first();

    livewire(ListUsers::class)
        ->assertTableColumnStateSet(name: 'type', value: 'LOCAL', record: $user)
        ->assertTableColumnStateNotSet(name: 'type', value: 'LDAP', record: $user);
});

it('can search column', function (string $column) {
    $records = User::factory()->count(5)->create();

    $value = $records->first()->$column;

    livewire(ListUsers::class)
        ->searchTable($value)
        ->assertCanSeeTableRecords($records->where("$column", $value))
        ->assertCanNotSeeTableRecords($records->where("$column", '!=', $value));
})->with(['name', 'username', 'email']);

it('can edit users', function () {
    $user = User::factory()->create();

    livewire(ListUsers::class)
        ->callTableAction(EditAction::class, $user, data: [
            'name' => $name = fake()->name(),
        ])
        ->assertHasNoTableActionErrors();

    expect($user->refresh())
        ->name->toEqual($name);
});

it('can view users information', function () {
    $user = User::factory()->create();

    livewire(ListUsers::class)
        ->callTableAction(ViewAction::class, $user, data: [
            'record' => $user,
        ])
        ->assertHasNoTableActionErrors();
});

it('can bulk delete records', function () {
    $users = User::factory()->count(5)->create();

    livewire(ListUsers::class)
        ->assertTableBulkActionExists('delete')
        ->callTableBulkAction(DeleteBulkAction::class, $users);

    foreach ($users as $record) {
        assertModelMissing($record);
    }
});
