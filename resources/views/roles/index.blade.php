<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
                {{ __('messages.roles') }}
            </h2>
            <div>
                @can('create', App\Models\Role::class)
                <a href="{{ route('roles.create') }}"
                class="inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-md 
                        font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 
                        focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                    <x-heroicon-s-plus class="w-4 h-4 mr-2" />
                    {{ __('messages.new_role') }}
                </a>
                @endcan
            </div>
        </div>
    </x-slot>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-2">
            
            <!-- Table -->
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-xl border border-gray-200/50 dark:border-gray-700/50 shadow-sm overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200/50 dark:divide-gray-700/50">
                    <thead class="bg-gray-50/50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider" rowspan="2">{{ __('messages.no') }}</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider" rowspan="2">{{ __('messages.name') }}</th>
                            @if(Auth::user()->isSuperAdmin())
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider" rowspan="2">{{ __('messages.property') }}</th>
                            @endif
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider" colspan="5">{{ __('messages.permissions') }}</th>
                        </tr>
                        <tr>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('messages.perm_ass') }}</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('messages.perm_usr') }}</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('messages.perm_cat') }}</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('messages.perm_dep') }}</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">{{ __('messages.perm_rol') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/50 dark:divide-gray-700/50">
                        @foreach($roles as $role)
                            <tr class="transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <!-- No -->
                                <td class="px-4 py-2 text-center text-sm text-gray-700 dark:text-gray-200">
                                    {{ $roles->firstItem() + $loop->index }}
                                </td>

                                <!-- Role Name -->
                                <td class="px-4 py-2 text-center text-sm font-medium text-accent font-semibold hover:underline">
                                    <a href="{{ route('roles.show', $role) }}">{{ ucwords($role->name) }}</a>
                                </td>

                                @if(Auth::user()->isSuperAdmin())
                                    <td class="px-4 py-2 text-center text-sm text-gray-700 dark:text-gray-200">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white shadow-sm" style="background-color: {{ optional($role->property)->accent_color ?? '#6b7280' }}">
                                            {{ optional($role->property)->name ?? '-' }}
                                        </span>
                                    </td>
                                @endif

                                <!-- Permissions -->
                                <td class="px-1 py-2 text-center">
                                    <span class="text-xs px-2 py-1 rounded {{ $role->perm_assets == 'no access' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' }}">
                                        {{ mb_substr(ucwords(__('messages.' . str_replace([' & ', ' '], ['_', '_'], $role->perm_assets))), 0, 4) }}..
                                    </span>
                                </td>
                                <td class="px-1 py-2 text-center">
                                    <span class="text-xs px-2 py-1 rounded {{ $role->perm_users == 'no access' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' }}">
                                        {{ mb_substr(ucwords(__('messages.' . str_replace([' & ', ' '], ['_', '_'], $role->perm_users))), 0, 4) }}..
                                    </span>
                                </td>
                                <td class="px-1 py-2 text-center">
                                    <span class="text-xs px-2 py-1 rounded {{ $role->perm_categories == 'no access' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' }}">
                                        {{ mb_substr(ucwords(__('messages.' . str_replace([' & ', ' '], ['_', '_'], $role->perm_categories))), 0, 4) }}..
                                    </span>
                                </td>
                                <td class="px-1 py-2 text-center">
                                    <span class="text-xs px-2 py-1 rounded {{ $role->perm_departments == 'no access' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' }}">
                                        {{ mb_substr(ucwords(__('messages.' . str_replace([' & ', ' '], ['_', '_'], $role->perm_departments))), 0, 4) }}..
                                    </span>
                                </td>
                                <td class="px-1 py-2 text-center">
                                    <span class="text-xs px-2 py-1 rounded {{ $role->perm_roles == 'no access' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' }}">
                                        {{ mb_substr(ucwords(__('messages.' . str_replace([' & ', ' '], ['_', '_'], $role->perm_roles))), 0, 4) }}..
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <!-- Pagination -->
            <div class="mt-4">
                {{ $roles->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
