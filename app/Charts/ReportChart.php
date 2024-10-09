<?php

namespace App\Charts;
use App\Models\Report;
;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\AreaChart;
use ArielMejiaDev\LarapexCharts\AreaChart as LarapexAreaChart;

class ReportChart
{
    protected $chart;

    public function __construct(AreaChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): AreaChart
    {
        $year = date('Y'); // Get the current year
        $month = date('m'); // Get the current month
        $reportData = Report::whereMonth('date', $month)->get(); // Filter by the year

        $pendapatanData = $reportData->pluck('income')->toArray();
        $labels = $reportData->pluck('date')->toArray();

        return $this->chart->areaChart()
            ->setTitle('Grafik Data Pendapatan')
            ->setSubtitle($month)
            ->addData('Pendapatan', $pendapatanData)
            ->setXAxis($labels);
        }   

}
