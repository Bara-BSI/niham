<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Role') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="mx-auto max-w-4xl px-3 sm:px-6 lg:px-8">
            <div class="glass-card p-6">
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
                    @php
                        $options = [
                            'no access' => 'No Access',
                            'view' => 'View Only',
                            'view, create' => 'View & Create',
                            'view, update' => 'View & Update',
                            'view, create, update' => 'View, Create & Update',
                            'view, create, update, delete' => 'View, Create, Update & Delete',
                            'full access' => 'Full Access'
                        ];
                        $perms = ['perm_assets' => 'Assets', 'perm_users' => 'Users', 'perm_categories' => 'Categories', 'perm_departments' => 'Departments', 'perm_roles' => 'Roles'];
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        @foreach($perms as $field => $label)
                        <div>
                            <x-input-label :for="$field" :value="__($label . ' Permissions')" />
                            <select id="{{ $field }}" name="{{ $field }}" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach($options as $val => $text)
                                    <option value="{{ $val }}" {{ old($field, $role->$field ?? 'no access') === $val ? 'selected' : '' }}>
                                        {{ $text }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get($field)" class="mt-2" />
                        </div>
                        @endforeach
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
