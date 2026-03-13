<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Material;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $title = 'Purchase management';
        $purchases = $query->latest()->paginate(10)->withQueryString();

        return view('purchase.index', compact('title', 'purchases'));

    }

    /**
     * Display a listing of the resource.
     */
    public function create(Request $request)
    {
        $materials = Material::all();
        $suppliers = Supplier::all();
        $title = 'Purchase Manage';

        return view('purchase.create', compact('materials', 'suppliers', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseRequest $request)
    {

        $validated = $request->validated();

        Purchase::updateOrCreate(
            ['id' => $validated['updateId'] ?? null],
            $validated
        );
        $updatedOrCreated = array_key_exists('updateId', $validated) ? 'Updated' : 'Created';

        return redirect()->route('purchases.index')->with('success', 'Purchase '.$updatedOrCreated.' successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $materials = Material::all();
        $suppliers = Supplier::all();
        $title = 'Purchase Edit';

        return view('purchase.create', compact('title', 'purchase', 'suppliers', 'materials'));
    }

    // public function stockInfo(Purchase $purchase)
    // {
    //     $material = $purchase->material;

    //     $totalPurchased = Purchase::where('material_id', $material->id)->sum('quantity') + Material::find($material->id)->value('stock_quantity');
    //     $totalUsed = ProductionMaterial::where('material_id', $material->id)->sum('quantity_used');
    //     $currentStock = $totalPurchased - $totalUsed;

    //     return response()->json([
    //         'material' => $material->name,
    //         'unit' => $material->unit,
    //         'total_purchased' => number_format($totalPurchased, 2),
    //         'total_used' => number_format($totalUsed, 2),
    //         'current_stock' => number_format($currentStock, 2),
    //     ]);
    // }
}
