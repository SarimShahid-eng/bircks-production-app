<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaterialRequest;
use App\Models\Material;
use App\Models\ProductionMaterial;
use App\Models\Purchase;
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

    public function stockInfo(Material $material)
    {
        $totalPurchased = Purchase::where('material_id', $material->id)->sum('quantity') + Material::find($material->id)->value('stock_quantity');
        $totalUsed = ProductionMaterial::where('material_id', $material->id)->sum('quantity_used');
        $currentStock = $totalPurchased - $totalUsed;

        return response()->json([
            'material' => $material->name,
            'unit' => $material->unit,
            'total_purchased' => number_format($totalPurchased, 2),
            'total_used' => number_format($totalUsed, 2),
            'current_stock' => number_format($currentStock, 2),
        ]);
    }
}
