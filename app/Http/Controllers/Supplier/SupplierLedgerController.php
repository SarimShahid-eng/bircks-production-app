<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function ledger(Request $request)
    {
        // ── GLOBAL TOTALS (always all data, unaffected by filters) ──
        $allSuppliers = Supplier::query()
            // ->whereHas('purchases')
            ->withSum('purchases', 'total')
            ->withSum('supplierPayments', 'amount')
            ->get()
            ->map(function ($s) {
                $totalPurchases = (float) ($s->purchases_sum_total ?? 0);
                $totalPaid = (float) ($s->supplier_payments_sum_amount ?? 0);

                return [
                    'id' => $s->id,
                    'name' => $s->name,
                    'total_purchases' => $totalPurchases,
                    'total_paid' => $totalPaid,
                    'balance' => $totalPurchases - $totalPaid,
                ];
            });

        $globalTotals = [
            'total_purchases' => $allSuppliers->sum('total_purchases'),
            'total_paid' => $allSuppliers->sum('total_paid'),
            'balance' => $allSuppliers->sum('balance'),
            'count' => $allSuppliers->count(),
        ];

        // ── PAGINATED + FILTERED TABLE ──
        $query = Supplier::query()
            // ->whereHas('purchases')
            ->withSum('purchases', 'total')
            ->withSum('supplierPayments', 'amount')
            ->orderBy('name');

        // search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        // filter by status
        if ($request->filled('status')) {
            if ($request->filled('status')) {
                if ($request->status === 'unpaid') {
                    $query->whereRaw('
            COALESCE((SELECT SUM(total) FROM purchases WHERE purchases.supplier_id = suppliers.id), 0)
            >
            COALESCE((SELECT SUM(amount) FROM supplier_payments WHERE supplier_payments.supplier_id = suppliers.id), 0)
        ');
                } elseif ($request->status === 'paid') {
                    $query->whereRaw('
            COALESCE((SELECT SUM(total) FROM purchases WHERE purchases.supplier_id = suppliers.id), 0)
            <=
            COALESCE((SELECT SUM(amount) FROM supplier_payments WHERE supplier_payments.supplier_id = suppliers.id), 0)
        ');
                }
            }
        }

        $suppliers = $query->paginate(15)->through(function ($s) {
            $totalPurchases = (float) ($s->purchases_sum_total ?? 0);
            $totalPaid = (float) ($s->supplier_payments_sum_amount ?? 0);

            return [
                'id' => $s->id,
                'name' => $s->name,
                'phone' => $s->phone,
                'address' => $s->address,
                'total_purchases' => $totalPurchases,
                'total_paid' => $totalPaid,
                'balance' => $totalPurchases - $totalPaid,
            ];
        });

        // ── STATEMENT ──
        $statement = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        $selected = null;

        if ($request->filled('supplier_id') || $request->filled('from') || $request->filled('to')) {

            $purchaseQuery = DB::table('purchases')
                ->join('suppliers', 'suppliers.id', '=', 'purchases.supplier_id')
                ->select(
                    'purchases.purchase_date as date',
                    'suppliers.name          as supplier_name',
                    'purchases.total         as debit',
                    DB::raw('0               as credit'),
                    DB::raw('"Purchase"      as type'),
                    DB::raw('NULL            as note')
                );

            $paymentQuery = DB::table('supplier_payments')
                ->join('suppliers', 'suppliers.id', '=', 'supplier_payments.supplier_id')
                ->select(
                    'supplier_payments.payment_date as date',
                    'suppliers.name                 as supplier_name',
                    DB::raw('0                       as debit'),
                    'supplier_payments.amount        as credit',
                    DB::raw('"Payment"               as type'),
                    'supplier_payments.note          as note'
                );

            if ($request->filled('supplier_id')) {
                $selected = Supplier::findOrFail($request->supplier_id);
                $purchaseQuery->where('purchases.supplier_id', $request->supplier_id);
                $paymentQuery->where('supplier_payments.supplier_id', $request->supplier_id);
            }

            if ($request->filled('from')) {
                $purchaseQuery->whereDate('purchases.purchase_date', '>=', $request->from);
                $paymentQuery->whereDate('supplier_payments.payment_date', '>=', $request->from);
            }

            if ($request->filled('to')) {
                $purchaseQuery->whereDate('purchases.purchase_date', '<=', $request->to);
                $paymentQuery->whereDate('supplier_payments.payment_date', '<=', $request->to);
            }

            $statement = DB::query()
                ->fromSub(
                    $purchaseQuery->unionAll($paymentQuery),
                    'ledger'
                )
                ->orderBy('date')
                ->paginate(20);
        }

        return view('supplier.ledger', [
            'title' => 'Supplier Ledger',
            'suppliers' => $suppliers,
            'unPaginatedSuppliers' => $allSuppliers,
            'globalTotals' => $globalTotals,
            'statement' => $statement,
            'selected' => $selected,
        ]);
    }
}
