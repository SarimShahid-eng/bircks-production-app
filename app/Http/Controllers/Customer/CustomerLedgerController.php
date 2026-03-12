<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerLedgerController extends Controller
{
    public function ledger(Request $request)
    {
        // ── GLOBAL TOTALS ──
        $allCustomers = Customer::query()
            ->withSum('sales', 'total')
            ->withSum('customerPayments', 'amount')
            ->get()
            ->map(function ($c) {
                $totalSales = (float) ($c->sales_sum_total ?? 0);
                $totalPaid = (float) ($c->customer_payments_sum_amount ?? 0);

                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'total_sales' => $totalSales,
                    'total_paid' => $totalPaid,
                    'balance' => $totalSales - $totalPaid,
                ];
            });

        $globalTotals = [
            'total_sales' => $allCustomers->sum('total_sales'),
            'total_paid' => $allCustomers->sum('total_paid'),
            'balance' => $allCustomers->sum('balance'),
            'count' => $allCustomers->count(),
        ];

        // ── PAGINATED + FILTERED TABLE ──
        $query = Customer::query()
            ->withSum('sales', 'total')
            ->withSum('customerPayments', 'amount')
            ->orderBy('name');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'unpaid') {
                $query->whereRaw('
                    COALESCE((SELECT SUM(total) FROM sales WHERE sales.customer_id = customers.id), 0)
                    >
                    COALESCE((SELECT SUM(amount) FROM customer_payments WHERE customer_payments.customer_id = customers.id), 0)
                ');
            } elseif ($request->status === 'paid') {
                $query->whereRaw('
                    COALESCE((SELECT SUM(total) FROM sales WHERE sales.customer_id = customers.id), 0)
                    <=
                    COALESCE((SELECT SUM(amount) FROM customer_payments WHERE customer_payments.customer_id = customers.id), 0)
                ');
            }
        }

        $customers = $query->paginate(15)->through(function ($c) {
            $totalSales = (float) ($c->sales_sum_total ?? 0);
            $totalPaid = (float) ($c->customer_payments_sum_amount ?? 0);

            return [
                'id' => $c->id,
                'name' => $c->name,
                'phone' => $c->phone,
                'address' => $c->address,
                'total_sales' => $totalSales,
                'total_paid' => $totalPaid,
                'balance' => $totalSales - $totalPaid,
            ];
        });

        // ── STATEMENT ──
        $statement = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        $selected = null;

        if ($request->filled('customer_id') || $request->filled('from') || $request->filled('to')) {

            $saleQuery = DB::table('sales')
                ->join('customers', 'customers.id', '=', 'sales.customer_id')
                ->select(
                    'sales.sale_date      as date',
                    'customers.name       as customer_name',
                    'sales.total          as debit',
                    DB::raw('0            as credit'),
                    DB::raw('"Sale"       as type'),
                    DB::raw('NULL         as note')
                );

            $paymentQuery = DB::table('customer_payments')
                ->join('customers', 'customers.id', '=', 'customer_payments.customer_id')
                ->select(
                    'customer_payments.payment_date as date',
                    'customers.name                 as customer_name',
                    DB::raw('0                       as debit'),
                    'customer_payments.amount        as credit',
                    DB::raw('"Payment"               as type'),
                    'customer_payments.note          as note'
                );

            if ($request->filled('customer_id')) {
                $selected = Customer::findOrFail($request->customer_id);
                $saleQuery->where('sales.customer_id', $request->customer_id);
                $paymentQuery->where('customer_payments.customer_id', $request->customer_id);
            }

            if ($request->filled('from')) {
                $saleQuery->whereDate('sales.sale_date', '>=', $request->from);
                $paymentQuery->whereDate('customer_payments.payment_date', '>=', $request->from);
            }

            if ($request->filled('to')) {
                $saleQuery->whereDate('sales.sale_date', '<=', $request->to);
                $paymentQuery->whereDate('customer_payments.payment_date', '<=', $request->to);
            }

            $statement = DB::query()
                ->fromSub(
                    $saleQuery->unionAll($paymentQuery),
                    'ledger'
                )
                ->orderBy('date')
                ->paginate(20);
        }

        return view('customer.ledger', [
            'title' => 'Customer Ledger',
            'customers' => $customers,
            'globalTotals' => $globalTotals,
            'statement' => $statement,
            'selected' => $selected,
        ]);
    }
}
