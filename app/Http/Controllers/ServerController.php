<?php

namespace App\Http\Controllers;

use App\Memory;
use App\Server;
use Illuminate\Http\Request;
use Khill\Lavacharts\Lavacharts;

class ServerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Server $server)
    {
        $chart = '';
        $load = $server->loads()->orderBy('updated_at', 'desc')->first();
        $ssh = $server->sshs()->orderBy('updated_at', 'desc')->first();
        $mem = new MemoryChartController();
        $chart .= $mem->memorySteppedAreaChart($server);
        $chart .= $mem->memoryPieChart($server);
        $chart .= $mem->memorySteppedBarChart($server);
        return view('server', compact('server', 'load', 'ssh', 'chart'));
    }

    public function currentLoad(Server $server)
    {
        $load = $server->loads()->orderBy('updated_at', 'desc')->first();
        return response()->json([
            'oneMin' => $load->oneMin,
            'fiveMin' => $load->fiveMin,
            'fifteenMin' => $load->fifteenMin,
            'updated_at' => $load->updated_at->diffForHumans(),
        ]);
    }

    public function currentSsh(Server $server)
    {
        $load = $server->sshs()->orderBy('updated_at', 'desc')->first();
        return response()->json([
            'num_sessions' => $load->num_sessions,
            'updated_at' => $load->updated_at->diffForHumans(),
        ]);
    }

}
