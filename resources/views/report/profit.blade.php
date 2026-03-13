@extends('partials.app', ['title' => $title])

@section('content')
<div class="col-span-12 space-y-6 xl:col-span-12">
    <div class="p-4 mx-auto max-w-screen-2xl md:p-6">

        {{-- PAGE HEADER --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Profit Report</h2>
                <p class="mt-0.5 text-theme-sm text-gray-500 dark:text-gray-400">Revenue, cost and profit breakdown</p>
            </div>

            {{-- Date Filter --}}
            <form method="GET" action="{{ route('reports.profit') }}" class="flex items-center gap-2">
                <x-datepicker-input name="date_range" value="{{ request('date_range', '') }}" />
                <button type="submit"
                    class="inline-flex h-[42px] items-center gap-2 rounded-lg bg-brand-500 px-4 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                    <svg class="fill-white" width="16" height="16" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
                    </svg>
                    Filter
                </button>
                @if(request('date_range'))
                    <a href="{{ route('reports.profit') }}"
                        class="shadow-theme-xs flex h-[42px] items-center rounded-lg border border-gray-300 bg-white px-3.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 mb-6">

            {{-- Total Revenue --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</span>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </span>
                </div>
                <p class="text-2xl font-bold text-gray-800 dark:text-white/90">
                    Rs {{ number_format($totalRevenue, 2) }}
                </p>
            </div>

            {{-- Total Cost --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Cost</span>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-500/10">
                        <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>
                        </svg>
                    </span>
                </div>
                <p class="text-2xl font-bold text-gray-800 dark:text-white/90">
                    Rs {{ number_format($totalCost, 2) }}
                </p>
            </div>

            {{-- Net Profit --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Net Profit</span>
                    <span @class([
                        'inline-flex items-center justify-center w-10 h-10 rounded-xl',
                        'bg-green-50 dark:bg-green-500/10' => $totalProfit >= 0,
                        'bg-red-50 dark:bg-red-500/10'    => $totalProfit < 0,
                    ])>
                        <svg @class([
                            'w-5 h-5',
                            'text-green-500' => $totalProfit >= 0,
                            'text-red-500'   => $totalProfit < 0,
                        ]) fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            @if($totalProfit >= 0)
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>
                            @else
                                <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/>
                            @endif
                        </svg>
                    </span>
                </div>
                <p @class([
                    'text-2xl font-bold',
                    'text-green-600' => $totalProfit >= 0,
                    'text-red-500'   => $totalProfit < 0,
                ])>
                    Rs {{ number_format($totalProfit, 2) }}
                </p>
            </div>

            {{-- Profit Margin --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Profit Margin</span>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-500/10">
                        <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>
                        </svg>
                    </span>
                </div>
                <p @class([
                    'text-2xl font-bold',
                    'text-green-600' => $profitMargin >= 0,
                    'text-red-500'   => $profitMargin < 0,
                ])>
                    {{ $profitMargin }}%
                </p>
            </div>

        </div>

        {{-- BATCH-WISE TABLE --}}
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Batch-wise Breakdown</h3>
                <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">Profit calculated per production batch</p>
            </div>

            <div class="custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                <table class="min-w-full">
                    <thead class="border-y border-gray-100 py-3">
                        <tr>
                            <th class="py-3 pr-4 text-left text-theme-sm text-gray-500">#</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Batch</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Date</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Bricks Produced</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Unsold</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Production Cost</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Revenue</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Profit</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Margin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($batches as $index => $batch)
                            <tr class="hover:bg-gray-50 transition-colors dark:hover:bg-white/[0.02]">

                                <td class="py-3 pr-4 text-sm text-gray-500">{{ $index + 1 }}</td>

                                <td class="py-3 px-4 text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $batch['batch_no'] }}
                                </td>

                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($batch['production_date'])->format('d M Y') }}
                                </td>

                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ number_format($batch['bricks_produced']) }}
                                </td>

                                <td class="py-3 px-4 text-sm">
                                    <span @class([
                                        'font-medium',
                                        'text-orange-500' => $batch['unsold_bricks'] > 0,
                                        'text-gray-400'   => $batch['unsold_bricks'] <= 0,
                                    ])>
                                        {{ number_format($batch['unsold_bricks']) }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 text-sm text-orange-600 font-medium">
                                    Rs {{ number_format($batch['total_cost'], 2) }}
                                </td>

                                <td class="py-3 px-4 text-sm text-blue-600 font-medium">
                                    Rs {{ number_format($batch['revenue'], 2) }}
                                </td>

                                <td class="py-3 px-4 text-sm font-semibold">
                                    <span @class([
                                        'text-green-600' => $batch['profit'] >= 0,
                                        'text-red-500'   => $batch['profit'] < 0,
                                    ])>
                                        Rs {{ number_format($batch['profit'], 2) }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 text-sm">
                                    <span @class([
                                        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                        'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400' => $batch['margin'] >= 0,
                                        'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400'         => $batch['margin'] < 0,
                                    ])>
                                        {{ $batch['margin'] }}%
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-16 text-center text-gray-500">
                                    No batches found for the selected period
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    {{-- Totals Row --}}
                    @if($batches->count() > 0)
                        <tfoot>
                            <tr class="border-t-2 border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-white/[0.02]">
                                <td colspan="5" class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Totals
                                </td>
                                <td class="py-3 px-4 text-sm font-bold text-orange-600">
                                    Rs {{ number_format($totalCost, 2) }}
                                </td>
                                <td class="py-3 px-4 text-sm font-bold text-blue-600">
                                    Rs {{ number_format($totalRevenue, 2) }}
                                </td>
                                <td class="py-3 px-4 text-sm font-bold">
                                    <span @class([
                                        'text-green-600' => $totalProfit >= 0,
                                        'text-red-500'   => $totalProfit < 0,
                                    ])>
                                        Rs {{ number_format($totalProfit, 2) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm font-bold">
                                    <span @class([
                                        'text-green-600' => $profitMargin >= 0,
                                        'text-red-500'   => $profitMargin < 0,
                                    ])>
                                        {{ $profitMargin }}%
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    @endif

                </table>
            </div>
        </div>

    </div>
</div>
@endsection
