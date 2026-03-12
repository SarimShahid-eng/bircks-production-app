<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductionBatchRequest;
use App\Models\Material;
use App\Models\ProductionBatch;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductionBatchController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductionBatch::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('batch_no', 'like', "%{$search}%")
                    ->orWhereRaw("DATE_FORMAT(production_date, '%d %b %Y') LIKE ?", ["%{$search}%"])
                    ->orWhere('total_cost', 'like', "%{$search}%");

            });
        }

        $title = 'Purchase management';
        $batches = $query->latest()->paginate(10)->withQueryString();

        return view('production_batches.index', compact('title', 'batches'));

    }

    /**
     * Display a listing of the resource.
     */
    public function create(Request $request)
    {
        $materials = Material::all();
        $batchNo = $this->generateBatchNo();
        $title = 'Production Manage';

        return view('production_batches.create', compact('materials', 'title', 'batchNo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductionBatchRequest $request)
    {
        $isUpdate = filled($request->updateId);

        if ($isUpdate) {
            $batch = ProductionBatch::findOrFail($request->updateId);

            $batch->update([
                'production_date' => $request->production_date,
                'bricks_produced' => $request->bricks_produced,
                'bricks_wasted' => $request->bricks_wasted,
                'waste_reason' => $request->waste_reason,
                'labor_cost' => $request->labor_cost,
                'fuel_cost' => $request->fuel_cost,
                'other_cost' => $request->other_cost ?? 0,
                'other_cost_note' => $request->other_cost_note,
                'total_material_cost' => $request->total_material_cost,
                'total_expense_cost' => $request->total_expense_cost,
                'total_cost' => $request->total_cost,
            ]);

            // delete old material rows and re-insert fresh
            $batch->productionMaterials()->delete();

        } else {
            $batch = ProductionBatch::create([
                'batch_no' => $this->generateBatchNo(),
                'production_date' => $request->production_date,
                'bricks_produced' => $request->bricks_produced,
                'bricks_wasted' => $request->bricks_wasted,
                'waste_reason' => $request->waste_reason,
                'labor_cost' => $request->labor_cost,
                'fuel_cost' => $request->fuel_cost,
                'other_cost' => $request->other_cost ?? 0,
                'other_cost_note' => $request->other_cost_note,
                'total_material_cost' => $request->total_material_cost,
                'total_expense_cost' => $request->total_expense_cost,
                'total_cost' => $request->total_cost,
            ]);
        }

        // insert material rows
        $materialRows = collect($request->materials)
            ->filter(fn ($m) => ! empty($m['material_id']))
            ->map(fn ($m) => [
                'material_id' => $m['material_id'],
                'quantity_used' => $m['quantity_used'],
                'rate_at_time' => $m['rate_at_time'],
                'total_cost' => $m['total_cost'],
            ]);

        $batch->productionMaterials()->createMany($materialRows);

        $message = $isUpdate ? 'Batch updated successfully.' : 'Batch created successfully.';

        return redirect()->route('production_batches.index')->with('success', $message);
    }

    /**
     * Show the form for editing the specified resource.
     */
public function edit(ProductionBatch $batch)
    {
        $materials = Material::all();
        $batchNo=$batch->batch_no;
        $title = 'ProductionBatch Edit';

        return view('production_batches.create', compact('title','batchNo', 'batch', 'materials'));
    }

    private function generateBatchNo()
    {
        $monthCount = ProductionBatch::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count() + 1;
        // yearmonth+current batch of month eg:9=8th batch of march
        $batchNo = 'PB-'.now()->format('ym').'-'.str_pad($monthCount, 3, '0', STR_PAD_LEFT);

        return $batchNo;
    }
}
