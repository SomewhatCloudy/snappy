<?php

use App\Data\PostcodeData;
use App\Models\Postcode;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('PostcodeData can be instantiated with all required fields', function () {
    $data = new PostcodeData(
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

    expect($data->id)->toBe(1);
    expect($data->postcode)->toBe('SW1A 1AA');
    expect($data->place_name)->toBe('London');
    expect($data->county)->toBe('Greater London');
    expect($data->country)->toBe('United Kingdom');
    expect($data->type)->toBe('Postcode');
    expect($data->latitude)->toBe(51.5033);
    expect($data->longitude)->toBe(-0.1195);
    expect($data->address)->toBe('London, Greater London, United Kingdom');
});

test('PostcodeData can be created from a model', function () {
    $postcode = Postcode::factory()->create([
        'postcode' => 'SW1A 1AA',
        'place_name' => 'London',
        'county' => 'Greater London',
        'country' => 'United Kingdom',
        'type' => 'Postcode',
        'latitude' => 51.5033,
        'longitude' => -0.1195,
    ]);

    $data = PostcodeData::fromModel($postcode);

    expect($data->id)->toBe($postcode->id);
    expect($data->postcode)->toBe($postcode->postcode);
    expect($data->place_name)->toBe($postcode->place_name);
    expect($data->county)->toBe($postcode->county);
    expect($data->country)->toBe($postcode->country);
    expect($data->type)->toBe($postcode->type);
    expect($data->latitude)->toBe(51.5033);
    expect($data->longitude)->toBe(-0.1195);
    expect($data->address)->toBe('London, Greater London, United Kingdom');
});

test('PostcodeData address handles missing fields', function () {
    $postcode = Postcode::factory()->create([
        'place_name' => 'London',
        'county' => null,
        'country' => 'United Kingdom',
    ]);

    $data = PostcodeData::fromModel($postcode);

    expect($data->address)->toBe('London, United Kingdom');
});
