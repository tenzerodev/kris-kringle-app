<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;

    protected $guarded = [];

    // --- ADD THESE FUNCTIONS BELOW ---

    // 1. A Group has many Themes
    public function themes()
    {
        return $this->hasMany(Theme::class);
    }

    // 2. A Group has many Pairings (for the Secret Santa draw)
    public function pairings()
    {
        return $this->hasMany(Pairing::class);
    }

    // 3. A Group belongs to an Admin (User)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}