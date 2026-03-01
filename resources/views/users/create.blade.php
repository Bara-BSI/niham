<x-app-layout>
<div class="py-4 sm:py-8">
        <div class="mx-auto max-w-4xl px-3 sm:px-6 lg:px-8">
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200/50 dark:border-gray-700/50">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ __('messages.add_new_user') ?? __('messages.add_new_user') }}
                    </h2>
                </div>
                <div class="p-6">
                <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Responsive Two-Column Layout -->
                    <div class="m-8 grid grid-cols-2 gap-1 justify-evenly">

                        <!-- Left Column -->
                        <div class="col-span-2 md:col-span-1">
                            
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('messages.account_name')" />
                                <x-text-input
                                    id="name"
                                    name="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    :value="old('name')"
                                    required
                                />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Username -->
                            <div>
                                <x-input-label for="username" :value="__('messages.username')" />
                                <x-text-input
                                    id="username"
                                    name="username"
                                    type="text"
                                    class="mt-1 block w-full"
                                    :value="old('username')"
                                    required
                                />
                                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            </div>
                            
                            <!-- Password -->
                            <div>
                                <x-input-label for="password" :value="__('messages.password')" />
                                <x-text-input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Password Confirmation -->
                            <div>
                                <x-input-label for="password_confirmation" :value="__('messages.confirm_password')" />
                                <x-text-input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-span-2 md:col-span-1">

                            {{-- Property (Super Admin only) --}}
                            @if (Auth::user()->isSuperAdmin())
                                <div>
                                    <x-input-label for="property_id" :value="__('messages.property')" />
                                    <select
                                        id="property_id"
                                        name="property_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-100 shadow-sm focus:ring-accent focus:border-accent"
                                        required
                                    >
                                        <option value="">—</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>
                                                {{ $property->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('property_id')" class="mt-2" />
                                </div>
                            @endif

                            <!-- Departments -->
                            <div>
                                <div x-data="{ selected: '{{ old('department_id', $user->department_id ?? '') }}' }">
                                    <x-input-label for="department_id" :value="__('messages.department')" />

                                    <select
                                        id="department_id"
                                        name="department_id"
                                        x-model="selected"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-100 shadow-sm focus:ring-accent focus:border-accent"
                                    >
                                        <option value="">—</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">
                                                {{ $department->name }}{{ Auth::user()->isSuperAdmin() && $department->property ? ' - ' . $department->property->name : '' }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <!-- Tooltip/note appears only if the specific department has executive oversight -->
                                    <p x-cloak x-show="selected && {{ $departments->where('is_executive_oversight', true)->pluck('id') }}.includes(parseInt(selected))"
                                    class="mt-1 text-sm text-accent mb-3 font-semibold text-center italic">
                                        {{ __('messages.executive_can_oversee_other_departments') }}
                                    </p>
                                </div>

                                <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                            </div>

                            <!-- Role -->
                            <div>
                                <x-input-label for="role_id" :value="__('messages.role')" />
                                <select
                                    id="role_id"
                                    name="role_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-100 shadow-sm focus:ring-accent focus:border-accent"
                                >
                                    <option value="">—</option>
                                    @foreach ($roles as $role)
                                        @if (old('role_id') == $role->id)
                                            <option value="{{ $role->id }}" selected>
                                                {{ $role->name }}{{ Auth::user()->isSuperAdmin() && $role->property ? ' - ' . $role->property->name : '' }}
                                            </option>
                                        @else
                                            <option value="{{ $role->id }}">
                                                {{ $role->name }}{{ Auth::user()->isSuperAdmin() && $role->property ? ' - ' . $role->property->name : '' }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                            </div>

                            {{-- Email Address --}}
                            <div>
                                <x-input-label for="email" :value="__('messages.email')" />
                                <x-text-input
                                    id="email"
                                    name="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    :value="old('email')"
                                />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between items-center">
                        <!-- Back Button -->
                        <div class="mt-6 flex justify-start">
                            <x-secondary-button onclick="window.history.back()">
                                <x-heroicon-s-arrow-left class="w-4 h-4 mr-2" />
                                {{ __('messages.back') }}
                            </x-secondary-button>

                        </div>
                        <!-- Submit Button -->
                        <div class="mt-6 flex justify-end">
                            <x-primary-button>
                                <x-heroicon-s-bookmark class="w-4 h-4 mr-2" />
                                {{ __('messages.save_account') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
