<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PostcodeData extends Data
{
    public function __construct(
        public int $id,
        public string $postcode,
        public string $place_name,
        public ?string $county,
        public ?string $country,
        public ?string $type,
        public float $latitude,
        public float $longitude,
        public string $address,
    ) {}

    public static function fromModel(\App\Models\Postcode $postcode): self
    {
        return new self(
            id: $postcode->id,
            postcode: $postcode->postcode,
            place_name: $postcode->place_name,
            county: $postcode->county,
            country: $postcode->country,
            type: $postcode->type,
            latitude: (float) $postcode->latitude,
            longitude: (float) $postcode->longitude,
            address: implode(', ', array_filter([
                $postcode->place_name,
                $postcode->county,
                $postcode->country,
            ])),
        );
    }
}
