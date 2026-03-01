<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('messages.profile_information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>

        <p class="mt-1 text-sm text-yellow-800 dark:text-yellow-500">
            {{ __("messages.disabled_profile_informations_can_only_be_changed") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="username" :value="__('messages.username')" />
            @if (Auth::user()->isRole('admin') || Auth::user()->isSuperAdmin())
                <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required autofocus autocomplete="username" />
            @else
                <x-text-input id="username" name="username" type="text" class="mt-1 block w-full text-gray-400" :value="old('username', $user->username)" required autofocus autocomplete="username" disabled="true" />
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="department_id" :value="__('messages.department')" />
            @if (Auth::user()->isRole('admin') || Auth::user()->isSuperAdmin())
                <select id="department_id" name="department_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-100 shadow-sm focus:ring-accent focus:border-accent" required>
                    <option value="">—</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}{{ Auth::user()->isSuperAdmin() && $department->property ? ' - ' . $department->property->name : '' }}
                        </option>
                    @endforeach
                </select>
            @else
                <x-text-input id="department" name="department" type="text" class="mt-1 block w-full text-gray-400" :value="old('department', $user->department?->name)" required autofocus autocomplete="department" disabled="true" />
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
        </div>

        <div>
            <x-input-label for="role_id" :value="__('messages.role')" />
            @if (Auth::user()->isRole('admin') || Auth::user()->isSuperAdmin())
                <select id="role_id" name="role_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-100 shadow-sm focus:ring-accent focus:border-accent" required>
                    <option value="">—</option>
                    @foreach ($roles as $role)
                        @if (old('role_id', $user->role_id) == $role->id)
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
            @else
                <x-text-input id="role" name="role" type="text" class="mt-1 block w-full text-gray-400" :value="old('role', $user->role?->name)" required autofocus autocomplete="role" disabled="true" />
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('role_id')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('messages.name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('messages.email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-900 dark:text-gray-100">
                        {{ __('messages.your_email_address_is_unverified') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 dark:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                            {{ __('messages.click_here_to_re_send_the_verification_email') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('messages.a_new_verification_link_has_been_sent_to_your_emai') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('messages.save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('messages.saved') }}</p>
            @endif
        </div>
    </form>
</section>
