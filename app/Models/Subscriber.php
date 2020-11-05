<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'site_id', 'referer', 'endpoint', 'p256dh', 'auth'
    ];
}
