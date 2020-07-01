<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;

class Server extends Model
{
    use SoftDeletes;

    public function cpus()
    {
        return $this->hasMany('App\Cpus');
    }

    public function diskIos()
    {
        return $this->hasMany('App\DiskIo');
    }

    public function diskUsages()
    {
        return $this->hasMany('App\DiskUsage');
    }

    public function loads()
    {
        return $this->hasMany('App\Load');
    }

    public function memories()
    {
        return $this->hasMany('App\Memory');
    }

    public function networks()
    {
        return $this->hasMany('App\Network');
    }

    public function sqls()
    {
        return $this->hasMany('App\Sql');
    }

    public function sshs()
    {
        return $this->hasMany('App\Ssh');
    }

    static function saveOwnServer()
    {
        $server = new Server();
        $server->ip = request()->server('SERVER_ADDR');
        $server->url = url('/');
        $server->description = 'Main Server';
        $server->save();
    }

}
