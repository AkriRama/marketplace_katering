<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Report;
use App\Charts\ReportChart;
use App\Charts\ProductSalesChart;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use DB;

class DashboardController extends Controller
{
    public function index(ReportChart $chart, ProductSalesChart $chartProduct)
    {
        $productCount = DB::table('products')->count();
        $userCount = User::count();
        $serviceCount = Service::count();
        $transactionCount = Transaction::count();

        $transactionsOrder = Transaction::where('service', 1)->get();
        $payments = Payment::all();
    

        if($transactionsOrder)
        {
            foreach($transactionsOrder as $transaction)
            {
                $payments = Payment::where('id', $transaction->method)->first();
            }
        }
        
        return view('dashboard', ['product_count' => $productCount, 'user_count' => $userCount, 'service_count' => $serviceCount, 'transaction_count' => $transactionCount, 'transactions' => $transactionsOrder, 'payments' => $payments, 'chart' => $chart->build(), 'chartProduct' => $chartProduct->build()]);
    }
}
