<?php

use App\Models\Postcode;
use App\Models\Store;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
});

it('unauthenticated users cannot access admin stores index', function () {
    $this->get(route('admin.stores.index'))
        ->assertRedirect(route('login'));
});

it('can list stores in admin', function () {
    $stores = Store::factory()->count(3)->create();

    $this->actingAs($this->user)
        ->get(route('admin.stores.index'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Stores/Index')
            ->has('stores', 3)
        );
});

it('can show create store page', function () {
    $this->actingAs($this->user)
        ->get(route('admin.stores.create'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Stores/Create')
        );
})->todo();

it('can create a new store', function () {
    $postcode = Postcode::factory()->create();

    $this->actingAs($this->user)
        ->post(route('admin.stores.store'), [
            'name' => 'New Store',
            'postcode_id' => $postcode->id,
            'delivery_radius_km' => 15,
        ])
        ->assertRedirect(route('admin.stores.index'));

    $this->assertDatabaseHas('stores', [
        'name' => 'New Store',
        'postcode_id' => $postcode->id,
        'delivery_radius_km' => 15,
    ]);
});

it('can show edit store page', function () {
    $store = Store::factory()->create();

    $this->actingAs($this->user)
        ->get(route('admin.stores.edit', $store))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Stores/Edit')
            ->has('store', fn (Assert $page) => $page
                ->where('id', $store->id)
                ->where('name', $store->name)
                ->where('delivery_radius_km', $store->delivery_radius_km)
                ->where('postcode_id', $store->postcode_id)
                ->etc()
            )
        );
})->todo();

it('can update a store', function () {
    $store = Store::factory()->create();
    $newPostcode = Postcode::factory()->create();

    $this->actingAs($this->user)
        ->put(route('admin.stores.update', $store), [
            'name' => 'Updated Store Name',
            'postcode_id' => $newPostcode->id,
            'delivery_radius_km' => 20,
        ])
        ->assertRedirect(route('admin.stores.index'));

    $this->assertDatabaseHas('stores', [
        'id' => $store->id,
        'name' => 'Updated Store Name',
        'postcode_id' => $newPostcode->id,
        'delivery_radius_km' => 20,
    ]);
});

it('can delete a store', function () {
    $store = Store::factory()->create();

    $this->actingAs($this->user)
        ->delete(route('admin.stores.destroy', $store))
        ->assertRedirect(route('admin.stores.index'));

    $this->assertDatabaseMissing('stores', ['id' => $store->id]);
});
