<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    protected $fillable = [
        'interface',
        'sent_bytes',
        'received_bytes',
        'sent_packets',
        'received_packets',
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
