@props(['asset'])

<div x-data="{ openUpdateModal: false }" class="inline-flex w-full sm:w-auto">
    <!-- Trigger slot -->
    <div @click="openUpdateModal = true; $event.preventDefault();" class="w-full sm:w-auto inline-flex">
        {{ $trigger }}
    </div>

    <!-- Modal -->
    <template x-teleport="body">
        <div x-show="openUpdateModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
            <div class="bg-white/90 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl w-full max-w-lg p-6 relative" @click.outside="openUpdateModal = false">
                <button @click="openUpdateModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <x-heroicon-s-x-mark class="w-5 h-5"/>
                </button>
                
                <h2 class="text-lg font-bold text-gray-900 mb-4">Update Asset Status</h2>
                
                <form action="{{ route('assets.update', $asset) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <input type="hidden" name="name" value="{{ $asset->name }}">
                    <input type="hidden" name="tag" value="{{ $asset->tag }}">
                    <input type="hidden" name="category_id" value="{{ $asset->category_id }}">
                    <input type="hidden" name="department_id" value="{{ $asset->department_id }}">
                    <input type="hidden" name="property_id" value="{{ $asset->property_id }}">
                    <input type="hidden" name="condition" value="{{ $asset->condition ?? 'good' }}">
                    
                    <div class="mb-4">
                        <x-input-label for="modal_status" :value="__('Status')" />
                        <select id="modal_status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="in_service" {{ $asset->status == 'in_service' ? 'selected' : '' }}>In Service</option>
                            <option value="out_of_service" {{ $asset->status == 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                            <option value="disposed" {{ $asset->status == 'disposed' ? 'selected' : '' }}>Disposed</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="modal_remarks" :value="__('Remarks')" />
                        <textarea id="modal_remarks" name="remarks" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ $asset->remarks }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <x-secondary-button type="button" @click="openUpdateModal = false">Cancel</x-secondary-button>
                        <x-primary-button type="submit">Save Changes</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
