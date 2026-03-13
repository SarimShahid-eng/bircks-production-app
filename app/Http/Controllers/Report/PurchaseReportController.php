<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseReportController extends Controller
{
    public function purchases(Request $request)
    {
        $startDate = null;
        $endDate = null;

        if ($request->filled('date_range') && str_contains($request->date_range, ' - ')) {
            $parts = explode(' - ', $request->date_range);
            $startDate = Carbon::createFromFormat('M j, Y', trim($parts[0]))->startOfDay();
            $endDate = Carbon::createFromFormat('M j, Y', trim($parts[1]))->endOfDay();
        }

        $query = \App\Models\Purchase::with(['supplier', 'material'])
            ->orderByDesc('purchase_date');

        if ($startDate && $endDate) {
            $query->whereBetween('purchase_date', [$startDate, $endDate]);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('material_id')) {
            $query->where('material_id', $request->material_id);
        }

        $purchases = $query->get();

        $totalQuantity = $purchases->sum('quantity');
        $totalAmount = $purchases->sum('total');
        $totalRecords = $purchases->count();

        $suppliers = \App\Models\Supplier::orderBy('name')->get();
        $materials = \App\Models\Material::orderBy('name')->get();

        return view('report.purchases', [
            'title' => 'Purchase Report',
            'purchases' => $purchases,
            'totalQuantity' => $totalQuantity,
            'totalAmount' => $totalAmount,
            'totalRecords' => $totalRecords,
            'suppliers' => $suppliers,
            'materials' => $materials,
        ]);
    }
}
