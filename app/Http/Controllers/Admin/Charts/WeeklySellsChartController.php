<?php

namespace App\Http\Controllers\Admin\Charts;

use Carbon\Carbon;
use App\Models\Order;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Backpack\CRUD\app\Http\Controllers\ChartController;

/**
 * Class WeeklySellsChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class WeeklySellsChartController extends ChartController
{
    public function setup()
    {
        $this->chart = new Chart();

        // MANDATORY. Set the labels for the dataset points
        $labels = [];
        for ($subDay=6; $subDay >= 0; $subDay--) {
            if ($subDay == 1) {
                $labels[] = 'Yesterday';
            } elseif ($subDay == 0) {
                $labels[] = 'Today';
            } else {
                $labels[] = $subDay . " days ago";
            }
        }
        $this->chart->labels($labels);

        // RECOMMENDED. Set URL that the ChartJS library should call, to get its data using AJAX.
        $this->chart->load(backpack_url('charts/weekly-sells'));

        // OPTIONAL
        // $this->chart->minimalist(false);
        // $this->chart->displayLegend(true);
    }

    /**
     * Respond to AJAX calls with all the chart data points.
     *
     * @return json
     */
    public function data()
    {
        $weeklySells = [];
        for ($subDay=6; $subDay >= 0; $subDay--) {
            if ($subDay == 1) {
                $weeklySells[] = Order::whereDate('created_at', Carbon::yesterday())->count();
            } elseif ($subDay == 0) {
                $weeklySells[] = Order::whereDate('created_at', Carbon::now())->count();
            } else {
                $weeklySells[] = Order::whereDate('created_at', Carbon::now()->subDays($subDay))->count();
            }
        }

        $this->chart->dataset('Sells', 'line', $weeklySells)
            ->color('#7c69ef')
            ->backgroundColor('hsla(249, 81%, 72%, 0.401)');
    }
}