<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Assets') }}
            </h2>
            <div>
                <a href="{{ route('assets.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md 
                        font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    <x-heroicon-s-plus class="w-4 h-4 mr-2" />
                    {{ __('New Asset') }}
                </a>
            </div>
        </div>
    </x-slot>


    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-2">
            <!-- Filter and Sort -->
            <form method="GET" action="{{ route('assets.index') }}" class="mb-6 w-max flex flex-wrap gap-4 items-center">
                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="category" id="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">All</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Department Filter -->
                @if (Auth::user()->inDept('EXE') || Auth::user()->inDept('PTLP'))
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                        <select name="department" id="department" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">All</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">All</option>
                        <option value="in_service" {{ request('status') == 'in_service' ? 'selected' : '' }}>In Service</option>
                        <option value="out_of_service" {{ request('status') == 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                        <option value="disposed" {{ request('status') == 'disposed' ? 'selected' : '' }}>Disposed</option>
                    </select>
                </div>

                <!-- Sort By -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
                    <select name="sort" id="sort" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="tag" {{ request('sort') == 'tag' ? 'selected' : '' }}>Tag</option>
                        <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>

                {{-- Search --}}
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name or tag" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                </div>

                <!-- Buttons -->
                <div class="pt-6 flex gap-2">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md 
                                font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 
                                focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        {{ __('Apply') }}
                    </button>

                    <button type="submit" formaction="{{ route('assets.export') }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md 
                                font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 
                                focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                        {{ __('Export') }}
                    </button>
                </div>

                
            </form>

            <!-- Table -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-scroll rounded">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tag</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">QR</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($assets as $a)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $assets->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $a->tag }}</td>
                                <td class="px-4 py-2 text-sm text-indigo-700 font-semibold hover:underline">
                                    <a href="{{ route('assets.show',$a) }}">{{ $a->name }}</a>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $a->category->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ optional($a->department)->name ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if ($a->status == 'in_service')
                                            bg-green-100 text-green-800
                                        @elseif ($a->status == 'out_of_service')
                                            bg-red-100 text-red-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $a->status)) }}
                                    </span>
                                </td>
                                {{-- QR --}}
                                <td class="px-4 py-2">
                                    <x-qr-modal :asset="$a"/>
                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $assets->links() }}
            </div>

            @if (Auth::user()->isRole('admin'))
                {{-- Backup --}}
                <div class="flex gap-2">
                    <form action="{{ route('backup.download') }}" method="POST">
                        @csrf
                        <x-primary-button>Download Backup</x-primary-button>
                    </form>

                    <!-- Trigger button -->
                    <x-danger-button x-data @click="$dispatch('open-restore-modal')">
                        Restore Data
                    </x-danger-button>

                    <!-- Modal -->
                    <div 
                        x-data="{ open: false }"
                        x-on:open-restore-modal.window="open = true"
                        x-show="open"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                        x-cloak
                    >
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                            <h2 class="text-lg font-semibold text-gray-800">
                                ⚠️ Restore Backup
                            </h2>
                            <p class="mt-2 text-sm text-gray-600">
                                Restoring a backup will <strong>replace ALL current data and attachments</strong>.
                                Please select a backup file to continue.
                            </p>

                            <form action="{{ route('backup.restore') }}" method="POST" enctype="multipart/form-data"
                                class="mt-4 space-y-4"
                                onsubmit="return confirm('⚠️ This will overwrite all data. Continue?');">
                                @csrf
                                <input type="file" name="backup" accept=".zip" required
                                    class="block w-full text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring focus:ring-indigo-500" />

                                <div class="flex justify-end gap-2">
                                    <x-secondary-button type="button" @click="open = false">
                                        Cancel
                                    </x-secondary-button>
                                    <x-danger-button type="submit">
                                        Restore Now
                                    </x-danger-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
