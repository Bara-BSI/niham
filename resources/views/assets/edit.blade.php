<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Asset') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="mx-auto max-w-4xl px-3 sm:px-6 lg:px-8">
            <div class="glass-card p-6">
                <form method="POST" action="{{ route('assets.update', $asset) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Image Upload (Edit) -->
                    @php
                        $firstAttachment = $asset->attachments;
                        $previewPath = $firstAttachment
                            ? asset('storage/'.$firstAttachment->path)
                            : null;
                    @endphp

                    <div x-data='@json(["previewUrl" => $previewPath])' class="m-8">
                        <x-input-label for="attachment" :value="__('Asset Image')" />

                        <input
                            id="attachment"
                            name="attachment"
                            type="file"
                            accept="image/*"
                            @change="previewUrl = $event.target.files.length 
                                ? URL.createObjectURL($event.target.files[0]) 
                                : previewUrl"
                            class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0 file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-accent hover:file:bg-indigo-100"
                        />

                        <x-input-error :messages="$errors->get('attachment')" class="mt-2" />

                        <!-- Preview -->
                        <div class="mt-4" x-show="previewUrl">
                            <p class="text-sm text-gray-600 mb-2">{{ __('Current / New Preview') }}</p>
                            <img :src="previewUrl" alt="Preview" class="max-h-24 rounded-md border border-gray-200 shadow-sm">
                        </div>

                        <!-- AI Scan -->
                        <div class="mt-4" x-data="{ scanning: false, error: null, success: null }">
                            <x-primary-button type="button" 
                                @click="
                                    if(document.getElementById('attachment').files.length === 0) { 
                                        error = 'Please select a new image first'; 
                                        success = null;
                                        return; 
                                    }
                                    error = null;
                                    success = null;
                                    scanning = true;
                                    let formData = new FormData();
                                    formData.append('image', document.getElementById('attachment').files[0]);
                                    formData.append('_token', '{{ csrf_token() }}');
                                    
                                    fetch('{{ route('assets.ocr-scan') }}', {
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        scanning = false;
                                        if(data.error) throw new Error(data.error);
                                        
                                        if(data.extracted.asset_name) document.getElementById('name').value = data.extracted.asset_name;
                                        if(data.extracted.serial_number) document.getElementById('serial_number').value = data.extracted.serial_number;
                                        if(data.extracted.brand) document.getElementById('vendor').value = data.extracted.brand;
                                        
                                        success = 'Scan completed successfully! Please verify the extracted details.';
                                    })
                                    .catch(err => {
                                        scanning = false;
                                        error = err.message || 'Failed to scan image';
                                    });
                                "
                                x-bind:disabled="scanning"
                            >
                                <span x-show="!scanning">✨ AI Scan Details</span>
                                <span x-show="scanning" x-cloak>Scanning... ⏳</span>
                            </x-primary-button>
                            <p x-show="error" class="text-sm text-red-600 mt-2" x-text="error" x-cloak></p>
                            <p x-show="success" class="text-sm text-green-600 mt-2" x-text="success" x-cloak></p>
                            <p class="text-xs text-gray-500 mt-1 italic">Disclaimer: AI scans may occasionally be inaccurate. Always verify the auto-filled information.</p>
                        </div>
                    </div>

                    <!-- Responsive Two-Column Layout -->
                    <div class="m-8 grid grid-cols-2 gap-1 justify-evenly">

                        <!-- Left Column -->
                        <div class="col-span-2 md:col-span-1">


                            <!-- Tag -->
                            <div x-data="{ tag: '' }" class="">
                                <x-input-label for="tag" :value="__('Asset Tag')" />
                                <input
                                    list="tag-options"
                                    {{-- x-model="tag" --}}
                                    name="tag"
                                    id="tag"
                                    value="{{ old('tag', $asset->tag ?? '') }}"
                                    autocomplete="off"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-accent focus:border-accent"
                                    placeholder="Select or type a new tag"
                                    required
                                />
                                <datalist id="tag-options">
                                    @foreach ($existingTags as $existingTag)
                                        <option value="{{ $existingTag->tag }}"></option>
                                    @endforeach
                                </datalist>
                                <x-input-error :messages="$errors->get('tag')" class="mt-2" />
                            </div>

                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Asset Name')" />
                                <x-text-input
                                    id="name"
                                    name="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    value="{{ old('name', $asset->name) }}"
                                />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Category -->
                            <div>
                                <x-input-label for="category_id" :value="__('Category')" />
                                <select
                                    id="category_id"
                                    name="category_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent"
                                    required
                                >
                                    @foreach ($categories as $category)
                                        @if (old('category_id', $asset->category_id) == $category->id)
                                            <option value="{{ $category->id }}" selected>
                                                {{ $category->name }}{{ Auth::user()->isSuperAdmin() && $category->property ? ' - ' . $category->property->name : '' }}
                                            </option>
                                        @else
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}{{ Auth::user()->isSuperAdmin() && $category->property ? ' - ' . $category->property->name : '' }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                            </div>

                            @if (Auth::user()->hasExecutiveOversight())
                                <!-- Executive can choose any department -->
                                <div>
                                    <x-input-label for="department_id" :value="__('Department')" />
                                    <select
                                        id="department_id"
                                        name="department_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-accent focus:border-accent"
                                    >
                                        <option value="">—</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                @selected(old('department_id', $asset->department_id) == $department->id)>
                                                {{ $department->name }}{{ Auth::user()->isSuperAdmin() && $department->property ? ' - ' . $department->property->name : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                                </div>
                            @else
                                <!-- Non-executive: locked to their department -->
                                <div>
                                    <x-input-label for="department_id" :value="__('Department')" />
                                    <select
                                        id="department_id"
                                        name="department_id"
                                        disabled
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-accent focus:border-accent text-gray-500"
                                    >
                                        <option value="{{ old('department_id', $asset->department_id) }}" selected>
                                            {{ old('department_id', $asset->department->name) }}
                                        </option>
                                    </select>
                                    <!-- Hidden input ensures value is still submitted -->
                                    <input type="hidden" name="department_id" value="{{ old('department_id', $asset->department_id) }}">
                                    <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                                </div>
                            @endif

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select
                                    id="status"
                                    name="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-accent focus:border-accent"
                                >
                                    <option value="in_service" {{ old('status', $asset->status) == 'in_service' ? 'selected' : '' }}>In Service</option>
                                    <option value="out_of_service" {{ old('status', $asset->status) == 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                                    <option value="disposed" {{ old('status', $asset->status) == 'disposed' ? 'selected' : '' }}>Disposed</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-span-2 md:col-span-1">

                            <!-- Serial Number -->
                            <div>
                                <x-input-label for="serial_number" :value="__('Serial Number')" />
                                <x-text-input
                                    id="serial_number"
                                    name="serial_number"
                                    type="text"
                                    class="mt-1 block w-full"
                                    value="{{ old('serial_number', $asset->serial_number) }}"
                                />
                                <x-input-error :messages="$errors->get('serial_number')" class="mt-2" />
                            </div>

                            <!-- Purchase Date -->
                            <div>
                                <x-input-label for="purchase_date" :value="__('Purchase Date')" />
                                <x-text-input
                                    id="purchase_date"
                                    name="purchase_date"
                                    type="date"
                                    class="mt-1 block w-full"
                                    :value="old('purchase_date', $asset->purchase_date?->format('Y-m-d'))"
                                />
                                <x-input-error :messages="$errors->get('purchase_date')" class="mt-2" />
                            </div>

                            {{-- Warranty --}}
                            <div>
                                <x-input-label for="warranty_duration" :value="__('Warranty Duration')" />
                                @php
                                    $warranty_duration = 'none';

                                    if ($asset->warranty_date) {
                                        $purchase = $asset->purchase_date;
                                        $warranty = $asset->warranty_date;

                                        $diffInMonths = $purchase->diffInMonths($warranty);

                                        if ($diffInMonths == 6) {
                                            $warranty_duration = '6m';
                                        } elseif ($diffInMonths == 12) {
                                            $warranty_duration = '1y';
                                        } elseif ($diffInMonths == 24) {
                                            $warranty_duration = '2y';
                                        } elseif ($diffInMonths == 36) {
                                            $warranty_duration = '3y';
                                        }
                                    }
                                @endphp
                                {{-- {{ $warranty_duration }} --}}

                                <select name="warranty_duration" id="warranty_duration"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="none" {{  $warranty_duration == 'none' ? 'selected' : '' }}>None</option>
                                    <option value="6m" {{  $warranty_duration == '6m' ? 'selected' : '' }}>6 Months</option>
                                    <option value="1y" {{  $warranty_duration == '1y' ? 'selected' : '' }}>1 Year</option>
                                    <option value="2y" {{  $warranty_duration == '2y' ? 'selected' : '' }}>2 Years</option>
                                    <option value="3y" {{  $warranty_duration == '3y' ? 'selected' : '' }}>3 Years</option>
                                </select>

                                <x-input-error :messages="$errors->get('warranty_duration')" class="mt-2" />
                            </div>

                            <!-- Purchase Cost -->
                            <div>
                                <x-input-label for="purchase_cost" :value="__('Purchase Cost')" />
                                <x-text-input
                                    id="purchase_cost"
                                    name="purchase_cost"
                                    type="number"
                                    step="0.01"
                                    class="mt-1 block w-full"
                                    value="{{ old('purchase_cost', $asset->purchase_cost) }}"
                                />
                                <x-input-error :messages="$errors->get('purchase_cost')" class="mt-2" />
                            </div>

                            <!-- Vendor -->
                            <div>
                                <x-input-label for="vendor" :value="__('Vendor')" />
                                <x-text-input
                                    id="vendor"
                                    name="vendor"
                                    type="text"
                                    class="mt-1 block w-full"
                                    value="{{ old('vendor', $asset->vendor) }}"
                                />
                                <x-input-error :messages="$errors->get('vendor')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                    {{-- Remarks --}}
                    <div 
                        x-data="{ count: {{ strlen(old('remarks', $asset->remarks ?? '')) }} }"
                        class="m-8"
                    >
                        <x-input-label for="remarks" :value="__('Remarks')" />

                        <textarea
                            id="remarks"
                            name="remarks"
                            maxlength="120"
                            rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            placeholder="Add a short note (max 120 chars)"
                            x-on:input="count = $event.target.value.length"
                        >{{ old('remarks', $asset->remarks) }}</textarea>

                        <div class="flex justify-between mt-1 text-sm text-gray-500">
                            <span>Max 120 characters</span>
                            <span x-text="count + '/120'"></span>
                        </div>

                        <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-between items-center">
                        <!-- Back Button -->
                        <div class="mt-6 flex justify-start">
                            <x-secondary-button onclick="window.history.back()">
                                <x-heroicon-s-arrow-left class="w-4 h-4 mr-2" />
                                {{ __('Back') }}
                            </x-secondary-button>

                        </div>
                        <!-- Submit Button -->
                        <div class="mt-6 flex justify-end">
                            <x-primary-button>
                                <x-heroicon-s-bookmark class="w-4 h-4 mr-2" />
                                {{ __('Save Asset') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
