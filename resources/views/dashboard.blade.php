<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.dashboard') }}
            @if ($activeProperty)
                <span class="text-sm font-normal text-gray-500">— {{ $activeProperty->name }}</span>
            @elseif (Auth::user()->isSuperAdmin())
                <span class="text-sm font-normal text-gray-500">— All Properties</span>
            @endif
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6">

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.total_assets') }}</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalAssets }}</div>
                </div>

                <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.active') }}</div>
                    <div class="mt-2 text-3xl font-bold text-green-600">{{ $isAssets }}</div>
                </div>

                <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.under_maintenance') }}</div>
                    <div class="mt-2 text-3xl font-bold text-yellow-500">{{ $oosAssets }}</div>
                </div>

                <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm p-6">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('messages.retired') }}</div>
                    <div class="mt-2 text-3xl font-bold text-red-600">{{ $disposedAssets }}</div>
                </div>
            </div>

            <!-- Assets by Department -->
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('messages.assets_by_department') }}</h3>
                <ul class="divide-y divide-gray-200/50 dark:divide-gray-700/50">
                    @foreach ($assetsByDepartment as $department => $count)
                        <li class="flex justify-between py-2">
                            <span class="text-gray-700 dark:text-gray-200">{{ $department }}</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('messages.recent_assets') }}</h3>
                <div class="overflow-x-auto -mx-4 sm:-mx-6">
                    <div class="inline-block min-w-full px-4 sm:px-6">
                        <table class="min-w-full divide-y divide-gray-200/50 dark:divide-gray-700/50">
                            <thead class="bg-gray-50/50 dark:bg-gray-800/50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-200">{{ __('messages.name') }}</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-200">{{ __('messages.department') }}</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-200">{{ __('messages.status') }}</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-200">{{ __('messages.updated') }}</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-200">{{ __('messages.editor') }}</th>
                                    @if(Auth::user()->hasExecutiveOversight())
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 dark:text-gray-200">{{ __('messages.action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200/50 dark:divide-gray-700/50">
                                @foreach ($recentAssets as $asset)
                                    <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $asset->name }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $asset->department?->name ?? __('messages.n_a') }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            <x-status-badge :status="$asset->status" />
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $asset->updated_at->diffForHumans() }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $asset->editorUser ? $asset->editorUser->name : __('messages.n_a') }}</td>
                                        @if(Auth::user()->hasExecutiveOversight())
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <a href="{{ route('assets.history', $asset) }}" class="text-accent hover:underline text-sm font-medium">{{ __('messages.history') }}</a>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
