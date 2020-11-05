<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'title', 'body', 'image', 'icon', 'badge', 'link'
    ];

    public function clicks()
    {
        return $this->hasMany(Click::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function closes()
    {
        return $this->hasMany(Close::class);
    }
}
