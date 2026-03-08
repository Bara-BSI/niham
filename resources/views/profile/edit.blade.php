<x-app-layout>
<div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md shadow-sm sm:rounded-xl border border-gray-200/50 dark:border-gray-700/50">
                <div class="px-6 py-4 border-b border-gray-200/50 dark:border-gray-700/50">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                        {{ __('messages.profile') ?? __('messages.profile') }}
                    </h2>
                </div>
                <div class="p-4 sm:p-8">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white/90 dark:bg-gray-800/90 backdrop-blur-md shadow-sm sm:rounded-xl border border-gray-200/50 dark:border-gray-700/50">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white/90 dark:bg-gray-800/90 backdrop-blur-md shadow-sm sm:rounded-xl border border-gray-200/50 dark:border-gray-700/50">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
