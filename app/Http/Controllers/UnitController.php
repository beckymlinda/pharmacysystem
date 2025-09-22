<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the units.
     */
    public function show($id)
{
    return response()->json(['message' => 'Not implemented']);
}

    public function index()
    {
        $units = Unit::all();
        return view('units.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit.
     */
    public function create()
    {
        return view('units.create');
    }

    /**
     * Store a newly created unit in storage.
     */
  
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'short_name' => 'required|string|max:50',
    ]);

    $unit = Unit::create($request->only('name', 'short_name'));

    return response()->json([
        'success' => true,
        'message' => 'Unit added successfully!',
        'unit' => $unit
    ]);
}

    /**
     * Show the form for editing the specified unit.
     */
    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }
public function fetchUnits(Request $request)
{
    $q = $request->query('q');
    $units = Unit::where('name', 'like', "%$q%")
                 ->orWhere('short_name', 'like', "%$q%")
                 ->get(['id','name','short_name']);

    return response()->json($units);
}





    /**
     * Update the specified unit in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:units,name,' . $unit->id,
            'short_name' => 'required|string|max:20|unique:units,short_name,' . $unit->id,
        ]);

        $unit->update([
            'name' => $request->name,
            'short_name' => $request->short_name,
        ]);

        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified unit from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}
