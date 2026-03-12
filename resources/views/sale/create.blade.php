@extends('partials.app', ['title' => $title])

@section('content')
    <div class="col-span-12 space-y-6 xl:col-span-12">
        <div class="p-4 mx-auto max-w-screen-2xl md:p-6">

            <x-toast />
            <x-toast-error field="updateId" />
            <x-toast-error field="customer_id" />
            <x-toast-error field="production_batch_id" />

            {{-- PAGE HEADER --}}
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">
                        {{ isset($sale) ? 'Edit Sale' : 'Add Sale' }}
                    </h2>
                    <p class="mt-0.5 text-theme-sm text-gray-500 dark:text-gray-400">
                        {{ isset($sale) ? 'Update the sale record details' : 'Fill in the details to record a new sale' }}
                    </p>
                </div>
                <a href="{{ route('sales.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Back to Sales
                </a>
            </div>

            <form method="POST" action="{{ route('sales.store') }}">
                @csrf
                <input type="hidden" name="updateId" value="{{ $sale->id ?? '' }}">

                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

                    {{-- Card Header --}}
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Sale Information</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Customer, batch and pricing details for
                            this sale.</p>
                    </div>

                    {{-- Fields --}}
                    <div class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            {{-- Customer --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Customer <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="customer_id" @class([
                                        'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:text-white/90',
                                        'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                            'customer_id'),
                                        'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                            'customer_id'),
                                    ])>
                                        <option value="">Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}" @selected(old('customer_id', $sale->customer_id ?? '') == $customer->id)>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('customer_id'))
                                        <span class="absolute top-1/2 right-3.5 -translate-y-1/2">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M2.58325 7.99967C2.58325 5.00813 5.00838 2.58301 7.99992 2.58301C10.9915 2.58301 13.4166 5.00813 13.4166 7.99967C13.4166 10.9912 10.9915 13.4163 7.99992 13.4163C5.00838 13.4163 2.58325 10.9912 2.58325 7.99967ZM7.99992 1.08301C4.17995 1.08301 1.08325 4.17971 1.08325 7.99967C1.08325 11.8196 4.17995 14.9163 7.99992 14.9163C11.8199 14.9163 14.9166 11.8196 14.9166 7.99967C14.9166 4.17971 11.8199 1.08301 7.99992 1.08301ZM7.09932 5.01639C7.09932 5.51345 7.50227 5.91639 7.99932 5.91639H7.99999C8.49705 5.91639 8.89999 5.51345 8.89999 5.01639C8.89999 4.51933 8.49705 4.11639 7.99999 4.11639H7.99932C7.50227 4.11639 7.09932 4.51933 7.09932 5.01639ZM7.99998 11.8306C7.58576 11.8306 7.24998 11.4948 7.24998 11.0806V7.29627C7.24998 6.88206 7.58576 6.54627 7.99998 6.54627C8.41419 6.54627 8.74998 6.88206 8.74998 7.29627V11.0806C8.74998 11.4948 8.41419 11.8306 7.99998 11.8306Z"
                                                    fill="#F04438" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                @error('customer_id')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Production Batch --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Production Batch <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="production_batch_id" @class([
                                        'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:text-white/90',
                                        'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                            'production_batch_id'),
                                        'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                            'production_batch_id'),
                                    ])>
                                        <option value="">Select Batch</option>
                                        @foreach ($batches as $batch)
                                            <option value="{{ $batch->id }}" @selected(old('production_batch_id', $sale->production_batch_id ?? '') == $batch->id)>
                                                {{ $batch->batch_no }} —
                                                {{ \Carbon\Carbon::parse($batch->production_date)->format('d M Y') }}
                                                ({{ number_format($batch->bricks_produced) }} bricks)
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('production_batch_id'))
                                        <span class="absolute top-1/2 right-3.5 -translate-y-1/2">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M2.58325 7.99967C2.58325 5.00813 5.00838 2.58301 7.99992 2.58301C10.9915 2.58301 13.4166 5.00813 13.4166 7.99967C13.4166 10.9912 10.9915 13.4163 7.99992 13.4163C5.00838 13.4163 2.58325 10.9912 2.58325 7.99967ZM7.99992 1.08301C4.17995 1.08301 1.08325 4.17971 1.08325 7.99967C1.08325 11.8196 4.17995 14.9163 7.99992 14.9163C11.8199 14.9163 14.9166 11.8196 14.9166 7.99967C14.9166 4.17971 11.8199 1.08301 7.99992 1.08301ZM7.09932 5.01639C7.09932 5.51345 7.50227 5.91639 7.99932 5.91639H7.99999C8.49705 5.91639 8.89999 5.51345 8.89999 5.01639C8.89999 4.51933 8.49705 4.11639 7.99999 4.11639H7.99932C7.50227 4.11639 7.09932 4.51933 7.09932 5.01639ZM7.99998 11.8306C7.58576 11.8306 7.24998 11.4948 7.24998 11.0806V7.29627C7.24998 6.88206 7.58576 6.54627 7.99998 6.54627C8.41419 6.54627 8.74998 6.88206 8.74998 7.29627V11.0806C8.74998 11.4948 8.41419 11.8306 7.99998 11.8306Z"
                                                    fill="#F04438" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                @error('production_batch_id')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Quantity Sold --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Quantity Sold <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="quantity_sold"
                                        value="{{ old('quantity_sold', $sale->quantity_sold ?? '') }}"
                                        placeholder="e.g. 1000" min="1" step="any" @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                                'quantity_sold'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                                'quantity_sold'),
                                        ])>
                                    @if ($errors->has('quantity_sold'))
                                        <span class="absolute top-1/2 right-3.5 -translate-y-1/2">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M2.58325 7.99967C2.58325 5.00813 5.00838 2.58301 7.99992 2.58301C10.9915 2.58301 13.4166 5.00813 13.4166 7.99967C13.4166 10.9912 10.9915 13.4163 7.99992 13.4163C5.00838 13.4163 2.58325 10.9912 2.58325 7.99967ZM7.99992 1.08301C4.17995 1.08301 1.08325 4.17971 1.08325 7.99967C1.08325 11.8196 4.17995 14.9163 7.99992 14.9163C11.8199 14.9163 14.9166 11.8196 14.9166 7.99967C14.9166 4.17971 11.8199 1.08301 7.99992 1.08301ZM7.09932 5.01639C7.09932 5.51345 7.50227 5.91639 7.99932 5.91639H7.99999C8.49705 5.91639 8.89999 5.51345 8.89999 5.01639C8.89999 4.51933 8.49705 4.11639 7.99999 4.11639H7.99932C7.50227 4.11639 7.09932 4.51933 7.09932 5.01639ZM7.99998 11.8306C7.58576 11.8306 7.24998 11.4948 7.24998 11.0806V7.29627C7.24998 6.88206 7.58576 6.54627 7.99998 6.54627C8.41419 6.54627 8.74998 6.88206 8.74998 7.29627V11.0806C8.74998 11.4948 8.41419 11.8306 7.99998 11.8306Z"
                                                    fill="#F04438" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                @error('quantity_sold')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Rate Per Brick --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Rate Per Brick <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="rate_per_brick"
                                        value="{{ old('rate_per_brick', $sale->rate_per_brick ?? '') }}"
                                        placeholder="e.g. 12.00" min="0" step="any" @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                                'rate_per_brick'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                                'rate_per_brick'),
                                        ])>
                                    @if ($errors->has('rate_per_brick'))
                                        <span class="absolute top-1/2 right-3.5 -translate-y-1/2">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M2.58325 7.99967C2.58325 5.00813 5.00838 2.58301 7.99992 2.58301C10.9915 2.58301 13.4166 5.00813 13.4166 7.99967C13.4166 10.9912 10.9915 13.4163 7.99992 13.4163C5.00838 13.4163 2.58325 10.9912 2.58325 7.99967ZM7.99992 1.08301C4.17995 1.08301 1.08325 4.17971 1.08325 7.99967C1.08325 11.8196 4.17995 14.9163 7.99992 14.9163C11.8199 14.9163 14.9166 11.8196 14.9166 7.99967C14.9166 4.17971 11.8199 1.08301 7.99992 1.08301ZM7.09932 5.01639C7.09932 5.51345 7.50227 5.91639 7.99932 5.91639H7.99999C8.49705 5.91639 8.89999 5.51345 8.89999 5.01639C8.89999 4.51933 8.49705 4.11639 7.99999 4.11639H7.99932C7.50227 4.11639 7.09932 4.51933 7.09932 5.01639ZM7.99998 11.8306C7.58576 11.8306 7.24998 11.4948 7.24998 11.0806V7.29627C7.24998 6.88206 7.58576 6.54627 7.99998 6.54627C8.41419 6.54627 8.74998 6.88206 8.74998 7.29627V11.0806C8.74998 11.4948 8.41419 11.8306 7.99998 11.8306Z"
                                                    fill="#F04438" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                @error('rate_per_brick')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Total (auto-calculated) --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Total <span class="text-gray-400 text-xs font-normal">(auto-calculated)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="total" value="{{ old('total', $sale->total ?? '') }}"
                                        placeholder="Auto-calculated" min="0" step="any" readonly
                                        @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30 cursor-not-allowed opacity-75',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                                'total'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                                'total'),
                                        ])>
                                </div>
                                @error('total')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Sale Date --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Sale Date <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="date" name="sale_date"
                                        value="{{ old('sale_date', isset($sale->sale_date) ? \Carbon\Carbon::parse($sale->sale_date)->format('Y-m-d') : '') }}"
                                        @class([
                                            'dark:bg-dark-900 shadow-theme-xs h-10 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:text-white/90',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                                'sale_date'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                                'sale_date'),
                                        ])>
                                    @if ($errors->has('sale_date'))
                                        <span class="absolute top-1/2 right-3.5 -translate-y-1/2">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M2.58325 7.99967C2.58325 5.00813 5.00838 2.58301 7.99992 2.58301C10.9915 2.58301 13.4166 5.00813 13.4166 7.99967C13.4166 10.9912 10.9915 13.4163 7.99992 13.4163C5.00838 13.4163 2.58325 10.9912 2.58325 7.99967ZM7.99992 1.08301C4.17995 1.08301 1.08325 4.17971 1.08325 7.99967C1.08325 11.8196 4.17995 14.9163 7.99992 14.9163C11.8199 14.9163 14.9166 11.8196 14.9166 7.99967C14.9166 4.17971 11.8199 1.08301 7.99992 1.08301ZM7.09932 5.01639C7.09932 5.51345 7.50227 5.91639 7.99932 5.91639H7.99999C8.49705 5.91639 8.89999 5.51345 8.89999 5.01639C8.89999 4.51933 8.49705 4.11639 7.99999 4.11639H7.99932C7.50227 4.11639 7.09932 4.51933 7.09932 5.01639ZM7.99998 11.8306C7.58576 11.8306 7.24998 11.4948 7.24998 11.0806V7.29627C7.24998 6.88206 7.58576 6.54627 7.99998 6.54627C8.41419 6.54627 8.74998 6.88206 8.74998 7.29627V11.0806C8.74998 11.4948 8.41419 11.8306 7.99998 11.8306Z"
                                                    fill="#F04438" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                @error('sale_date')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div
                        class="flex items-center justify-end gap-3 border-t border-gray-100 px-5 py-4 sm:px-6 dark:border-gray-800">
                        <a href="{{ route('sales.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            Save Sale
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <script>
        const qty = document.querySelector('input[name="quantity_sold"]');
        const rate = document.querySelector('input[name="rate_per_brick"]');
        const total = document.querySelector('input[name="total"]');

        function calcTotal() {
            const q = parseFloat(qty.value) || 0;
            const r = parseFloat(rate.value) || 0;
            total.value = (q * r).toFixed(2);
        }

        qty.addEventListener('input', calcTotal);
        rate.addEventListener('input', calcTotal);
    </script>
@endsection
