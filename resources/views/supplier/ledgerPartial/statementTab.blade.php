 <div id="panel-statement" class="hidden">

                {{-- FILTER BAR --}}
                <form method="GET" action="{{ route('suppliers.ledger') }}" id="statementForm">
                    <input type="hidden" name="tab" value="statement">
                    <div
                        class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-5 sm:p-6 mb-5">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">

                            {{-- Supplier --}}
                            <div>
                                <label
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Supplier</label>
                                <select name="supplier_id" id="supplierSelect"
                                    class="shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <option value="">All Suppliers</option>
                                    {{-- @dd($unPaginatedSuppliers) --}}
                                    @foreach ($unPaginatedSuppliers as $s)
                                        <option value="{{ $s['id'] }}" @selected(request('supplier_id') == $s['id'])>
                                            {{ $s['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- From --}}
                            <div>
                                <label
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">From</label>
                                <input type="date" name="from" value="{{ request('from') }}"
                                    class="shadow-theme-xs h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            </div>

                            {{-- To --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">To</label>
                                <input type="date" name="to" value="{{ request('to') }}"
                                    class="shadow-theme-xs h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-end gap-2">
                                <button type="submit"
                                    class="inline-flex h-10 flex-1 items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <circle cx="11" cy="11" r="8" />
                                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                    </svg>
                                    Filter
                                </button>
                                @if (request('supplier_id') || request('from') || request('to'))
                                    <a href="{{ route('suppliers.ledger', ['tab' => 'statement']) }}"
                                        class="inline-flex h-10 items-center justify-center rounded-lg border border-gray-300 bg-white px-3.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                        Clear
                                    </a>
                                @endif
                            </div>

                        </div>
                    </div>
                </form>

                {{-- STATEMENT TABLE --}}
                <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

                    {{-- Header --}}
                    <div class="mb-4 flex items-center justify-between px-5 sm:px-6">
                        <div>
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                                @if ($selected)
                                    {{ $selected->name }} — Statement
                                @else
                                    All Transactions
                                @endif
                            </h3>
                            <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">
                                @if (request('from') || request('to'))
                                    {{ request('from') ? \Carbon\Carbon::parse(request('from'))->format('d M Y') : 'Beginning' }}
                                    —
                                    {{ request('to') ? \Carbon\Carbon::parse(request('to'))->format('d M Y') : 'Today' }}
                                @else
                                    All time
                                @endif
                                &bull; {{ $statement->total() }} record{{ $statement->total() !== 1 ? 's' : '' }}
                            </p>
                        </div>
                        @if ($statement->isNotEmpty())
                            <button onclick="window.print()"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="6 9 6 2 18 2 18 9" />
                                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                    <rect x="6" y="14" width="12" height="8" />
                                </svg>
                                Print
                            </button>
                        @endif
                    </div>

                    @if ($statement->isNotEmpty())
                        <div class="custom-scrollbar max-w-full overflow-x-auto px-5 sm:px-6">
                            <table class="min-w-full">
                                <thead class="border-y border-gray-100 dark:border-gray-800">
                                    <tr>
                                        <th class="py-3 pr-4 text-left text-theme-sm text-gray-500">#</th>
                                        <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Date</th>
                                        <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Supplier</th>
                                        <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Type</th>
                                        <th class="py-3 px-4 text-left text-theme-sm text-gray-500">Note</th>
                                        <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Debit</th>
                                        <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Credit</th>
                                        <th class="py-3 px-4 text-right text-theme-sm text-gray-500">Balance</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @php $runningBalance = 0; @endphp
                                    @foreach ($statement as $index => $entry)
                                        @php $runningBalance += $entry->debit - $entry->credit; @endphp
                                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">

                                            <td class="py-3 pr-4 text-sm text-gray-500">
                                                {{ $statement->firstItem() + $index }}
                                            </td>

                                            <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($entry->date)->format('d M Y') }}
                                            </td>

                                            <td class="py-3 px-4 text-sm text-gray-800 dark:text-white/90">
                                                {{ $entry->supplier_name ?? '—' }}
                                            </td>

                                            <td class="py-3 px-4">
                                                @if ($entry->type === 'Purchase')
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                                                        Purchase
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-600 dark:bg-green-500/10 dark:text-green-400">
                                                        Payment
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="py-3 px-4 text-sm text-gray-500 max-w-[160px] truncate">
                                                {{ $entry->note ?? '—' }}
                                            </td>

                                            <td class="py-3 px-4 text-right text-sm font-medium text-red-500">
                                                {{ $entry->debit > 0 ? 'Rs ' . number_format($entry->debit, 2) : '—' }}
                                            </td>

                                            <td class="py-3 px-4 text-right text-sm font-medium text-green-600">
                                                {{ $entry->credit > 0 ? 'Rs ' . number_format($entry->credit, 2) : '—' }}
                                            </td>

                                            <td
                                                class="py-3 px-4 text-right text-sm font-semibold {{ $runningBalance > 0 ? 'text-red-500' : 'text-green-600' }}">
                                                Rs {{ number_format(abs($runningBalance), 2) }}
                                                <span
                                                    class="text-xs font-normal">{{ $runningBalance > 0 ? 'DR' : 'CR' }}</span>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>

                                {{-- Totals footer --}}
                                <tfoot class="border-t-2 border-gray-200 dark:border-gray-700">
                                    <tr>
                                        <td colspan="5"
                                            class="py-3 px-4 text-sm font-semibold text-gray-800 dark:text-white/90">Page
                                            Totals</td>
                                        <td class="py-3 px-4 text-right text-sm font-semibold text-red-500">
                                            Rs {{ number_format($statement->sum('debit'), 2) }}
                                        </td>
                                        <td class="py-3 px-4 text-right text-sm font-semibold text-green-600">
                                            Rs {{ number_format($statement->sum('credit'), 2) }}
                                        </td>
                                        <td
                                            class="py-3 px-4 text-right text-sm font-bold {{ $runningBalance > 0 ? 'text-red-500' : 'text-green-600' }}">
                                            Rs {{ number_format(abs($runningBalance), 2) }}
                                            <span
                                                class="text-xs font-normal">{{ $runningBalance > 0 ? 'DR' : 'CR' }}</span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- PAGINATION --}}
                        <div class="border-t border-gray-200 px-6 py-4">
                            {{ $statement->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="py-16 text-center">
                            <svg class="mx-auto mb-3 w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                                <rect x="9" y="3" width="6" height="4" rx="1" />
                            </svg>
                            <p class="text-sm text-gray-400">
                                {{ request('supplier_id') || request('from') || request('to') ? 'No transactions found for the selected filters.' : 'Select a supplier or apply filters to view transactions.' }}
                            </p>
                        </div>
                    @endif

                </div>
            </div>
