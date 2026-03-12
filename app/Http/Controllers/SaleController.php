<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\Customer;
use App\Models\ProductionBatch;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'productionBatch'])
            ->orderByDesc('sale_date');

        if ($request->filled('search')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $sales = $query->paginate(15);

        return view('sale.index', [
            'title'  => 'Sales',
            'sales'  => $sales,
        ]);
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $batches   = ProductionBatch::orderByDesc('production_date')->get();

        return view('sale.create', [
            'title'     => 'Add Sale',
            'customers' => $customers,
            'batches'   => $batches,
        ]);
    }

    public function store(SaleRequest $request)
    {
        $isUpdate = filled($request->updateId);

        if ($isUpdate) {
            $sale = Sale::findOrFail($request->updateId);
            $sale->update([
                'customer_id'         => $request->customer_id,
                'production_batch_id' => $request->production_batch_id,
                'quantity_sold'       => $request->quantity_sold,
                'rate_per_brick'      => $request->rate_per_brick,
                'total'               => $request->total,
                'sale_date'           => $request->sale_date,
            ]);
            $message = 'Sale updated successfully.';
        } else {
            Sale::create([
                'customer_id'         => $request->customer_id,
                'production_batch_id' => $request->production_batch_id,
                'quantity_sold'       => $request->quantity_sold,
                'rate_per_brick'      => $request->rate_per_brick,
                'total'               => $request->total,
                'sale_date'           => $request->sale_date,
            ]);
            $message = 'Sale recorded successfully.';
        }

        return redirect()->route('sales.index')->with('success', $message);
    }

    public function edit(Sale $sale)
    {
        $customers = Customer::orderBy('name')->get();
        $batches   = ProductionBatch::orderByDesc('production_date')->get();

        return view('sale.create', [
            'title'     => 'Edit Sale',
            'sale'      => $sale,
            'customers' => $customers,
            'batches'   => $batches,
        ]);
    }
}
