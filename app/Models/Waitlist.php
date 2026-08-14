<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waitlist extends Model
{
    protected $fillable = [
        'name', 'phone', 'party_size', 'status', 'notes'
    ];
}
