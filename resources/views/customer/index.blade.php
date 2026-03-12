@extends('partials.app', ['title' => $title])

@section('content')

    <div class="col-span-12 space-y-6 xl:col-span-12">
      <x-toast />

        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between px-3">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Customers</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Manage your customer directory</p>
            </div>
            <a href="{{ route('customers.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white rounded-lg bg-brand-500 hover:bg-brand-600 transition-colors shadow-theme-xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Add Customer
            </a>
        </div>

        {{-- TABLE CARD --}}
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

            {{-- TOOLBAR --}}
            <div class="mb-4 flex flex-col gap-3 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">All Customers</h3>
                    <p class="mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">
                        {{ $customers->total() }} customer{{ $customers->total() !== 1 ? 's' : '' }} found
                    </p>
                </div>
                <form method="GET" action="{{ route('customers.index') }}">
                    <div class="flex items-center gap-2">

                        <x-search-input placeholder="Search Customer..." />

                        <button type="submit"
                            class="inline-flex h-[42px] items-center gap-2 rounded-lg bg-brand-500 px-4 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                            <svg class="fill-white" width="16" height="16" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                    fill="" />
                            </svg>
                            Search
                        </button>

                        @if (request('search'))
                            <a href="{{ route('customers.index') }}"
                                class="shadow-theme-xs flex h-[42px] items-center rounded-lg border border-gray-300 bg-white px-3.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                Clear
                            </a>
                        @endif

                    </div>
                </form>
            </div>

            {{-- TABLE --}}
            <div class="custom-scrollbar max-w-full overflow-x-auto overflow-y-visible px-5 sm:px-6">
                <table class="min-w-full">
                    <thead class="border-y border-gray-100 py-3 dark:border-gray-800">
                        <tr>
                            <th class="py-3 pr-4 font-normal whitespace-nowrap text-left">
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">#</p>
                            </th>
                            <th class="py-3 px-4 font-normal whitespace-nowrap text-left">
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Customer Name</p>
                            </th>
                            <th class="py-3 px-4 font-normal whitespace-nowrap text-left">
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Contact</p>
                            </th>
                            <th class="py-3 px-4 font-normal whitespace-nowrap text-left">
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Address</p>
                            </th>
                            <th class="py-3 px-4 font-normal whitespace-nowrap text-center">
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Actions</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                        @forelse($customers as $index => $customer)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">

                                <td class="py-3 pr-4 whitespace-nowrap">
                                    <p class="text-theme-sm text-gray-400 dark:text-gray-500">
                                        {{ $customers->firstItem() + $index }}
                                    </p>
                                </td>

                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-50 text-sm font-semibold text-brand-500 dark:bg-brand-500/10 dark:text-brand-400 flex-shrink-0">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="text-theme-sm block font-medium text-gray-700 dark:text-gray-300">
                                                {{ $customer->name }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <svg class="text-gray-400 flex-shrink-0" width="15" height="15"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path
                                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.1 6.1l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                                        </svg>
                                        <p class="text-theme-sm text-gray-700 dark:text-gray-400">
                                            {{ $customer->phone ?? 'N/A' }}
                                        </p>
                                    </div>
                                </td>

                                <td class="py-3 px-4 max-w-[220px]">
                                    <div class="flex items-start gap-2">
                                        <svg class="text-gray-400 flex-shrink-0 mt-0.5" width="14" height="14"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                            <circle cx="12" cy="10" r="3" />
                                        </svg>
                                        <p class="text-theme-sm text-gray-700 dark:text-gray-400 line-clamp-2 leading-snug">
                                            {{ $customer->address ?? 'N/A' }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('customers.edit', $customer->id) }}" title="Edit"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-brand-500 hover:bg-brand-50 dark:text-gray-400 dark:hover:text-brand-400 dark:hover:bg-brand-500/10 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.5" class="text-gray-400">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                                <circle cx="9" cy="7" r="4" />
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                            </svg>
                                        </div>
                                        <p class="text-theme-sm font-medium text-gray-800 dark:text-white/90">
                                            @if (request('search'))
                                                No results for "{{ request('search') }}"
                                            @else
                                                No customers yet
                                            @endif
                                        </p>
                                        <p class="text-theme-xs text-gray-500 dark:text-gray-400">
                                            @if (request('search'))
                                                Try a different term or
                                                <a href="{{ route('customers.index') }}"
                                                    class="text-brand-500 hover:underline">clear filter</a>
                                            @else
                                                Get started by adding your first customer
                                            @endif
                                        </p>
                                        @if (!request('search'))
                                            <a href="{{ route('customers.create') }}"
                                                class="mt-1 inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600 transition-colors">
                                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2.5">
                                                    <line x1="12" y1="5" x2="12" y2="19" />
                                                    <line x1="5" y1="12" x2="19" y2="12" />
                                                </svg>
                                                Add First Customer
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if ($customers->hasPages())
                <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
                    <div class="flex items-center justify-between">

                        @if ($customers->onFirstPage())
                            <button disabled
                                class="text-theme-sm shadow-theme-xs flex cursor-not-allowed items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 font-medium text-gray-400 sm:px-3.5 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-600">
                                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z" fill="" />
                                </svg>
                                <span class="hidden sm:inline">Previous</span>
                            </button>
                        @else
                            <a href="{{ $customers->previousPageUrl() }}"
                                class="text-theme-sm shadow-theme-xs flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 sm:px-3.5 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z" fill="" />
                                </svg>
                                <span class="hidden sm:inline">Previous</span>
                            </a>
                        @endif

                        <span class="block text-sm font-medium text-gray-700 sm:hidden dark:text-gray-400">
                            Page {{ $customers->currentPage() }} of {{ $customers->lastPage() }}
                        </span>

                        <ul class="hidden items-center gap-0.5 sm:flex">
                            @foreach ($customers->getUrlRange(max(1, $customers->currentPage() - 2), min($customers->lastPage(), $customers->currentPage() + 2)) as $page => $url)
                                <li>
                                    @if ($page == $customers->currentPage())
                                        <span class="bg-brand-500/[0.08] text-theme-sm text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}"
                                            class="text-theme-sm hover:bg-brand-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700 dark:text-gray-400">
                                            {{ $page }}
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        @if ($customers->hasMorePages())
                            <a href="{{ $customers->nextPageUrl() }}"
                                class="text-theme-sm shadow-theme-xs flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 sm:px-3.5 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                <span class="hidden sm:inline">Next</span>
                                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z" fill="" />
                                </svg>
                            </a>
                        @else
                            <button disabled
                                class="text-theme-sm shadow-theme-xs flex cursor-not-allowed items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 font-medium text-gray-400 sm:px-3.5 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-600">
                                <span class="hidden sm:inline">Next</span>
                                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z" fill="" />
                                </svg>
                            </button>
                        @endif

                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection
