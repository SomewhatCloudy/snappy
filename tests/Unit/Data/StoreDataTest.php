<?php

use App\Data\PostcodeData;
use App\Data\StoreData;
use App\Models\Postcode;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('StoreData can be instantiated with all required fields', function () {
    $postcodeData = new PostcodeData(
        id: 1,
        postcode: 'SW1A 1AA',
        place_name: 'London',
        county: 'Greater London',
        country: 'United Kingdom',
        type: 'Postcode',
        latitude: 51.5033,
        longitude: -0.1195,
        address: 'London, Greater London, United Kingdom'
    );

    $data = new StoreData(
        id: 1,
        name: 'Snappy Snaps London',
        delivery_radius_km: 10.5,
        postcode: $postcodeData,
        distance_km: 5.2
    );

    expect($data->id)->toBe(1);
    expect($data->name)->toBe('Snappy Snaps London');
    expect($data->delivery_radius_km)->toBe(10.5);
    expect($data->postcode)->toBe($postcodeData);
    expect($data->distance_km)->toBe(5.2);
});

test('StoreData can be created from a model', function () {
    $postcode = Postcode::factory()->create();
    $store = Store::factory()->create([
        'name' => 'Snappy Snaps London',
        'delivery_radius_km' => 10.5,
        'postcode_id' => $postcode->id,
    ]);

    $data = StoreData::fromModel($store);

    expect($data->id)->toBe($store->id);
    expect($data->name)->toBe('Snappy Snaps London');
    expect($data->delivery_radius_km)->toBe(10.5);
    expect($data->postcode->id)->toBe($postcode->id);
    expect($data->distance_km)->toBeNull();
});

test('StoreData can be created from a model with distance', function () {
    $postcode = Postcode::factory()->create();
    $store = Store::factory()->create([
        'postcode_id' => $postcode->id,
    ]);

    // Simulating the distance attribute added by database query (in meters)
    $store->distance = 5200;

    $data = StoreData::fromModel($store);

    expect($data->distance_km)->toBe(5.2);
});
