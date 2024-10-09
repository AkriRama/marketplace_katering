<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Purchase;
use App\Models\Expenses;
use App\Models\Report;
use DB;

use Illuminate\Support\Facades\Session;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request) {
        $report = Report::paginate(8);
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $paginate = $request->paginate;

        if($request->paginate)
        {
            $report = Report::paginate($request->paginate);
        }

        if($request->sortdown)
        {
            $report = Report::orderBy('date', $request->sortdown)->paginate(10);
            Session::put('sortdown', false);
        }
        elseif($request->sortup)
        {
            $report = Report::orderBy('date', $request->sortup)->paginate(10);
            Session::put('sortdown', true);
        }

        // sortdownSales
        elseif($request->sortdownSales)
        {
            $report = Report::orderBy('sales', $request->sortdownSales)->paginate(10);
            Session::put('sortdownSales', false);
        }
        elseif($request->sortupSales)
        {
            $report = Report::orderBy('sales', $request->sortupSales)->paginate(10);
            Session::put('sortdownSales', true);
        }

        // sortdownPurchases
        elseif($request->sortdownPurchases)
        {
            $report = Report::orderBy('purchases', $request->sortdownPurchases)->paginate(10);
            Session::put('sortdownPurchases', false);
        }
        elseif($request->sortupPurchases)
        {
            $report = Report::orderBy('purchases', $request->sortupPurchases)->paginate(10);
            Session::put('sortdownPurchases', true);
        }
        
        // sortdownExpenses
        elseif($request->sortdownExpenses)
        {
            $report = Report::orderBy('expenses', $request->sortdownExpenses)->paginate(10);
            Session::put('sortdownExpenses', false);
        }
        elseif($request->sortupExpenses)
        {
            $report = Report::orderBy('expenses', $request->sortupExpenses)->paginate(10);
            Session::put('sortdownExpenses', true);
        }
        
        // sortdownIncome
        elseif($request->sortdownIncome)
        {
            $report = Report::orderBy('income', $request->sortdownIncome)->paginate(10);
            Session::put('sortdownIncome', false);
        }
        elseif($request->sortupIncome)
        {
            $report = Report::orderBy('income', $request->sortupIncome)->paginate(10);
            Session::put('sortdownIncome', true);
        }
        
        if(isset($request->startDate) && isset($request->endDate))
        {
            $report = Report::whereBetween('date', [$request->startDate, $request->endDate])->paginate(10);
        }
        return view('adminreportincome', ['report' => $report, 'startDate' => $startDate, 'endDate' => $endDate, 'paginate' => $paginate]);
    }

    public function storeReport()
    {
        
        $reportSales = Transaction::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(total) as sum'))
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date', 'DESC')
        ->get();
        
        $reportPurchase = Purchase::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(total) as sum'))
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date', 'DESC')
        ->get();
        
        $reportExpenses = Expenses::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(expense) as sum'))
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date', 'DESC')
        ->get();

        if(isset($reportSales) && count($reportSales) > 0)
        {
            foreach($reportSales as $report)
            {
                $reportData = Report::where('date', $report->date)->first();
                if($reportData)
                {
                    $reportData->sales = $report->sum;
                    $reportData->income = $report->sum;
                    $reportData->update();

                }
                else{
                    $report = new Report([
                        'date' => $report->date,
                        'sales' => $report->sum,
                        'purchases' => 0,
                        'expenses' => 0,
                        'income' => $reportData + $report->sum,
                    ]);
            
                    $report->save();

                }
                
            }
            
        }

        if(isset($reportPurchase) && count($reportPurchase) > 0)
        {

            foreach($reportPurchase as $reports)
            {
                $reportData = Report::where('date', $reports->date)->first();
                if($reportData)
                {
                    $carbonDate = \Carbon\Carbon::parse($reportData->date);
                    $month = $carbonDate->format('m'); 

                    $results = DB::table('reports')
                    ->whereBetween('date', ["2023-10-25", "2023-10-30"])
                    ->get();

                    // dd($results);
                    $reportData->purchases = $reports->sum;
                    $reportData->income = $reportData->income - $reports->sum;
                    $reportData->update();
                    
                }
                else{
                    $report = new Report([
                        'date' => $reports->date,
                        'purchases' => $reports->sum,
                        'income' => 0 - $reports->sum,
                    ]);
            
                    $report->save();

                }
            }
            
        }

        if(isset($reportExpenses) && count($reportExpenses) > 0)
        {
            
            foreach($reportExpenses as $reportss)
            {
                $reportData = Report::where('date', $reportss->date)->first();
                if($reportData)
                {
                    $reportData->expenses = $reportss->sum;
                    $reportData->income = $reportData->income - $reportss->sum;
                    $reportData->update();

                }
                else{
                    $report = new Report([
                        'date' => $reportss->date,
                        'expenses' => $reportss->sum,
                        'income' => 0 - $reportss->sum,
                    ]);
            
                    $report->save();

                }
            }
            
        }

        return redirect('adminproduct');
    }

    public function export(Request $request)
    {
        return Excel::download(new ReportExport($request), 'Report.xlsx');
    }

}
