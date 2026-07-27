<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $fillable = [
        'item_type',
        'item_id',
        'user_id',
        'reaction_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
