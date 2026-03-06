<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('assets.review_data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md shadow-xl sm:rounded-2xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                <form action="{{ route('assets.import-store') }}" method="POST">
                    @csrf
                    
                    <div class="p-6 border-b border-gray-200/50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/50 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('assets.bulk_add_title') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('assets.bulk_add_desc') }}</p>
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition-all shadow-sm">
                            <x-heroicon-s-check class="w-4 h-4 mr-2" />
                            {{ __('messages.save') ?? 'Save All' }}
                        </button>
                    </div>

                    <div class="overflow-x-auto w-full p-4" x-data="{ 
                        rows: {{ count($data) }},
                        addRow() {
                            const tbody = document.getElementById('review-tbody');
                            const template = document.getElementById('row-template').content.cloneNode(true);
                            const newIndex = 'new_' + Date.now();
                            
                            let tempDiv = document.createElement('tbody');
                            tempDiv.appendChild(template);
                            let tempHTML = tempDiv.innerHTML.replace(/__INDEX__/g, newIndex);
                            tempHTML = tempHTML.replace(/__TAG__/g, 'AST-' + Math.random().toString(36).substring(2, 8).toUpperCase());
                            
                            tempDiv.innerHTML = tempHTML;
                            tbody.appendChild(tempDiv.firstElementChild);
                            this.rows++;
                        }
                    }">
                        @if (!empty($warning))
                            <div class="mb-4 bg-amber-100/60 dark:bg-amber-900/30 border border-amber-400/50 dark:border-amber-600/50 text-amber-800 dark:text-amber-200 px-4 py-3 rounded-lg relative flex items-start gap-3" role="alert">
                                <x-heroicon-o-exclamation-triangle class="w-5 h-5 flex-shrink-0 mt-0.5" />
                                <span class="text-sm">{{ $warning }}</span>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-4 bg-red-100/50 border border-red-400/50 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                                <strong class="font-bold">Oops!</strong>
                                <span class="block sm:inline">Please fix the highlighted errors below.</span>
                                <ul class="mt-2 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <table class="min-w-full divide-y divide-gray-200/50 dark:divide-gray-700/50">
                            <thead>
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tag *</th>
                                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name *</th>
                                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category *</th>
                                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Department *</th>
                                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status *</th>
                                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Model/Brand</th>
                                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Serial No.</th>
                                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Purchase Date</th>
                                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody id="review-tbody" class="divide-y divide-gray-200/50 dark:divide-gray-700/50">
                                @forelse($data as $index => $item)
                                    <tr x-data="{ showRow: true }" x-show="showRow" class="transition-all hover:bg-gray-50/50 dark:hover:bg-gray-800/50">
                                        <!-- Tag -->
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            <input type="text" name="assets[{{ $index }}][tag]" value="{{ old('assets.'.$index.'.tag', 'AST-' . strtoupper(Str::random(6))) }}" required
                                                class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-accent focus:border-accent text-sm dark:bg-gray-900/50 dark:text-white" />
                                        </td>
                                        
                                        <!-- Name -->
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            <input type="text" name="assets[{{ $index }}][name]" value="{{ old('assets.'.$index.'.name', $item['name'] ?? '') }}" required
                                                class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-accent focus:border-accent text-sm dark:bg-gray-900/50 dark:text-white" />
                                        </td>
                                        
                                        <!-- Category -->
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            <select name="assets[{{ $index }}][category_id]" required
                                                class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-accent focus:border-accent text-sm dark:bg-gray-900/50 dark:text-white">
                                                <option value="">-- Select --</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}" {{ old('assets.'.$index.'.category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        
                                        <!-- Department -->
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            <select name="assets[{{ $index }}][department_id]" required
                                                class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-accent focus:border-accent text-sm dark:bg-gray-900/50 dark:text-white">
                                                <option value="">-- Select --</option>
                                                @foreach($departments as $dept)
                                                    <!-- Auto-select department if match or fallback to user dept if admin -->
                                                    <option value="{{ $dept->id }}" {{ old('assets.'.$index.'.department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        
                                        <!-- Status -->
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            <select name="assets[{{ $index }}][status]" required
                                                class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-accent focus:border-accent text-sm dark:bg-gray-900/50 dark:text-white">
                                                <option value="in_service" {{ old('assets.'.$index.'.status', $item['status'] ?? '') == 'in_service' ? 'selected' : '' }}>In Service</option>
                                                <option value="out_of_service" {{ old('assets.'.$index.'.status', $item['status'] ?? '') == 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                                                <option value="disposed" {{ old('assets.'.$index.'.status', $item['status'] ?? '') == 'disposed' ? 'selected' : '' }}>Disposed</option>
                                            </select>
                                        </td>
                                        
                                        <!-- Brand & Model (combined to model for payload) -->
                                        @php
                                            $brandStr = isset($item['brand']) && !empty($item['brand']) ? $item['brand'] . ' ' : '';
                                            $modelStr = isset($item['model']) && !empty($item['model']) ? $item['model'] : '';
                                            $combined = trim($brandStr . $modelStr);
                                        @endphp
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            <input type="text" name="assets[{{ $index }}][model]" value="{{ old('assets.'.$index.'.model', $combined) }}"
                                                class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-accent focus:border-accent text-sm dark:bg-gray-900/50 dark:text-white" />
                                        </td>
                                        
                                        <!-- Serial Number -->
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            <input type="text" name="assets[{{ $index }}][serial_number]" value="{{ old('assets.'.$index.'.serial_number', $item['serial_number'] ?? '') }}"
                                                class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-accent focus:border-accent text-sm dark:bg-gray-900/50 dark:text-white" />
                                        </td>
                                        
                                        <!-- Purchase Date -->
                                        <td class="px-2 py-3 whitespace-nowrap">
                                            <input type="date" name="assets[{{ $index }}][purchase_date]" value="{{ old('assets.'.$index.'.purchase_date', $item['purchase_date'] ?? '') }}"
                                                class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-accent focus:border-accent text-sm dark:bg-gray-900/50 dark:text-white" />
                                        </td>
                                        
                                        <!-- Action -->
                                        <td class="px-2 py-3 whitespace-nowrap text-center">
                                            <!-- Remove row frontend only. The backend ignores it if it is missing from array. -->
                                            <button type="button" @click="$el.closest('tr').remove(); rows--;" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors p-2 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                                <x-heroicon-o-trash class="w-5 h-5" />
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No data could be extracted.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <template id="row-template">
                            <tr class="transition-all hover:bg-gray-50/50 dark:hover:bg-gray-800/50">
                                <td class="px-2 py-3 whitespace-nowrap">
                                    <input type="text" name="assets[__INDEX__][tag]" value="__TAG__" required
                                        class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-accent focus:border-accent text-sm dark:bg-gray-900/50 dark:text-white" />
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap">
                                    <input type="text" name="assets[__INDEX__][name]" value="" required
                                        class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-accent focus:border-accent text-sm dark:bg-gray-900/50 dark:text-white" />
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap">
                                    <select name="assets[__INDEX__][category_id]" required
                                        class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-accent focus:border-accent text-sm dark:bg-gray-900/50 dark:text-white">
                                        <option value="">-- Select --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap">
                                    <select name="assets[__INDEX__][department_id]" required
                                        class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-accent focus:border-accent text-sm dark:bg-gray-900/50 dark:text-white">
                                        <option value="">-- Select --</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap">
                                    <select name="assets[__INDEX__][status]" required
                                        class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-accent focus:border-accent text-sm dark:bg-gray-900/50 dark:text-white">
                                        <option value="in_service" selected>In Service</option>
                                        <option value="out_of_service">Out of Service</option>
                                        <option value="disposed">Disposed</option>
                                    </select>
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap">
                                    <input type="text" name="assets[__INDEX__][model]" value=""
                                        class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-accent focus:border-accent text-sm dark:bg-gray-900/50 dark:text-white" />
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap">
                                    <input type="text" name="assets[__INDEX__][serial_number]" value=""
                                        class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-accent focus:border-accent text-sm dark:bg-gray-900/50 dark:text-white" />
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap">
                                    <input type="date" name="assets[__INDEX__][purchase_date]" value=""
                                        class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-accent focus:border-accent text-sm dark:bg-gray-900/50 dark:text-white" />
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap text-center">
                                    <button type="button" @click="$el.closest('tr').remove(); rows--;" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors p-2 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </td>
                            </tr>
                        </template>

                        <div class="mt-6 border-t border-gray-200/50 dark:border-gray-700/50 pt-4 flex justify-between items-center">
                            <!-- Back Button -->
                            <div class="mt-6 flex justify-start">
                                <x-secondary-button onclick="window.history.back()">
                                    <x-heroicon-s-arrow-left class="w-4 h-4 mr-2" />
                                    {{ __('messages.back') }}
                                </x-secondary-button>
                            </div>
                            <p class="mt-6 flex justify-end text-sm font-medium text-gray-500 dark:text-gray-400">Total Rows: <span x-text="rows" class="text-gray-900 dark:text-gray-100"></span></p>
                            <button type="button" @click="addRow" class="mt-6 flex justify-end inline-flex items-center px-4 py-2 bg-white/90 dark:bg-gray-800/90 backdrop-blur-md border border-gray-200/50 dark:border-gray-700/50 rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 transition-all shadow-sm">
                                <x-heroicon-s-plus class="w-4 h-4 mr-2" />
                                Add Row
                            </button>
                            
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
