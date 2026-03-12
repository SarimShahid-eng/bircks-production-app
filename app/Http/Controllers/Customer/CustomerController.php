<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $title = 'Customer management';
        $customers = $query->paginate(10)->withQueryString();

        return view('customer.index', compact('title', 'customers'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Customer create';

        return view('customer.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $validated = $request->validated();

        Customer::updateOrCreate(
            ['id' => $validated['updateId'] ?? null],
            $validated
        );
        $updatedOrCreated=$validated['updateId']?"Updated":"Created";

        return redirect()->route('customers.index')->with('success', 'Customer '.$updatedOrCreated.' successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $title="Customer Edit";
        return view('customer.create', compact('title', 'customer'));
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, Supplier $supplier)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Supplier $supplier)
    // {
    //     //
    // }
}
