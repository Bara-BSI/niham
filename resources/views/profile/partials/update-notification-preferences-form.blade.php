<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('messages.notification_preferences') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("messages.manage_how_you_receive_alerts_about_assets_and_pro") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update.notifications') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div x-data="{ allProp: {{ auth()->user()->notify_all_properties ? 'true' : 'false' }}, dept: {{ auth()->user()->notify_department ? 'true' : 'false' }} }">
            
            <div class="flex items-start gap-3">
                <input type="hidden" name="notify_all_properties" value="0">
                <input type="checkbox" id="notify_all_properties" name="notify_all_properties" value="1" x-model="allProp" class="mt-1 rounded border-gray-300 text-accent focus:ring-accent dark:bg-gray-900 dark:border-gray-700">
                <label for="notify_all_properties" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('messages.all_properties_notifications') }}
                    <span class="block text-xs text-gray-500">{{ __('messages.receive_notifications_for_all_changes_in_the_curre') }}</span>
                </label>
            </div>

            <div class="flex items-start gap-3 mt-4">
                <input type="hidden" name="notify_department" value="0">
                <input type="checkbox" id="notify_department" name="notify_department" value="1" x-model="dept" x-bind:disabled="allProp" class="mt-1 rounded border-gray-300 text-accent focus:ring-accent dark:bg-gray-900 dark:border-gray-700 disabled:opacity-50">
                <label for="notify_department" class="text-sm font-medium text-gray-700 dark:text-gray-300" :class="allProp ? 'opacity-50' : ''">
                    {{ __('messages.department_notifications') }}
                    <span class="block text-xs text-gray-500">{{ __('messages.receive_notifications_only_for_assets_in_your_depa') }}</span>
                </label>
            </div>

            <div class="flex items-start gap-3 mt-4">
                <input type="hidden" name="notify_email" value="0">
                <input type="checkbox" id="notify_email" name="notify_email" value="1" {{ auth()->user()->notify_email ? 'checked' : '' }} class="mt-1 rounded border-gray-300 text-accent focus:ring-accent dark:bg-gray-900 dark:border-gray-700">
                <label for="notify_email" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('messages.email_notifications') }}
                    <span class="block text-xs text-gray-500">{{ __('messages.receive_these_alerts_via_email_as_well') }}</span>
                </label>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('messages.save_preferences') }}</x-primary-button>

            @if (session('status') === 'notifications-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('messages.saved') }}
                </p>
            @endif
        </div>
    </form>
</section>
