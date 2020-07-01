<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ssh extends Model
{
    protected $fillable = [
        'num_sessions',
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
