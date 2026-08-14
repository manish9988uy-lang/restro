<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationCampaign extends Model
{
    protected $fillable = [
        'name', 'type', 'message', 'recipients', 'scheduled_at', 'sent_at', 'status'
    ];

    protected $casts = [
        'recipients' => 'json',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];
}
