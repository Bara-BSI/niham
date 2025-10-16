<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Create Asset') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('assets.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Image Upload -->
                    <div x-data="{ previewUrl: null }" class="m-8">
                        <x-input-label for="attachment" :value="__('Asset Image')" />

                        <input
                            id="attachment"
                            name="attachment"
                            type="file"
                            accept="image/*"
                            @change="previewUrl = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : null"
                            class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0 file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                        />

                        <x-input-error :messages="$errors->get('attachment')" class="mt-2" />

                        <!-- Preview -->
                        <template x-if="previewUrl">
                            <div class="mt-4">
                                <p class="text-sm text-gray-600 mb-2">{{ __('Preview') }}</p>
                                <img :src="previewUrl" alt="Preview" class="max-h-24 rounded-md border border-gray-200 shadow-sm">
                            </div>
                        </template>
                    </div>

                    <!-- Responsive Two-Column Layout -->
                    <div class="m-8 grid grid-cols-2 gap-1 justify-evenly">

                        <!-- Left Column -->
                        <div class="col-span-2 md:col-span-1">
                            <!-- Tag -->
                            <div x-data="{ tag: '' }">
                                <x-input-label for="tag" :value="__('Asset Tag')" />
                                <input
                                    list="tag-options"
                                    x-model="tag"
                                    name="tag"
                                    id="tag"
                                    autocomplete="off"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Select or type a new tag"
                                    required
                                    value="{{ old('tag') }}"
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
                                    value="{{ old('name') }}"
                                />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Category -->
                            <div>
                                <x-input-label for="category_id" :value="__('Category')" />
                                <select
                                    id="category_id"
                                    name="category_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    required
                                >
                                    @foreach ($categories as $category)
                                        @if (old('category_id') == $category->id)
                                            <option value="{{ $category->id }}" selected>
                                                {{ $category->name }}
                                            </option>
                                        @else
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                            </div>

                            @if (Auth::user()->inDept('EXE'))
                                <!-- Departments -->
                                <div>
                                    <x-input-label for="department_id" :value="__('Department')" />
                                    <select
                                        id="department_id"
                                        name="department_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    >
                                        <option value="">—</option>
                                        @foreach ($departments as $department)
                                            @if (old('department_id') == $department->id)
                                                <option value="{{ $department->id }}" selected>
                                                    {{ $department->name }}
                                                </option>
                                            @else
                                                <option value="{{ $department->id }}">
                                                    {{ $department->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                                </div>
                            @else
                                <!-- Departments -->
                                <div>
                                    <x-input-label for="department_id" :value="__('Department')" />
                                    <select
                                        id="department_id"
                                        name="department_id"
                                        disabled
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-500"
                                    >
                                        <option value="{{ Auth::user()->department->id }}" selected>{{ Auth::user()->department->name }}</option>
                                    </select>
                                    <input type="hidden" name="department_id" value="{{ Auth::user()->department->id }}">
                                    <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                                </div>
                            @endif

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select
                                    id="status"
                                    name="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                    <option value="in_service" {{ old('status') == 'in_service' ? 'selected' : '' }}>In Service</option>
                                    <option value="out_of_service" {{ old('status') == 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                                    <option value="disposed" {{ old('status') == 'disposed' ? 'selected' : '' }}>Disposed</option>
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
                                    value="{{ old('serial_number') }}"
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
                                    :value="old('purchase_date')"
                                />
                                <x-input-error :messages="$errors->get('purchase_date')" class="mt-2" />
                            </div>

                            {{-- Warranty --}}
                            <div>
                                <x-input-label for="warranty_duration" :value="__('Warranty Duration')" />
                                <select id="warranty_duration" name="warranty_duration"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="none" {{ old('warranty_duration') == 'none' ? 'selected' : '' }}>None</option>
                                    <option value="6m" {{ old('warranty_duration') == '6m' ? 'selected' : '' }}>6 Months</option>
                                    <option value="1y" {{ old('warranty_duration') == '1y' ? 'selected' : '' }}>1 Year</option>
                                    <option value="2y" {{ old('warranty_duration') == '2y' ? 'selected' : '' }}>2 Years</option>
                                    <option value="3y" {{ old('warranty_duration') == '3y' ? 'selected' : '' }}>3 Years</option>
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
                                    value="{{ old('purchase_cost') }}"
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
                                    value="{{ old('vendor') }}"
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
                                >{{ old('remarks') }}</textarea>

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
