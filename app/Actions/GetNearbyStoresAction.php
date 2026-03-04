<?php

namespace App\Actions;

use App\Models\Store;
use Illuminate\Database\Eloquent\Collection;

class GetNearbyStoresAction
{
    /**
     * Get stores within a given radius of a coordinate.
     *
     * @param  float  $lat
     * @param  float  $lon
     * @param  float  $radius In kilometers.
     * @return Collection<int, Store>
     */
    public function execute(float $lat, float $lon, float $radius = 50.0): Collection
    {
        return Store::select('stores.*')
            ->selectRaw('ST_Distance_Sphere(point(postcodes.longitude, postcodes.latitude), point(?, ?)) as distance', [
                $lon,
                $lat,
            ])
            ->join('postcodes', 'stores.postcode_id', '=', 'postcodes.id')
            ->whereRaw('ST_Distance_Sphere(point(postcodes.longitude, postcodes.latitude), point(?, ?)) <= ?', [
                $lon,
                $lat,
                $radius * 1000,
            ])
            ->orderBy('distance')
            ->get();
    }
}
