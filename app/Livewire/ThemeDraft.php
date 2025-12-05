<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Theme;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ThemeDraft extends Component
{
    public Group $group;

    // This makes the component refresh every 2 seconds to see if others took themes
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(Group $group)
    {
        $this->group = $group;
    }

    public function claimTheme($themeId)
    {
        $user = Auth::user();
        $theme = Theme::find($themeId);

        if ($theme->claimed_by_user_id) {
            session()->flash('error', 'Too slow! Someone took that.');
            return;
        }

        // Check if I already have a theme
        $myClaim = Theme::where('group_id', $this->group->id)
                        ->where('claimed_by_user_id', $user->id)
                        ->exists();

        if ($myClaim) {
            session()->flash('error', 'You already have a theme! Unselect it first.');
            return;
        }

        $theme->update(['claimed_by_user_id' => $user->id]);
    }

    public function render()
    {
        return view('livewire.theme-draft', [
            'themes' => $this->group->themes()->get()
        ]);
    }

    public function drawRandomTheme()
    {
        // Validation: Only allow if mode is RANDOM
        if ($this->group->mode !== 'RANDOM') return;

        $user = Auth::user();

        DB::transaction(function () use ($user) {
            // 1. Check if user already has a theme
            $existing = Theme::where('group_id', $this->group->id)
                            ->where('claimed_by_user_id', $user->id)
                            ->exists();

            if ($existing) {
                $this->dispatch('notify', 'You already have a theme!');
                return;
            }

            // 2. Find a random available theme and lock it
            // We use lockForUpdate to ensure two people don't draw the same one at the exact same millisecond
            $randomTheme = Theme::where('group_id', $this->group->id)
                                ->whereNull('claimed_by_user_id')
                                ->inRandomOrder()
                                ->lockForUpdate()
                                ->first();

            if (!$randomTheme) {
                $this->dispatch('notify', 'No themes left! Contact Admin.');
                return;
            }

            // 3. Assign
            $randomTheme->update(['claimed_by_user_id' => $user->id]);
        });
    }
}