<?php

namespace App\Http\Controllers\Admin;

use App\Data\StoreData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreateRequest;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class StoreManagementController extends Controller
{
    public function index(): Response
    {
        $stores = Store::with('postcode')->get();

        return Inertia::render('Admin/Stores/Index', [
            'stores' => StoreData::collect($stores),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Stores/Create');
    }

    public function store(StoreCreateRequest $request): RedirectResponse
    {
        Store::create($request->validated());

        return redirect()->route('admin.stores.index')->with('success', 'Store created successfully');
    }

    public function edit(Store $store): Response
    {
        return Inertia::render('Admin/Stores/Edit', [
            'store' => StoreData::fromModel($store->load('postcode')),
        ]);
    }

    public function update(StoreCreateRequest $request, Store $store): RedirectResponse
    {
        $store->update($request->validated());

        return redirect()->route('admin.stores.index')->with('success', 'Store updated successfully');
    }

    public function destroy(Store $store): RedirectResponse
    {
        $store->delete();

        return redirect()->route('admin.stores.index')->with('success', 'Store deleted successfully');
    }
}
