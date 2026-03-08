<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">{{ __('messages.reset_password') }}</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
            {{ __('messages.forgot_your_password_no_problem_just_let_us_know_y') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('messages.email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-8 flex items-center justify-between">
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-100 transition-colors">
                &larr; {{ __('messages.back_to_login') }}
            </a>
            <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-900 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-900 transition-colors">
                {{ __('messages.email_password_reset_link') }}
            </button>
        </div>
    </form>
</x-guest-layout>
