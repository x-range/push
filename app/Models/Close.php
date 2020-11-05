<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Close extends Model
{
    protected $fillable = [
        'message_id', 'subscriber_id'
    ];
}
