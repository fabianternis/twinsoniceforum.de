<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Shout extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'message',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'message'])
            ->useLogName('shoutbox_audit');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
