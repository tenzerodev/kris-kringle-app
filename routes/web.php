<?php

use App\Models\Group;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    
    // Dashboard: List my groups
    Route::get('/dashboard', function () {
        $groups = auth()->user()->groups; // Relationship needed in User model
        return view('dashboard', compact('groups'));
    })->name('dashboard');

    // The Drafting Room
    Route::get('/groups/{group}', function (Group $group) {
        return view('groups.show', compact('group'));
    })->name('groups.show');
});

require __DIR__.'/auth.php';