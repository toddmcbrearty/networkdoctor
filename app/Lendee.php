<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lendee extends Model
{
    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot'
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
