<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];
    public function lendee()
    {
        return $this->belongsTo(Lendee::class)->select('id', 'name', 'relation');
    }
}
