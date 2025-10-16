<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Total Assets</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalAssets }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Active</div>
                    <div class="mt-2 text-3xl font-bold text-green-600">{{ $isAssets }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Under Maintenance</div>
                    <div class="mt-2 text-3xl font-bold text-yellow-500">{{ $oosAssets }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Retired</div>
                    <div class="mt-2 text-3xl font-bold text-red-600">{{ $disposedAssets }}</div>
                </div>
            </div>

            <!-- Asset Value -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800">Total Asset Value</h3>
                <p class="mt-2 text-2xl font-bold text-indigo-600">
                    Rp {{ number_format($totalValue, 0, ',', '.') }}
                </p>
            </div>

            <!-- Assets by Department -->
            <div class="bg-white shadow rounded-lg p-6">
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
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Assets</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Name</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Department</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Status</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($recentAssets as $asset)
                            <tr>
                                <td class="px-4 py-2">{{ $asset->name }}</td>
                                <td class="px-4 py-2">{{ $asset->department->name }}</td>
                                <td class="px-4 py-2">
                                    <x-status-badge :status="$asset->status" />
                                </td>
                                <td class="px-4 py-2">{{ $asset->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
