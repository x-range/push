<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    public function subscribers()
    {
        return $this->hasMany(Subscriber::class)->whereNull('unsubscribed_at');
    }

    public function unsubscribers()
    {
        return $this->hasMany(Subscriber::class)->whereNotNull('unsubscribed_at');
    }
}
