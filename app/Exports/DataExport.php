<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;

// class DataExport implements FromCollection
class DataExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()

    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function view(): View
    {
        // return Transaction::all();
        \Log::info($this->request->all());
        $transaction = Transaction::all();
        $totalTransaction = Transaction::sum('total');  
        
        if($this->request->input('search'))
        
        {
            $transaction = Transaction::where('name','like','%'.$this->request->search.'%')->get();
        }
        elseif($this->request->input('Pilihan'))
        {
            $transaction = Transaction::orderBy('created_at', $this->request->Pilihan)->get();
        }

        return view('reporttable', ['transactions' => $transaction, 'totalTransaction' => $totalTransaction]);
    }
}
