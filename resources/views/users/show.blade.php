<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Account Details') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-sm shadow-md rounded-xl border border-white/30 p-6 space-y-6">

                {{-- <!-- Image Preview -->
                @if ($account->attachments->first())
                    <div class="flex justify-center">
                        <img src="{{ account('storage/' . $account->attachments->first()->path) }}"
                             alt="Account Image"
                             class="max-w-xs rounded-md shadow-md border border-gray-200" />
                    </div>
                @endif --}}

                <!-- Account Info -->
                <div class="grid grid-cols-2 gap-1 justify-evenly mx-5">
                    <div class="col-span-2 md:col-span-1">
                        <div><strong>Name:</strong> {{ $user->name }}</div>
                        <div><strong>Username:</strong> {{ $user->username }}</div>
                        <div><strong>Department:</strong> {{ $user->department->name ?? '-' }}</div>
                        <div><strong>Role:</strong> {{ $user->role->name ?? '-' }}</div>
                        <div><strong>Property:</strong> {{ $user->property->name ?? '-' }}</div>
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <div><strong>Email:</strong> {{ $user->email }}</div>
                        <div><strong>Join Date:</strong> {{ $user->created_at?->format('d M Y') }}</div>
                        <div><strong>Last Edit:</strong> {{ $user->updated_at?->format('d M Y') }}</div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-between items-center">
                    <!-- Back Button -->
                    <a href="{{ route('users.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md 
                            font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 
                            focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        <x-heroicon-s-arrow-left class="w-4 h-4 mr-2" />
                        Back
                    </a>

                    <div class="inline-flex">
                        <!-- Edit Button -->
                        <a href="{{ route('users.edit', $user) }}"
                        class="inline-flex items-center px-4 py-2 bg-accent border border-transparent rounded-md 
                                font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 
                                focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition">
                            <x-heroicon-s-pencil class="w-4 h-4 mr-2" />
                            Edit
                        </a>
                        <!-- Delete Button -->
                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md 
                                        font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 
                                        focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ml-1">
                                <x-heroicon-s-trash class="w-4 h-4 mr-2" />
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
