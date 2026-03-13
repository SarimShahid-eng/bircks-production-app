<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\ProductionBatch;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfitController extends Controller
{
  public function profit(Request $request)
    {
        // ── Date Range ──
        $startDate = null;
        $endDate   = null;

        if ($request->filled('date_range') && str_contains($request->date_range, ' - ')) {
            $parts     = explode(' - ', $request->date_range);
            $startDate = Carbon::createFromFormat('M j, Y', trim($parts[0]))->startOfDay();
            $endDate   = Carbon::createFromFormat('M j, Y', trim($parts[1]))->endOfDay();
        }

        // ── Batches with their sales ──
        $batchQuery = ProductionBatch::with(['sales'])
            ->orderByDesc('production_date');

        if ($startDate && $endDate) {
            $batchQuery->whereBetween('production_date', [$startDate, $endDate]);
        }

        $batches = $batchQuery->get()->map(function ($batch) {
            $revenue      = $batch->sales->sum('total');
            $cost         = (float) $batch->total_cost;
            $profit       = $revenue - $cost;
            $unsoldBricks = $batch->bricks_produced - $batch->sales->sum('quantity_sold');

            return [
                'id'             => $batch->id,
                'batch_no'       => $batch->batch_no,
                'production_date'=> $batch->production_date,
                'bricks_produced'=> $batch->bricks_produced,
                'unsold_bricks'  => $unsoldBricks,
                'total_cost'     => $cost,
                'revenue'        => $revenue,
                'profit'         => $profit,
                'margin'         => $revenue > 0 ? round(($profit / $revenue) * 100, 1) : 0,
            ];
        });

        // ── Period totals ──
        $salesQuery = Sale::query();
        if ($startDate && $endDate) {
            $salesQuery->whereBetween('sale_date', [$startDate, $endDate]);
        }
        $totalRevenue = $salesQuery->sum('total');

        $costQuery = ProductionBatch::query();
        if ($startDate && $endDate) {
            $costQuery->whereBetween('production_date', [$startDate, $endDate]);
        }
        $totalCost   = $costQuery->sum('total_cost');
        $totalProfit = $totalRevenue - $totalCost;

        return view('report.profit', [
            'title'         => 'Profit Report',
            'batches'       => $batches,
            'totalRevenue'  => $totalRevenue,
            'totalCost'     => $totalCost,
            'totalProfit'   => $totalProfit,
            'profitMargin'  => $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 1) : 0,
        ]);
    }
    //
}
