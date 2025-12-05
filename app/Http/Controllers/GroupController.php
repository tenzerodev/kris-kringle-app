<?php

namespace App\Http\Controllers;

use App\Http\Controllers\GroupController;

use App\Models\Group;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'exchange_date' => 'required|date',
            'mode' => 'required|in:CLAIM,RANDOM',
            'notes' => 'nullable|string',
            'themes_input' => 'required|string',
            'max_participants' => 'required|integer|min:2' // Ensure this exists
        ]);

        DB::transaction(function () use ($request) {
            // 1. Create Group
            $group = Group::create([
                'admin_id' => auth()->id(),
                'name' => $request->name,
                'exchange_date' => $request->exchange_date,
                'mode' => $request->mode,
                'notes' => $request->notes,
                'invite_code' => Str::uuid(),
                'max_participants' => $request->max_participants,
                'status' => 'DRAFTING'
            ]);

            // 2. Process Themes
            // Split by comma or new line
            $rawThemes = preg_split("/\r\n|\n|\r|,/", $request->themes_input);
            
            // Clean up empty spaces
            $cleanThemes = array_filter(array_map('trim', $rawThemes));
            
            // --- NEW LOGIC: FILL THE GAP ---
            $themeCount = count($cleanThemes);
            $needed = $request->max_participants;

            if ($themeCount > 0 && $themeCount < $needed) {
                // If we have 3 themes but need 10, keep merging until we have enough
                while (count($cleanThemes) < $needed) {
                    $cleanThemes = array_merge($cleanThemes, $cleanThemes);
                }
                // Cut it down to exact size (e.g., if we generated 12 but need 10)
                $cleanThemes = array_slice($cleanThemes, 0, $needed);
            }
            // -------------------------------

            // 3. Save to Database
            foreach ($cleanThemes as $themeName) {
                Theme::create([
                    'group_id' => $group->id,
                    'description' => $themeName
                ]);
            }
        });

        return redirect()->route('dashboard')->with('success', 'Group Created!');
    }

    public function draw(Group $group)
    {
        // 1. Validation Checks
        if ($group->admin_id !== auth()->id()) {
            abort(403, "Only the admin can start the draw.");
        }
        
        if ($group->status === 'DRAWN') {
            return back()->with('error', 'Pairs have already been generated!');
        }

        // 2. Get all participants who have a theme
        // (We assume a user 'has joined' if they picked a theme)
        $participants = $group->themes()
                            ->whereNotNull('claimed_by_user_id')
                            ->get()
                            ->pluck('claimed_by_user_id')
                            ->unique()
                            ->shuffle(); // Randomize the order

        if ($participants->count() < 2) {
            return back()->with('error', 'You need at least 2 participants to draw.');
        }

        // 3. The Logic (Circular Linked List)
        // We line everyone up. Person 1 gives to Person 2. Last Person gives to Person 1.
        // This mathematically guarantees no one draws themselves.
        
        $pairings = [];
        $count = $participants->count();

        for ($i = 0; $i < $count; $i++) {
            $santaId = $participants[$i];
            
            // If it's the last person, they give to the first person
            $babyId = ($i == $count - 1) ? $participants[0] : $participants[$i + 1];

            $pairings[] = [
                'group_id' => $group->id,
                'santa_id' => $santaId,
                'baby_id' => $babyId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 4. Save to Database
        \App\Models\Pairing::insert($pairings);
        
        // 5. Update Status so the button disappears
        $group->update(['status' => 'DRAWN']);

        return back()->with('success', 'Pairs Generated Successfully!');
    }
}