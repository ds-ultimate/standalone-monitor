<?php

namespace App\Http\Controllers;

use App\Server;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $servers = Server::all();
        if ($servers->count() == 0){
            Server::saveOwnServer();
        }
        return view('home', compact('servers'));
    }
}
