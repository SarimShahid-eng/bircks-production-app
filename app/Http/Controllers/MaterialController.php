<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaterialRequest;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::query();
        if ($request->filled('search')) {
            $search = $request->search;

            // $query->where(function ($q) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('unit', 'like', "%{$search}%")
                        ->orWhere('stock_quantity', 'like', "%{$search}%");
                });
        }

        $title = 'Material management';
        $materials = $query->paginate(10)->withQueryString();

        return view('material.index', compact('title', 'materials'));

    }

    /**
     * Display a listing of the resource.
     */
    public function create(Request $request)
    {
        $suppliers = Material::all();
        $title = 'Material add';

        return view('material.create', compact('suppliers', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MaterialRequest $request)
    {

        $validated = $request->validated();

        Material::updateOrCreate(
            ['id' => $validated['updateId'] ?? null],
            $validated
        );
        $updatedOrCreated = array_key_exists('updateId', $validated) ? 'Updated' : 'Created';

        return redirect()->route('materials.index')->with('success', 'Material '.$updatedOrCreated.' successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        $title = 'Material Edit';

        return view('material.create', compact('title', 'material'));
    }
}
