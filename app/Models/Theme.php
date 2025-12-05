<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Theme extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        // A theme is claimed by a User
        return $this->belongsTo(User::class, 'claimed_by_user_id');
    }
}
