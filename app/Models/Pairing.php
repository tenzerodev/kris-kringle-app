<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pairing extends Model
{
    protected $guarded = []; // Allow mass assignment

    public function santa()
    {
        return $this->belongsTo(User::class, 'santa_id');
    }

    public function baby()
    {
        return $this->belongsTo(User::class, 'baby_id');
    }
}