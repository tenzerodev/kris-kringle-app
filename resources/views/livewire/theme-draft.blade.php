{{-- <div wire:poll.2s> <h2 class="text-xl font-bold mb-4">Pick Your Battle (Theme)</h2>

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
                            class="bg-blue-500 px-4 py-2 rounded hover:bg-blue-600">
                        Claim This
                    </button>
                @endif
            </div>
        @endforeach
    </div>
</div> --}}

<div wire:poll.3s>
    
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded">
        <p><strong>📅 Exchange Date:</strong> {{ $group->exchange_date }}</p>
        @if($group->notes)
            <p class="mt-2 text-sm text-gray-600"><strong>📝 Note:</strong> {{ $group->notes }}</p>
        @endif
    </div>

    @php
        $myTheme = $themes->where('claimed_by_user_id', auth()->id())->first();
    @endphp

    @if($myTheme)
        <div class="text-center p-10 bg-green-100 rounded-lg border-2 border-green-500">
            <h2 class="text-2xl font-bold text-green-800">🎉 You are bringing:</h2>
            <p class="text-4xl font-black mt-4 uppercase">{{ $myTheme->description }}</p>
            <p class="text-sm text-gray-600 mt-4">Wait for the pairing draw to see who this is for!</p>
        </div>

    @else
        
        @if($group->mode === 'RANDOM')
            <div class="text-center py-12">
                <h3 class="text-xl mb-6">Themes are hidden! Click below to draw your destiny.</h3>
                <button wire:click="drawRandomTheme" 
                        class="bg-purple-600 text-white text-xl font-bold px-8 py-4 rounded-full shadow-lg hover:bg-purple-700 transform hover:scale-105 transition">
                    🎲 Draw My Random Theme
                </button>
                <p class="text-sm text-gray-500 mt-4">
                    {{ $themes->whereNull('claimed_by_user_id')->count() }} themes remaining.
                </p>
            </div>

        @else
            <h3 class="font-bold mb-4">Select your Theme:</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($themes as $theme)
                    <div class="border p-6 rounded-lg shadow-md transition duration-200 
                        {{ $theme->claimed_by_user_id ? 'bg-gray-200 opacity-50' : 'bg-white' }}">
                        
                        <h3 class="text-lg font-bold">{{ $theme->description }}</h3>
                        
                        @if($theme->claimed_by_user_id)
                            <span class="text-red-500 text-sm font-bold">Taken</span>
                        @else
                            <button wire:click="claimTheme({{ $theme->id }})" 
                                    class="mt-2 bg-blue-600 text-white px-4 py-2 rounded w-full">
                                Claim
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

    @endif
</div>