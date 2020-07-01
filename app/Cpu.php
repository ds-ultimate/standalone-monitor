<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cpu extends Model
{
    protected $fillable = [
        'name',
        'all',
        'user',
        'user_niced',
        'kernel',
        'io_wait',
        'idle',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
    ];

    public function server()
    {
        return $this->belongsTo('App\Server');
    }

}
