<?php

namespace App\Http\Controllers\Api;

use App\Actions\CanStoreDeliverToCoordsAction;
use App\Actions\GetNearbyStoresAction;
use App\Data\PostcodeData;
use App\Data\StoreData;
use App\Http\Controllers\Controller;
use App\Http\Requests\CanDeliverRequest;
use App\Http\Requests\NearbyCoordsRequest;
use App\Http\Requests\SearchLocationsRequest;
use App\Http\Requests\StoreCreateRequest;
use App\Models\Postcode;
use App\Models\Store;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    public function store(StoreCreateRequest $request): JsonResponse
    {
        $store = Store::create($request->validated());

        return response()->json(StoreData::fromModel($store->load('postcode')), 201);
    }

    public function nearbyCoords(NearbyCoordsRequest $request, GetNearbyStoresAction $getNearbyStoresAction): JsonResponse
    {
        $stores = $getNearbyStoresAction->execute(
            (float) $request->lat,
            (float) $request->lon,
            (float) ($request->radius ?? 50)
        );

        return response()->json(StoreData::collect($stores->load('postcode')));
    }

    public function searchLocations(SearchLocationsRequest $request): JsonResponse
    {
        $query = $request->query('query');

        $locations = Postcode::search($query)
            ->limit(10)
            ->get();

        return response()->json(PostcodeData::collect($locations));
    }

    public function canDeliver(CanDeliverRequest $request, Store $store, CanStoreDeliverToCoordsAction $canStoreDeliverToCoordsAction): JsonResponse
    {
        $canDeliver = $canStoreDeliverToCoordsAction->execute(
            $store,
            (float) $request->lat,
            (float) $request->lon
        );

        return response()->json(['can_deliver' => $canDeliver]);
    }
}
