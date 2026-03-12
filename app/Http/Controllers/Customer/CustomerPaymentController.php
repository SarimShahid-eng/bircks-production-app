<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerPaymentRequest;
use App\Models\Customer;
use App\Models\CustomerPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerPayment::query();
        if ($request->filled('customer')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->whereHas('customer', function ($customer) use ($search) {
                    $customer->where('name', 'like', "%{$search}%");
                })
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhere('payment_date', 'like', "%{$search}%");
            });
        }
        if ($request->filled('customer_id')) {
            $customer_id = $request->customer_id;

            $query->where(function ($q) use ($customer_id) {

                $q->whereHas('customer', function ($customer) use ($customer_id) {
                    $customer->where('customer_id', $customer_id);
                });

            });
        }
        if ($request->filled('date_range')) {
            $parts = explode(' - ', $request->date_range);
            $startDate = Carbon::createFromFormat('M j, Y', trim($parts[0]))->startOfDay();
            $endDate = Carbon::createFromFormat('M j, Y', trim($parts[1]))->endOfDay();

            $query->whereBetween('payment_date', [$startDate, $endDate]);
        }
        $title = 'Customer management';
        $payments = $query->paginate(10)->withQueryString();
        $customers = Customer::query()
            ->whereRaw('
                        COALESCE((SELECT SUM(total) FROM sales WHERE sales.customer_id = customers.id), 0)
                        >
                        COALESCE((SELECT SUM(amount) FROM customer_payments WHERE customer_payments.customer_id = customers.id), 0)
                    ')
            ->orderBy('name')
            ->get();

        return view('customer.customersPayment.index', compact('title', 'payments', 'customers'));

    }

    /**
     * Display a listing of the resource.
     */
    public function create(Request $request)
    {
        $customers = Customer::query()
            ->whereRaw('
        COALESCE((SELECT SUM(total) FROM sales WHERE sales.customer_id = customers.id), 0)
        >
        COALESCE((SELECT SUM(amount) FROM customer_payments WHERE customer_payments.customer_id = customers.id), 0)
    ')
            ->orderBy('name')
            ->get();
        $title = 'Customer payment';

        return view('customer.customersPayment.create', compact('customers', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerPaymentRequest $request)
    {

        $validated = $request->validated();
        if ($validated);

        CustomerPayment::updateOrCreate(
            ['id' => $validated['updateId'] ?? null],
            $validated
        );
        $updatedOrCreated = array_key_exists('updateId', $validated) ? 'Updated' : 'Created';

        return redirect()->route('customersPayment.index')->with('success', 'Customer Payment '.$updatedOrCreated.' successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerPayment $customerRecord)
    {
        $title = 'Customer Edit';
        $customers = Customer::all();

        return view('customer.customersPayment.create', compact('title', 'customers', 'customerRecord'));
    }
}
