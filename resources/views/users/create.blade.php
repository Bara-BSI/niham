<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Create Account') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Responsive Two-Column Layout -->
                    <div class="m-8 grid grid-cols-2 gap-1 justify-evenly">

                        <!-- Left Column -->
                        <div class="col-span-2 md:col-span-1">
                            
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Account Name')" />
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
                                <x-input-label for="username" :value="__('Username')" />
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
                                <x-input-label for="password" :value="__('Password')" />
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
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
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

                            <!-- Departments -->
                            <div>
                                <div x-data="{ selected: '{{ old('department_id', $user->department_id ?? '') }}' }">
                                    <x-input-label for="department_id" :value="__('Department')" />

                                    <select
                                        id="department_id"
                                        name="department_id"
                                        x-model="selected"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    >
                                        <option value="">—</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <!-- Tooltip/note appears only if EXE is selected -->
                                    <p x-show="selected && $el.previousElementSibling.value == '{{ $departments->firstWhere('code','EXE')->id ?? '' }}'"
                                    class="mt-1 text-sm text-yellow-700 mb-3">
                                        Executive can oversee other departments
                                    </p>
                                </div>

                                <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                            </div>

                            <!-- Role -->
                            <div>
                                <x-input-label for="role_id" :value="__('Role')" />
                                <select
                                    id="role_id"
                                    name="role_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                    <option value="">—</option>
                                    @foreach ($roles as $role)
                                        @if (old('role_id') == $role->id)
                                            <option value="{{ $role->id }}" selected>
                                                {{ $role->name }}
                                            </option>
                                        @else
                                            <option value="{{ $role->id }}">
                                                {{ $role->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                            </div>

                            {{-- Email Address --}}
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
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
                                {{ __('Back') }}
                            </x-secondary-button>

                        </div>
                        <!-- Submit Button -->
                        <div class="mt-6 flex justify-end">
                            <x-primary-button>
                                <x-heroicon-s-bookmark class="w-4 h-4 mr-2" />
                                {{ __('Save Account') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
