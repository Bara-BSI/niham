<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Role') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-sm shadow-md rounded-xl border border-white/30 p-6">
                <form method="POST" action="{{ route('roles.update', $role) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Role Name')" />
                        <x-text-input
                            id="name"
                            name="name"
                            type="text"
                            class="mt-1 block w-full"
                            required
                            value="{{ old('name', $role->name) }}"
                        />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="can_create" value="1" {{ old('can_create', $role->can_create ?? false) ? 'checked' : '' }}>
                            <span class="ml-2">Create</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="can_read" value="1" {{ old('can_read', $role->can_read ?? true) ? 'checked' : '' }}>
                            <span class="ml-2">Read</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="can_update" value="1" {{ old('can_update', $role->can_update ?? false) ? 'checked' : '' }}>
                            <span class="ml-2">Update</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="can_delete" value="1" {{ old('can_delete', $role->can_delete ?? false) ? 'checked' : '' }}>
                            <span class="ml-2">Delete</span>
                        </label>
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
                                {{ __('Save Role') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
