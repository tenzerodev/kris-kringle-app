<div wire:poll.2s> <h2 class="text-xl font-bold mb-4">Pick Your Battle (Theme)</h2>

    @if (session()->has('error'))
        <div class="bg-red-500 text-white p-2 rounded mb-4">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($themes as $theme)
            <div class="p-6 border rounded shadow 
                {{ $theme->claimed_by_user_id ? 'bg-gray-200 opacity-50' : 'bg-white' }}">
                
                <h3 class="text-lg font-bold">{{ $theme->description }}</h3>
                
                @if($theme->claimed_by_user_id === auth()->id())
                    <span class="text-green-600 font-bold">You picked this!</span>
                @elseif($theme->claimed_by_user_id)
                    <span class="text-red-500">Taken</span>
                @else
                    <button wire:click="claimTheme({{ $theme->id }})" 
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Claim This
                    </button>
                @endif
            </div>
        @endforeach
    </div>
</div>