<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;
use App\Models\Report;

class ReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     //
    // }

    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function view(): View
    {
        
        \Log::info($this->request->all());
        $report = Report::paginate(8);

        if($this->request->sortdown)
        {
            $report = Report::orderBy('date', $this->request->sortdown)->paginate(10);
        }
        elseif($this->request->sortup)
        {
            $report = Report::orderBy('date', $this->request->sortup)->paginate(10);
        }

        // sortdownSales
        elseif($this->request->sortdownSales)
        {
            $report = Report::orderBy('sales', $this->request->sortdownSales)->paginate(10);
            Session::put('sortdownSales', false);
        }
        elseif($this->request->sortupSales)
        {
            $report = Report::orderBy('sales', $this->request->sortupSales)->paginate(10);
            Session::put('sortdownSales', true);
        }

        // sortdownPurchases
        elseif($this->request->sortdownPurchases)
        {
            $report = Report::orderBy('purchases', $this->request->sortdownPurchases)->paginate(10);
            Session::put('sortdownPurchases', false);
        }
        elseif($this->request->sortupPurchases)
        {
            $report = Report::orderBy('purchases', $this->request->sortupPurchases)->paginate(10);
            Session::put('sortdownPurchases', true);
        }
        
        // sortdownExpenses
        elseif($this->request->sortdownExpenses)
        {
            $report = Report::orderBy('expenses', $this->request->sortdownExpenses)->paginate(10);
            Session::put('sortdownExpenses', false);
        }
        elseif($this->request->sortupExpenses)
        {
            $report = Report::orderBy('expenses', $this->request->sortupExpenses)->paginate(10);
            Session::put('sortdownExpenses', true);
        }
        
        // sortdownIncome
        elseif($this->request->sortdownIncome)
        {
            $report = Report::orderBy('income', $this->request->sortdownIncome)->paginate(10);
            Session::put('sortdownIncome', false);
        }
        elseif($this->request->sortupIncome)
        {
            $report = Report::orderBy('income', $this->request->sortupIncome)->paginate(10);
            Session::put('sortdownIncome', true);
        }

        if($this->request->paginate)
        {
            $report = Report::paginate($this->request->paginate);
        }
        
        if(isset($this->request->startDate) && isset($this->request->endDate))
        {
            $report = Report::whereBetween('date', [$this->request->startDate, $this->request->endDate])->paginate(10);
        }

        return view('adminproducttable', ['report' => $report]);
    }

}
