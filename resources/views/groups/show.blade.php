<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $group->name }}
        </h2>
        <p class="text-sm text-gray-500">Invite Code: {{ $group->invite_code }}</p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Step 1: Choose Your Theme</h3>
                <livewire:theme-draft :group="$group" />
            </div>

            @if(auth()->id() === $group->admin_id)
                <div class="bg-yellow-50 border border-yellow-200 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-yellow-800 mb-4">👑 Admin Controls (Only You Can See This)</h3>

                    <div class="mb-6">
                        <h4 class="font-semibold mb-2">Who has joined?</h4>
                        <ul class="list-disc list-inside">
                            @foreach($group->themes as $theme)
                                @if($theme->claimed_by_user_id)
                                    <li>
                                        <strong>{{ $theme->user->name ?? 'Unknown' }}</strong> 
                                        picked "{{ $theme->description }}"
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>

                    @if($group->status === 'DRAFTING')
                         <form action="{{ route('groups.draw', $group) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded font-bold">
                                🎲 GENERATE PAIRS NOW
                            </button>
                        </form>
                    @endif

                    @if($group->status === 'DRAWN')
                        <div class="mt-6 border-t pt-4">
                            <h4 class="font-bold text-xl mb-2">🎁 Master List (Secret)</h4>
                            <table class="w-full text-left">
                                <thead>
                                    <tr>
                                        <th class="p-2">Santa (Giver)</th>
                                        <th class="p-2">Baby (Receiver)</th>
                                        <th class="p-2">Theme to Buy</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($group->pairings as $pair)
                                        <tr class="border-b">
                                            <td class="p-2">{{ $pair->santa->name }}</td>
                                            <td class="p-2">{{ $pair->baby->name }}</td>
                                            <td class="p-2 font-bold text-indigo-600">
                                                {{ $pair->baby->themes->where('group_id', $group->id)->first()->description ?? 'Any' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>