<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiskUsage extends Model
{
    protected $fillable = [
        'diskname',
        'mounted_at',
        'kbytes_all',
        'kbytes_used',
        'kbytes_reserved',
        'inodes_all',
        'inodes_used',
    ];
    
    protected $dates = [
        'updated_at',
        'created_at',
    ];
}
