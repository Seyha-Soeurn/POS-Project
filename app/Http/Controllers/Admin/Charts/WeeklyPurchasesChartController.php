<?php

namespace App\Http\Controllers\Admin\Charts;

use Carbon\Carbon;
use App\Models\Purchase;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Backpack\CRUD\app\Http\Controllers\ChartController;

/**
 * Class WeeklyPurchasesChartController
 * @package App\Http\Controllers\Admin\Charts
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class WeeklyPurchasesChartController extends ChartController
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
        $this->chart->load(backpack_url('charts/weekly-purchases'));

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
        $weeklyPurchases = [];
        for ($subDay=6; $subDay >= 0; $subDay--) {
            if ($subDay == 1) {
                $weeklyPurchases[] = Purchase::whereDate('created_at', Carbon::yesterday())->count();
            } elseif ($subDay == 0) {
                $weeklyPurchases[] = Purchase::whereDate('created_at', Carbon::now())->count();
            } else {
                $weeklyPurchases[] = Purchase::whereDate('created_at', Carbon::now()->subDays($subDay))->count();
            }
        }

        $this->chart->dataset('Purchases', 'line', $weeklyPurchases)
            ->color('#467fd0')
            ->backgroundColor('hsla(215, 78%, 62%, 0.404)');
    }
}