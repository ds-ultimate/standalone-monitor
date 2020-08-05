<?php

namespace App\Http\Controllers;

use App\Memory;
use App\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Khill\Lavacharts\Lavacharts;

class MemoryChartController extends Controller
{

    private $lava;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->lava = new Lavacharts();
        //$this->middleware('auth');
    }

    public function memorySteppedAreaChart(Server $server){
        $memorys = $server->memories()->take(50)->get();
        $chart = $this->lava->DataTable();

        $chart->addDateColumn('Datum')
            ->addNumberColumn('used_programms')
            ->addNumberColumn('used_buffers')
            ->addNumberColumn('used_cache')
            ->addNumberColumn('free');

        foreach ($memorys as $memory){
            $chart->addRow([
                $memory->created_at, $memory->used_programms, $memory->used_buffers, $memory->used_cache, $memory->free,
            ]);
        }

        $this->lava->SteppedAreaChart('memorySteppedAreaChart', $chart, [
            'title' => 'Memory',
            'legend' => [
                'position' => 'top'
            ],
            'vAxis' => [
                'viewWindow' =>[
                    'max' => $memorys[0]->mem_total,
                ],
            ],
            'isStacked'=> true,
        ]);

        return $this->lava->render('SteppedAreaChart', 'memorySteppedAreaChart', 'memorySteppedAreaChart');
    }

    public function memoryPieChart(Server $server){
        $memory = $server->memories()->orderBy('updated_at', 'desc')->first();
        $chart = $this->lava->DataTable();

        $chart->addStringColumn('Datum')
            ->addNumberColumn('mB')
            ->addRow(['used_programms', $memory->used_programms])
            ->addRow(['free', $memory->free + $memory->used_buffers + $memory->used_cache]);

        $this->lava->PieChart('Memory', $chart, [
            'legend' => [
                'position' => 'none'
            ],
            'height' => 100,
        ]);

        return $this->lava->render('PieChart', 'Memory', 'memoryPieChart');
    }

    public function memorySteppedBarChart(Server $server){
        $memory = $server->memories()->orderBy('updated_at', 'desc')->first();
        $chart = $this->lava->DataTable();

        $chart->addStringColumn('Datum')
            ->addNumberColumn('used_programms')
            ->addNumberColumn('free')
            ->addRow(['', $memory->used_programms, $memory->used_buffers+$memory->used_cache+$memory->free]);

        $this->lava->BarChart('Memory', $chart, [
            'legend' => [
                'position' => 'none',
            ],
            'hAxis' => [
                'textPosition' => 'none',
                'gridlines' => [
                    'color' => 'transparent',
                ],
            ],
            'vAxis' => [
                'textPosition' => 'none',
                'gridlines' => [
                    'color' => 'transparent',
                ],
            ],
            'isStacked' => true,
            'height' => 50,
//            'weight' =>'',
        ]);

        return $this->lava->render('BarChart', 'Memory', 'memorySteppedBarChart');
    }

    public function memorySteppedAreaChartJson(Server $server){
        $memorys = $server->memories()->get();
        $chart = $this->lava->DataTable();

        $chart->addDateColumn('Datum')
            ->addNumberColumn('used_programms')
            ->addNumberColumn('used_buffers')
            ->addNumberColumn('used_cache')
            ->addNumberColumn('free');

        foreach ($memorys as $memory){
            $chart->addRow([
                $memory->created_at, $memory->used_programms, $memory->used_buffers, $memory->used_cache, $memory->free,
            ]);
        }

        return $chart->toJson();
    }

}
