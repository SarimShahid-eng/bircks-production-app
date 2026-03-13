@extends('partials.app', ['title' => $title])

@section('content')
<div class="col-span-12 space-y-6 xl:col-span-12">
    <div class="p-4 mx-auto max-w-screen-2xl md:p-6">

        {{-- PAGE HEADER --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Purchase Report</h2>
                <p class="mt-0.5 text-theme-sm text-gray-500 dark:text-gray-400">All material purchase records with filters</p>
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 mb-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <form method="GET" action="{{ route('reports.purchases') }}">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                    {{-- Date Range --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Date Range</label>
                        <x-datepicker-input name="date_range" value="{{ request('date_range', '') }}" />
                    </div>

                    {{-- Supplier --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Supplier</label>
                        <select name="supplier_id"
                            class="shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="">All Suppliers</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @selected(request('supplier_id') == $supplier->id)>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Material --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Material</label>
                        <select name="material_id"
                            class="shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="">All Materials</option>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}" @selected(request('material_id') == $material->id)>
                                    {{ $material->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="inline-flex h-[42px] flex-1 items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                            <svg class="fill-white" width="16" height="16" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
                            </svg>
                            Filter
                        </button>
                        @if(request()->anyFilled(['date_range', 'supplier_id', 'material_id']))
                            <a href="{{ route('reports.purchases') }}"
                                class="shadow-theme-xs flex h-[42px] items-center rounded-lg border border-gray-300 bg-white px-3.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                Clear
                            </a>
                        @endif
                    </div>

                </div>
            </form>
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">

            {{-- Total Records --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Purchases</span>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10">
                        <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                    </span>
                </div>
                <p class="text-2xl font-bold text-gray-800 dark:text-white/90">{{ number_format($totalRecords) }}</p>
                <p class="text-xs text-gray-400 mt-1">records</p>
            </div>

            {{-- Total Quantity --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Quantity</span>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
                        </svg>
                    </span>
                </div>
                <p class="text-2xl font-bold text-gray-800 dark:text-white/90">{{ number_format($totalQuantity, 2) }}</p>
                <p class="text-xs text-gray-400 mt-1">units purchased</p>
            </div>

            {{-- Total Amount --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Amount</span>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-500/10">
                        <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </span>
                </div>
                <p class="text-2xl font-bold text-gray-800 dark:text-white/90">Rs {{ number_format($totalAmount, 2) }}</p>
                <p class="text-xs text-gray-400 mt-1">total spent</p>
            </div>

        </div>

        {{-- TABLE --}}
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="px-5 py-4 sm:px-6">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Purchase Details</h3>
                <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">{{ $totalRecords }} record{{ $totalRecords !== 1 ? 's' : '' }} found</p>
            </div>

            <div class="custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                <table class="min-w-full">
                    <thead class="border-y border-gray-100 py-3">
                        <tr>
                            <th class="py-3 pr-4 text-left text-theme-sm text-gray-500">#</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Supplier</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Material</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Quantity</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Rate</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Total</th>
                            <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Purchase Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($purchases as $index => $purchase)
                            <tr class="hover:bg-gray-50 transition-colors dark:hover:bg-white/[0.02]">
                                <td class="py-3 pr-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 text-sm text-gray-800 dark:text-white/90">{{ $purchase->supplier->name ?? '—' }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">{{ $purchase->material->name ?? '—' }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ number_format($purchase->quantity, 2) }}
                                    <span class="text-xs text-gray-400">{{ $purchase->material->unit ?? '' }}</span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">Rs {{ number_format($purchase->rate, 2) }}</td>
                                <td class="py-3 px-4 text-sm font-medium text-orange-600">Rs {{ number_format($purchase->total, 2) }}</td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center text-gray-500">No purchases found</td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if($purchases->count() > 0)
                        <tfoot>
                            <tr class="border-t-2 border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-white/[0.02]">
                                <td colspan="3" class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Totals</td>
                                <td class="py-3 px-4 text-sm font-bold text-gray-800 dark:text-white/90">{{ number_format($totalQuantity, 2) }}</td>
                                <td class="py-3 px-4"></td>
                                <td class="py-3 px-4 text-sm font-bold text-orange-600">Rs {{ number_format($totalAmount, 2) }}</td>
                                <td class="py-3 px-4"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
