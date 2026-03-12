@extends('partials.app', ['title' => 'Supplier Ledger'])

@section('content')
    <div class="col-span-12 space-y-6 xl:col-span-12">
        <div class="p-4 mx-auto max-w-screen-2xl md:p-6">

            <x-toast />

            {{-- PAGE HEADER --}}
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Supplier Ledger</h2>
                    <p class="mt-0.5 text-theme-sm text-gray-500 dark:text-gray-400">Track purchases, payments and
                        outstanding balances</p>
                </div>
            </div>

            {{-- ── TABS ── --}}
            <div class="mb-5 flex gap-2 border-b border-gray-200 dark:border-gray-800">
                <button onclick="switchTab('overview')" id="tab-overview"
                    class="tab-btn relative px-4 py-2.5 text-sm font-medium transition-colors text-brand-500 border-b-2 border-brand-500">
                    Overview
                </button>
                <button onclick="switchTab('statement')" id="tab-statement"
                    class="tab-btn relative px-4 py-2.5 text-sm font-medium transition-colors text-gray-500 border-b-2 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                    Statement
                </button>
            </div>

            {{-- ══════════════════════════════════════════ --}}
            {{-- TAB 1 — OVERVIEW                          --}}
            {{-- ══════════════════════════════════════════ --}}
            <div id="panel-overview">

                {{-- SUMMARY CARDS --}}
                {{-- ── SUMMARY CARDS ── --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-5">

                    <div
                        class="rounded-2xl border border-gray-200 bg-white px-5 py-4 dark:border-gray-800 dark:bg-white/[0.03]">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Total
                            Purchases</p>
                        <p class="mt-1.5 text-2xl font-semibold text-gray-800 dark:text-white/90">
                            Rs {{ number_format($globalTotals['total_purchases'], 2) }}
                        </p>
                        <p class="mt-0.5 text-xs text-gray-400">Across {{ $globalTotals['count'] }} suppliers</p>
                    </div>

                    <div
                        class="rounded-2xl border border-gray-200 bg-white px-5 py-4 dark:border-gray-800 dark:bg-white/[0.03]">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Total Paid
                        </p>
                        <p class="mt-1.5 text-2xl font-semibold text-green-600">
                            Rs {{ number_format($globalTotals['total_paid'], 2) }}
                        </p>
                        <p class="mt-0.5 text-xs text-gray-400">Payments recorded</p>
                    </div>

                    <div
                        class="rounded-2xl border border-gray-200 bg-white px-5 py-4 dark:border-gray-800 dark:bg-white/[0.03]">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Outstanding
                            Balance</p>
                        <p class="mt-1.5 text-2xl font-semibold text-red-500">
                            Rs {{ number_format($globalTotals['balance'], 2) }}
                        </p>
                        <p class="mt-0.5 text-xs text-gray-400">Remaining to be paid</p>
                    </div>

                </div>

                {{-- OVERVIEW TABLE --}}
                <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

                    {{-- <div class="mb-4 flex items-center justify-between px-5 sm:px-6">
                        <div>
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">All Suppliers</h3>
                            <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">Click statement icon to view
                                detailed transactions</p>
                        </div>
                    </div> --}}
                    <div class="mb-4 flex flex-col gap-3 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                        <div>
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">All Suppliers</h3>
                            <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">
                                {{ $suppliers->total() }} supplier{{ $suppliers->total() !== 1 ? 's' : '' }} found
                            </p>
                        </div>

                        <form method="GET" action="{{ route('suppliers.ledger') }}">
                            <input type="hidden" name="tab" value="overview">
                            <div class="flex items-center gap-2">

                                {{-- Search Input --}}
                                <x-search-input placeholder="Search Suppliers here..." />
                                <select name="status"
                                    class="shadow-theme-xs h-[42px] rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <option value="">All Status</option>
                                    <option value="unpaid" @selected(request('status') === 'unpaid')>Unpaid</option>
                                    <option value="paid" @selected(request('status') === 'paid')>Cleared</option>
                                </select>

                                {{-- Search Button --}}
                                <button type="submit"
                                    class="inline-flex h-[42px] items-center gap-2 rounded-lg bg-brand-500 px-4 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                                    <svg class="fill-white w-4 h-4" viewBox="0 0 20 20" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                            fill="" />
                                    </svg>
                                    Search
                                </button>

                                {{-- Clear --}}
                                @if (request('search') || request('status'))
                                    <a href="{{ route('suppliers.ledger', ['tab' => 'overview']) }}"
                                        class="shadow-theme-xs flex h-[42px] items-center rounded-lg border border-gray-300 bg-white px-3.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                        Clear
                                    </a>
                                @endif

                            </div>
                        </form>
                    </div>

                    <div class="custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                        <table class="min-w-full">
                            <thead class="border-y border-gray-100 dark:border-gray-800">
                                <tr>
                                    <th class="py-3 pr-4 text-left text-theme-sm text-gray-500">#</th>
                                    <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Supplier</th>
                                    <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Phone</th>
                                    <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Total Purchases</th>
                                    <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Total Paid</th>
                                    <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Balance</th>
                                    <th class="py-3 px-4 text-center text-theme-sm text-gray-500">Statement</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse($suppliers as $index => $supplier)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">

                                        <td class="py-3 pr-4 text-sm text-gray-500">{{ $index + 1 }}</td>

                                        <td class="py-3 px-4">
                                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                {{ $supplier['name'] }}</p>

                                        </td>

                                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $supplier['phone'] ?? '—' }}
                                        </td>

                                        <td class="py-3 px-4 text-right text-sm text-gray-800 dark:text-white/90">
                                            Rs {{ number_format($supplier['total_purchases'], 2) }}
                                        </td>

                                        <td class="py-3 px-4 text-right text-sm font-medium text-green-600">
                                            Rs {{ number_format($supplier['total_paid'], 2) }}
                                        </td>

                                        <td class="py-3 px-4 text-right">
                                            @if ($supplier['balance'] > 0)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-600 dark:bg-red-500/10 dark:text-red-400">
                                                    Rs {{ number_format($supplier['balance'], 2) }}
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-600 dark:bg-green-500/10 dark:text-green-400">
                                                    Cleared
                                                </span>
                                            @endif
                                        </td>

                                        <td class="py-3 px-4 text-center">
                                            <button
                                                onclick="openStatement('{{ $supplier['id'] }}', '{{ $supplier['name'] }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-brand-500 hover:bg-brand-50 dark:text-gray-400 dark:hover:text-brand-400 dark:hover:bg-brand-500/10 transition-colors"
                                                title="View Statement">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path
                                                        d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                                                    <rect x="9" y="3" width="6" height="4" rx="1" />
                                                    <line x1="9" y1="12" x2="15" y2="12" />
                                                    <line x1="9" y1="16" x2="13" y2="16" />
                                                </svg>
                                            </button>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-16 text-center text-sm text-gray-400">No suppliers
                                            found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="border-t border-gray-200 px-6 py-4">
                        {{ $suppliers->appends(['tab' => 'overview', 'search' => request('search'), 'status' => request('status')])->links() }}
                    </div>

                </div>
            </div>
            {{-- ══════════════════════════════════════════ --}}
            {{-- TAB 2 — STATEMENT                         --}}
            {{-- ══════════════════════════════════════════ --}}
            @include('supplier.ledgerPartial.statementTab')

        </div>
    </div>

    <script>
        function switchTab(tab) {
            // panels
            document.getElementById('panel-overview').classList.toggle('hidden', tab !== 'overview');
            document.getElementById('panel-statement').classList.toggle('hidden', tab !== 'statement');

            // tab buttons
            const tabs = ['overview', 'statement'];
            tabs.forEach(t => {
                const btn = document.getElementById('tab-' + t);
                if (t === tab) {
                    btn.classList.add('text-brand-500', 'border-brand-500');
                    btn.classList.remove('text-gray-500', 'border-transparent');
                } else {
                    btn.classList.remove('text-brand-500', 'border-brand-500');
                    btn.classList.add('text-gray-500', 'border-transparent');
                }
            });
        }

        // clicking statement icon in overview switches to statement tab and pre-selects supplier
        function openStatement(supplierId, supplierName) {
            document.getElementById('supplierSelect').value = supplierId;
            switchTab('statement');
            document.getElementById('statementForm').submit();
        }

        // restore active tab on page load (after filter submit)
        document.addEventListener('DOMContentLoaded', function() {
            const activeTab = '{{ request('tab', 'overview') }}';
            switchTab(activeTab);
        });
    </script>
@endsection
