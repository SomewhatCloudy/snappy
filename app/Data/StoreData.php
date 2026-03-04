<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class StoreData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public float $delivery_radius_km,
        public PostcodeData $postcode,
        public ?float $distance_km = null,
    ) {}

    public static function fromModel(\App\Models\Store $store): self
    {
        return new self(
            id: $store->id,
            name: $store->name,
            delivery_radius_km: (float) $store->delivery_radius_km,
            postcode: PostcodeData::fromModel($store->postcode),
            distance_km: isset($store->distance) ? (float) ($store->distance / 1000) : null,
        );
    }
}
