<?php

namespace App\Actions;

use App\Models\Store;

class CanStoreDeliverToCoordsAction
{
    /**
     * Check if a store can deliver to a given coordinate.
     */
    public function execute(Store $store, float $lat, float $lon): bool
    {
        return Store::where('stores.id', $store->id)
            ->join('postcodes', 'stores.postcode_id', '=', 'postcodes.id')
            ->whereRaw('ST_Distance_Sphere(point(postcodes.longitude, postcodes.latitude), point(?, ?)) <= ?', [
                $lon,
                $lat,
                (float) $store->delivery_radius_km * 1000,
            ])
            ->exists();
    }
}
