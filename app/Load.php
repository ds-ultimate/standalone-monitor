<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Load extends Model
{
    protected $fillable = [
        'oneMin',
        'fiveMin',
        'fifteen',
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
