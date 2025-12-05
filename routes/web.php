<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GroupController;
use App\Models\Group;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    
    // Dashboard: List my groups
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // FETCH LOGIC:
        // 1. Groups where I am the Admin
        // 2. OR Groups where I have claimed a theme (via the 'themes' relationship)
        $groups = \App\Models\Group::where('admin_id', $user->id)
            ->orWhereHas('themes', function ($query) use ($user) {
                $query->where('claimed_by_user_id', $user->id);
            })
            ->get();

        return view('dashboard', compact('groups'));
    })->name('dashboard');

    // The Drafting Room
    Route::get('/groups/{group}', function (Group $group) {
        return view('groups.show', compact('group'));
    })->name('groups.show');

    Route::post('/groups/{group}/draw', [App\Http\Controllers\GroupController::class, 'draw'])->name('groups.draw');

    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');

    // Handle the "Join" form submission
    Route::post('/join', function (\Illuminate\Http\Request $request) {
        $request->validate(['code' => 'required']);
        
        $group = \App\Models\Group::where('invite_code', $request->code)->first();

        if (!$group) {
            return back()->withErrors(['code' => 'Invalid Group Code']);
        }

        return redirect()->route('groups.show', $group);
    })->name('groups.join');

    // --- RESTORED PROFILE ROUTES (Fixes the Error) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';