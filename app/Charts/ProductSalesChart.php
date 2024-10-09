<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\BarChart;
use ArielMejiaDev\LarapexCharts\BarChart as LarapexAreaChart;
use App\Models\Transaction;
use DB;

class ProductSalesChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): BarChart
    {
        $reportSales = Transaction::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(total) as sum'))
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date', 'DESC')
        ->get();

        $year = date('Y'); // Get the current year
        // $productSales = Transaction::all();
        $productSales = Transaction::select(DB::raw('name'), DB::raw('SUM(quantity) as sum'))
        ->groupBy('name')
        ->get();


        $totalProductSalesName = $productSales->pluck('name')->toArray();
        $totalProductSales = $productSales->pluck('sum')->toArray();
        // $labels = $productSales->pluck('name')->toArray();
        
        $labels = $reportSales->pluck('date')->toArray();

        return $this->chart->barChart()
            ->setTitle('Grafik Penjualan Barang per Jenis Barang')
            ->setSubtitle($year)
            ->addData('Barang', $totalProductSales)
            // ->addData('Boston', [7, 3, 8, 2, 6, 4])
            ->setXAxis($totalProductSalesName);
    }
}
