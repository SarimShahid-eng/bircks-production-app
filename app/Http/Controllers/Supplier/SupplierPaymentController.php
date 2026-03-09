<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierPaymentRequest;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;

class SupplierPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplierPayment::query();
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->whereHas('supplier', function ($supplier) use ($search) {
                    $supplier->where('name', 'like', "%{$search}%");
                })
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhere('payment_date', 'like', "%{$search}%");
            });
        }

        $title = 'Supplier management';
        $payments = $query->paginate(10)->withQueryString();

        return view('supplier.suppliersPayment.index', compact('title', 'payments'));

    }

    /**
     * Display a listing of the resource.
     */
    public function create(Request $request)
    {
        $suppliers = Supplier::all();
        $title = 'Supplier payment';

        return view('supplier.suppliersPayment.create', compact('suppliers', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $title = 'Supplier create';

    //     return view('supplier.create', compact('title'));
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierPaymentRequest $request)
    {

        $validated = $request->validated();
        if ($validated);

        SupplierPayment::updateOrCreate(
            ['id' => $validated['updateId'] ?? null],
            $validated
        );
        $updatedOrCreated = array_key_exists('updateId', $validated) ? 'Updated' : 'Created';

        return redirect()->route('suppliersPayment.index')->with('success', 'Supplier Payment ' .$updatedOrCreated.' successfully.');
    }

    /**
     * Display the specified resource.
     */
    // public function show(Supplier $supplier)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupplierPayment $supplierRecord)
    {
        $title = 'Supplier Edit';
        $suppliers = Supplier::all();

        return view('supplier.suppliersPayment.create', compact('title', 'suppliers', 'supplierRecord'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }
}
