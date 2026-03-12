@extends('partials.app', ['title' => $title])

@section('content')
    <div class="col-span-12 space-y-6 xl:col-span-12">
        <div class="p-4 mx-auto max-w-screen-2xl md:p-6">
            <x-toast-error field="materials" />
            <x-toast />

            {{-- PAGE HEADER --}}
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">New Production Batch</h2>
                    <p class="mt-0.5 text-theme-sm text-gray-500 dark:text-gray-400">Record a new brick production batch</p>
                </div>
                <a href="{{ route('production_batches.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Back to Batches
                </a>
            </div>

            <form method="POST" action="{{ route('production_batches.store') }}">
                @csrf
                <input type="hidden" value="{{ @$batch->id }}" name="updateId">
                {{-- ── BATCH INFO CARD ── --}}
                {{-- ── BATCH INFO CARD ── --}}
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mb-5">

                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Batch Information</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">General details about this production run.
                        </p>
                    </div>

                    <div class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            {{-- Batch No (auto) --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Batch No
                                </label>
                                <input type="text" value="{{ $batchNo }}" readonly
                                    class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 cursor-not-allowed dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                <input type="hidden" name="batch_no" value="{{ $batchNo }}">
                            </div>

                            {{-- Production Date --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Production Date <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="date" name="production_date"
                                        value="{{ old('production_date', isset($batch->production_date) ? \Carbon\Carbon::parse($batch->production_date)->format('Y-m-d') : '') }}"
                                        @class([
                                            'dark:bg-dark-900 shadow-theme-xs h-10 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:text-white/90',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                                'production_date'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                                'production_date'),
                                        ])>
                                    @if ($errors->has('production_date'))
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
                                @error('production_date')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Bricks Produced --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Bricks Produced <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="bricks_produced"
                                        value="{{ old('bricks_produced', $batch?->bricks_produced ?? '') }}"
                                        placeholder="e.g. 4800" min="1" step="any" @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                                'bricks_produced'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                                'bricks_produced'),
                                        ])>
                                    @if ($errors->has('bricks_produced'))
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
                                @error('bricks_produced')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Bricks Wasted --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Bricks Wasted <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="bricks_wasted"
                                        value="{{ old('bricks_wasted', $batch?->bricks_wasted ?? 0) }}"
                                        placeholder="e.g. 200" min="0" step="any" @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                                'bricks_wasted'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                                'bricks_wasted'),
                                        ])>
                                    @if ($errors->has('bricks_wasted'))
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
                                @error('bricks_wasted')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Waste Reason (full width) --}}
                            <div class="sm:col-span-2">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Waste Reason
                                    <span class="text-xs text-gray-400 font-normal">(optional)</span>
                                </label>
                                <div class="relative">
                                    <textarea name="waste_reason" rows="2" placeholder="e.g. Over-fired, cracked due to rain, handling damage..."
                                        @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800' => $errors->has(
                                                'waste_reason'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                                'waste_reason'),
                                        ])>{{ old('waste_reason', $batch?->waste_reason ?? '') }}</textarea>
                                </div>
                                @error('waste_reason')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── MATERIALS CARD ── --}}
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mb-5">

                    <div class="px-5 py-4 sm:px-6 sm:py-5 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Materials Used</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add each material consumed in this
                                batch.</p>
                        </div>
                        <button type="button" id="addMaterialRow"
                            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-3.5 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Add Material
                        </button>
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-800">

                        {{-- Table Header --}}
                        <div
                            class="hidden sm:grid grid-cols-12 gap-3 px-5 py-3 bg-gray-50 dark:bg-white/[0.02] text-xs font-medium text-gray-500 uppercase tracking-wide">
                            <div class="col-span-4">Material</div>
                            <div class="col-span-3">Quantity Used</div>
                            <div class="col-span-3">Rate at Time</div>
                            <div class="col-span-1">Total</div>
                            <div class="col-span-1"></div>
                        </div>

                        {{-- Material Rows --}}
                        <div id="materialRows" class="divide-y divide-gray-100 dark:divide-gray-800">
                            {{-- rows injected by JS --}}
                        </div>

                        {{-- Empty state --}}
                        <div id="emptyState" class="py-10 text-center text-sm text-gray-400">
                            No materials added yet. Click <strong>Add Material</strong> to begin.
                        </div>

                        {{-- Material subtotal --}}
                        <div
                            class="flex items-center justify-end gap-3 px-5 py-3 border-t border-gray-100 dark:border-gray-800">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Total Material Cost:</span>
                            <span id="totalMaterialDisplay"
                                class="text-sm font-semibold text-gray-800 dark:text-white/90">Rs 0.00</span>
                            <input type="hidden" name="total_material_cost" id="total_material_cost"
                                value="{{ old('total_material_cost', 0) }}">
                        </div>

                    </div>
                </div>
                <input type="hidden" id="materialRateUrl"
                    value="{{ route('materials.rate', ['material' => '__ID__']) }}">

                {{-- ── COSTS CARD ── --}}
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] mb-5">

                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Cost Breakdown</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Labor, fuel, and computed totals.</p>
                    </div>

                    <div class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            {{-- Labor Cost --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Labor Cost <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="labor_cost" id="labor_cost"
                                        value="{{ old('labor_cost', $batch?->labor_cost ?? '') }}"
                                        placeholder="e.g. 5000.00" min="0" step="any"
                                        @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                                'labor_cost'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                                'labor_cost'),
                                        ])>
                                    @if ($errors->has('labor_cost'))
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
                                @error('labor_cost')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Fuel Cost --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Fuel Cost <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="fuel_cost" id="fuel_cost"
                                        value="{{ old('fuel_cost', $batch?->fuel_cost ?? '') }}"
                                        placeholder="e.g. 3000.00" min="0" step="any"
                                        @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                                'fuel_cost'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                                'fuel_cost'),
                                        ])>
                                    @if ($errors->has('fuel_cost'))
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
                                @error('fuel_cost')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- Other Cost --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Other Cost
                                    <span class="text-xs text-gray-400 font-normal">(optional)</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="other_cost" id="other_cost"
                                        value="{{ old('other_cost', $batch?->other_cost ?? 0) }}"
                                        placeholder="e.g. 500.00" min="0" step="any"
                                        @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                                'other_cost'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                                'other_cost'),
                                        ])>
                                    @if ($errors->has('other_cost'))
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
                                @error('other_cost')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Other Cost Note --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Other Cost Note
                                    <span class="text-xs text-gray-400 font-normal">(optional)</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="other_cost_note"
                                        value="{{ old('other_cost_note', $batch?->other_cost_note ?? '') }}"
                                        placeholder="e.g. Generator repair, transport fee..." @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                                'other_cost_note'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                                'other_cost_note'),
                                        ])>
                                </div>
                                @error('other_cost_note')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Total Expense Cost (auto) --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Total Expense Cost
                                    <span class="text-xs text-gray-400 font-normal">(labor + fuel + other)</span>
                                </label>
                                <input type="number" id="total_expense_display" readonly placeholder="0.00"
                                    class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-500 cursor-not-allowed dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                <input type="hidden" name="total_expense_cost" id="total_expense_cost"
                                    value="{{ old('total_expense_cost', 0) }}">
                            </div>

                            {{-- Total Cost (auto) --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Total Cost
                                    <span class="text-xs text-gray-400 font-normal">(materials + expenses)</span>
                                </label>
                                <input type="number" id="total_cost_display" readonly placeholder="0.00"
                                    class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm font-semibold text-green-600 cursor-not-allowed dark:border-gray-700 dark:bg-gray-900">
                                <input type="hidden" name="total_cost" id="total_cost"
                                    value="{{ old('total_cost', 0) }}">
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('production_batches.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Save Batch
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        const materials = @json($materials);
        let rowIndex = 0;

        const materialRows = document.getElementById('materialRows');
        const emptyState = document.getElementById('emptyState');
        const addBtn = document.getElementById('addMaterialRow');



        function toggleEmpty() {
            emptyState.style.display = materialRows.children.length === 0 ? 'block' : 'none';
        }

        function recalcRow(row) {
            const qty = parseFloat(row.querySelector('.qty').value) || 0;
            const rate = parseFloat(row.querySelector('.rate').value) || 0;
            const total = qty * rate;
            row.querySelector('.row-total-display').textContent = 'Rs ' + total.toLocaleString('en-PK', {
                minimumFractionDigits: 2
            });
            row.querySelector('.row-total-input').value = total.toFixed(2);
            recalcGrandTotals();
        }

        function recalcGrandTotals() {
            let materialTotal = 0;
            document.querySelectorAll('.row-total-input').forEach(i => {
                materialTotal += parseFloat(i.value) || 0;
            });

            const labor = parseFloat(document.getElementById('labor_cost').value) || 0;
            const fuel = parseFloat(document.getElementById('fuel_cost').value) || 0;
            const other = parseFloat(document.getElementById('other_cost').value) || 0;
            const expenseTotal = labor + fuel + other;
            const grandTotal = materialTotal + expenseTotal;

            document.getElementById('totalMaterialDisplay').textContent = 'Rs ' + materialTotal.toLocaleString('en-PK', {
                minimumFractionDigits: 2
            });
            document.getElementById('total_expense_display').value = expenseTotal.toFixed(2);
            document.getElementById('total_cost_display').value = grandTotal.toFixed(2);

            document.getElementById('total_material_cost').value = materialTotal.toFixed(2);
            document.getElementById('total_expense_cost').value = expenseTotal.toFixed(2);
            document.getElementById('total_cost').value = grandTotal.toFixed(2);
        }

        function buildOptions(selectedId = '') {
            let opts = `<option value="">Select Material</option>`;
            materials.forEach(m => {
                opts +=
                    `<option value="${m.id}" ${m.id == selectedId ? 'selected' : ''}>${m.name} (${m.unit})</option>`;
            });
            return opts;
        }
        const rateUrlTemplate = document.getElementById('materialRateUrl').value;

        function fetchAndSetRate(row, materialId, rateInput) {
            if (!materialId) {
                rateInput.value = '';
                recalcRow(row);
                return;
            }
            const url = rateUrlTemplate.replace('__ID__', materialId);
            fetch(url)
                .then(r => r.json())
                .then(data => {
                    rateInput.value = data.avg_rate ?? '';
                    recalcRow(row);
                })
                .catch(() => {});
        }

        function addRow(data = {}) {
            const idx = rowIndex++;
            const row = document.createElement('div');
            row.className = 'material-row grid grid-cols-12 gap-3 items-start px-5 py-4';
            row.dataset.index = idx;

            row.innerHTML = `
            <div class="col-span-12 sm:col-span-4">
                <label class="mb-1 block text-xs text-gray-500 sm:hidden">Material</label>
                <select name="materials[${idx}][material_id]"
                    class="material-select shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    ${buildOptions(data.material_id)}
                </select>
            </div>

            <div class="col-span-5 sm:col-span-3">
                <label class="mb-1 block text-xs text-gray-500 sm:hidden">Quantity</label>
                <input type="number" name="materials[${idx}][quantity_used]"
                    value="${data.quantity_used ?? ''}"
                    placeholder="Qty" min="0.01" step="any"
                    class="qty shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <div class="col-span-5 sm:col-span-3">
                <label class="mb-1 block text-xs text-gray-500 sm:hidden">Rate</label>
                <input type="number" name="materials[${idx}][rate_at_time]"
                    value="${data.rate_at_time ?? ''}"
                    placeholder="Rate" min="0" step="any"
                    class="rate shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            <div class="col-span-10 sm:col-span-1 flex items-center">
                <span class="row-total-display text-sm font-medium text-green-600">Rs 0.00</span>
                <input type="hidden" name="materials[${idx}][total_cost]" class="row-total-input" value="0">
            </div>

            <div class="col-span-2 sm:col-span-1 flex items-center justify-end">
                <button type="button"
                    class="remove-row inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
        `;

            const selectEl = row.querySelector('.material-select');
            const rateEl = row.querySelector('.rate');
            const qtyEl = row.querySelector('.qty');

            // On material change → fetch avg_rate and auto-fill rate
            selectEl.addEventListener('change', () => {
                fetchAndSetRate(row, selectEl.value, rateEl);
            });

            qtyEl.addEventListener('input', () => recalcRow(row));
            rateEl.addEventListener('input', () => recalcRow(row));

            row.querySelector('.remove-row').addEventListener('click', () => {
                row.remove();
                toggleEmpty();
                recalcGrandTotals();
            });

            // If data was passed (edit/old restore) — rate already set, just recalc
            if (data.quantity_used && data.rate_at_time) recalcRow(row);

            materialRows.appendChild(row);
            toggleEmpty();
        }

        addBtn.addEventListener('click', () => addRow());

        document.getElementById('labor_cost').addEventListener('input', recalcGrandTotals);
        document.getElementById('fuel_cost').addEventListener('input', recalcGrandTotals);
        document.getElementById('other_cost').addEventListener('input', recalcGrandTotals);
        @if (old('materials'))
            @foreach (old('materials') as $i => $mat)
                addRow({
                    material_id: '{{ $mat['material_id'] ?? '' }}',
                    quantity_used: '{{ $mat['quantity_used'] ?? '' }}',
                    rate_at_time: '{{ $mat['rate_at_time'] ?? '' }}',
                });
            @endforeach
        @elseif (isset($batch) && $batch->productionMaterials->isNotEmpty())
            @foreach ($batch->productionMaterials as $mat)
                addRow({
                    material_id: '{{ $mat->material_id }}',
                    quantity_used: '{{ $mat->quantity_used }}',
                    rate_at_time: '{{ $mat->rate_at_time }}',
                });
            @endforeach
        @endif

        toggleEmpty();
    </script>
@endsection
