<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
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
                <div class="glass-card p-6">
                    <div class="text-sm font-medium text-gray-500">Total Assets</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalAssets }}</div>
                </div>

                <div class="glass-card p-6">
                    <div class="text-sm font-medium text-gray-500">Active</div>
                    <div class="mt-2 text-3xl font-bold text-green-600">{{ $isAssets }}</div>
                </div>

                <div class="glass-card p-6">
                    <div class="text-sm font-medium text-gray-500">Under Maintenance</div>
                    <div class="mt-2 text-3xl font-bold text-yellow-500">{{ $oosAssets }}</div>
                </div>

                <div class="glass-card p-6">
                    <div class="text-sm font-medium text-gray-500">Retired</div>
                    <div class="mt-2 text-3xl font-bold text-red-600">{{ $disposedAssets }}</div>
                </div>
            </div>

            <!-- Assets by Department -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Assets by Department</h3>
                <ul class="divide-y divide-gray-200">
                    @foreach ($assetsByDepartment as $department => $count)
                        <li class="flex justify-between py-2">
                            <span class="text-gray-700">{{ $department }}</span>
                            <span class="font-semibold">{{ $count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Recent Activity -->
            <div class="glass-card p-4 sm:p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Assets</h3>
                <div class="overflow-x-auto -mx-4 sm:-mx-6">
                    <div class="inline-block min-w-full px-4 sm:px-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Name</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Department</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Status</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Updated</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Editor</th>
                                    @if(Auth::user()->hasExecutiveOversight())
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($recentAssets as $asset)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $asset->name }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $asset->department?->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <x-status-badge :status="$asset->status" />
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $asset->updated_at->diffForHumans() }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $asset->editorUser ? $asset->editorUser->name : 'N/A' }}</td>
                                        @if(Auth::user()->hasExecutiveOversight())
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <a href="{{ route('assets.history', $asset) }}" class="text-accent hover:underline text-sm font-medium">History</a>
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
