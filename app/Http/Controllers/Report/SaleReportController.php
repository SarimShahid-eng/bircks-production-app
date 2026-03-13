<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaleReportController extends Controller {
    public function sales(Request $request)
{
    $startDate = null;
    $endDate   = null;

    if ($request->filled('date_range') && str_contains($request->date_range, ' - ')) {
        $parts     = explode(' - ', $request->date_range);
        $startDate = Carbon::createFromFormat('M j, Y', trim($parts[0]))->startOfDay();
        $endDate   = Carbon::createFromFormat('M j, Y', trim($parts[1]))->endOfDay();
    }

    $query = \App\Models\Sale::with(['customer', 'productionBatch'])
        ->orderByDesc('sale_date');

    if ($startDate && $endDate) {
        $query->whereBetween('sale_date', [$startDate, $endDate]);
    }

    if ($request->filled('customer_id')) {
        $query->where('customer_id', $request->customer_id);
    }

    $sales = $query->get();

    $totalQuantity = $sales->sum('quantity_sold');
    $totalRevenue  = $sales->sum('total');
    $totalRecords  = $sales->count();

    $customers = \App\Models\Customer::orderBy('name')->get();

    return view('report.sales', [
        'title'         => 'Sale Report',
        'sales'         => $sales,
        'totalQuantity' => $totalQuantity,
        'totalRevenue'  => $totalRevenue,
        'totalRecords'  => $totalRecords,
        'customers'     => $customers,
    ]);
}
}
