<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiskIo extends Model
{
    protected $fillable = [
        'diskname',
        'read_io',
        'read_sector',
        'write_io',
        'write_sector',
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
