<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $query = Location::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('zone', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%")
                    ->orWhere('shelf', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $locations = $query->orderBy('zone')->orderBy('module')->orderBy('shelf')->paginate(15)->withQueryString();

        return view('locations.index', compact('locations'));
    }

    public function store(StoreLocationRequest $request)
    {
        Location::create($request->validated());

        return redirect()->route('locations.index')->with('success', 'Ubicación creada correctamente.');
    }

    public function update(UpdateLocationRequest $request, Location $location)
    {
        $location->update($request->validated());

        return redirect()->route('locations.index')->with('success', 'Ubicación actualizada correctamente.');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Ubicación eliminada correctamente.');
    }
}
