<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold mb-4">Create a New Exchange</h3>
                
                <form action="{{ route('groups.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" name="name" placeholder="Group Name (e.g. Marketing Team)" class="border p-2 rounded w-full" required>
                        <input type="date" name="exchange_date" class="border p-2 rounded w-full" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="font-bold block mb-1">Number of Participants</label>
                        <input type="number" name="max_participants" value="10" min="2" 
                            class="border p-2 rounded w-full" required>
                    </div>

                    <div class="border p-4 rounded bg-gray-50">
                        <label class="font-bold block mb-2">How do users get themes?</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="mode" value="CLAIM" checked>
                                <span>User Picks (First come, first served)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="mode" value="RANDOM">
                                <span>Random Draw (Mystery!)</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="font-bold block mb-1">Themes (Separate by comma or new line)</label>
                        <textarea name="themes_input" rows="3" placeholder="Something Red, Something Soft, Something Expensive..." class="w-full border p-2 rounded" required></textarea>
                    </div>

                    <textarea name="notes" placeholder="Any special instructions? (e.g., Budget is 500 pesos)" class="w-full border p-2 rounded"></textarea>

                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700">
                        Create Group
                    </button>
                </form>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Join an Existing Group</h3>
                
                <form action="{{ route('groups.join') }}" method="POST" class="flex gap-4">
                    @csrf
                    <input type="text" name="code" placeholder="Paste Invite Code here..." 
                        class="border-gray-300 rounded-md shadow-sm w-full" required>
                    
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                        Go
                    </button>
                </form>

                @error('code')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">My Exchange Groups</h3>
                
                @if($groups->isEmpty())
                    <p class="text-gray-500">You haven't created or joined any groups yet.</p>
                @else
                    <div class="grid gap-4">
                        @foreach($groups as $group)
                            <div class="border p-4 rounded flex justify-between items-center hover:bg-gray-50">
                                <div>
                                    <h4 class="font-bold text-lg">{{ $group->name }}</h4>
                                    <span class="text-sm text-gray-500">Code: {{ $group->invite_code }}</span>
                                </div>
                                <a href="{{ route('groups.show', $group) }}" 
                                   class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                    Enter Room
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>