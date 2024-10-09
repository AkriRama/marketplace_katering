<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Payment;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

use App\Exports\DataExport;
use Maatwebsite\Excel\Facades\Excel;

use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\Session;


class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::paginate(10);
        
        if($request->sortdown)
        {
            $transactions = Transaction::orderBy('created_at', $request->sortdown)->paginate(10);
            Session::put('sortdown', false);
        }
        elseif($request->sortup)
        {
            $transactions = Transaction::orderBy('created_at', $request->sortup)->paginate(10);
            Session::put('sortdown', true);
        }
        
        if(isset($request->paginate))
        {
            $transactions = Transaction::paginate($request->paginate);
        }
        foreach ($transactions as $transaction)
        {
            $users = User::where('id', $transaction->user_id)->first();
        }

        if(isset($transaction))
        {
            return view('admintransaction', ['transactions' => $transactions, 'users' => $users]);
        }
        else{
            return view('admintransaction', ['transactions' => $transactions]);
        }

    }

    public function indexUser()
    {
        $transactions = TransactionDetail::where('user_id', Auth::user()->id)->get();
        if($transactions)
        {
            foreach($transactions as $transaction)
            {
                $payments = Payment::where('id', $transaction->method)->first();
            }
            return view('usertransaction', ['transactions' => $transactions, 'payments' => $payments]);
        } 
        else{
            return view('usertransaction', ['transactions' => $transactions]);
        }
    }

    public function checkout()
    {
        
    }

    //history/progress order in service
    public function indexOrderService()
    {
        $transactionsOrder = Transaction::where('user_id', Auth::user()->id)->where('service', 1)->get();
    
        if($transactionsOrder)
        {
            foreach($transactionsOrder as $transaction)
            {
                $payments = Payment::where('id', $transaction->method)->first();
            }
            return view('historyorderservice', ['transactions' => $transactionsOrder, 'payments' => $payments]);
        }
        else{
            return view('historyorderservice', ['transactions' => $transactionsOrder]);
        }
    }
    
    public function detailOrder($orderID)
    {
        $transactionsOrder = Transaction::where('user_id', Auth::user()->id)->where('order_id', $orderID)->get();
        $transactionDetail = TransactionDetail::where('user_id', Auth::user()->id)->where('order_id', $orderID)->first();
    
        if($transactionsOrder)
        {
            foreach($transactionsOrder as $transaction)
            {
                $payments = Payment::where('id', $transaction->method)->first();
            }
            return view('detailorder', ['transactions' => $transactionsOrder, 'payments' => $payments, 'transactionDetail' => $transactionDetail]);
        }
        else{
            return view('detailorder', ['transactions' => $transactionsOrder, 'transactionDetail' => $transactionDetail]);
        }
    }

    public function changeStatusProcess($orderID)
    {
        $transactions = Transaction::where('order_id', $orderID)->first();
        $transactions->status_service = 'Sedang diproses';
        $transactions->save();

        return redirect('dashboard');
    }

    public function changeStatusNotProcess($orderID)
    {
        $transactions = Transaction::where('order_id', $orderID)->first();
        $transactions->status_service = 'Belum diproses';
        $transactions->save();

        return redirect('dashboard');
    }

    public function reportData(Request $request)
    {
        $transaction = Transaction::latest()->paginate(10);
        $totalTransaction = Transaction::sum('total');
        $search = $request->input('search');
        $option = $request->input('Pilihan');
        $sortID = $request->input('sortID');

        if($search)
        {
            $products = Product::all();
            $transaction = Transaction::where('order_id', 'like', '%' . $search . '%')
    ->orWhereHas('products', function ($query) use ($search) {
        $query->where('code_product', 'like', '%' . $search . '%');
    })
    ->paginate(10);
            
        }
        elseif($option)
        {
            $transaction = Transaction::orderBy('created_at', $option)->paginate(10);
        }
        elseif($sortID)
        {
            $transaction = Transaction::orderBy('id', $sortID)->paginate(10);
        }
        return view('report', ['transactions' => $transaction, 'totalTransaction' => $totalTransaction, 'search' => $search, 'option' => $option]);
        
    }

    public function transactionSuccess(Request $request, $order_id)
    {
        $orders = Order::where('order_id', $order_id)->get();
        $order = Order::where('order_id', $order_id)->first();
        $transaction = Transaction::all();
        $disableButton = true;

        if(isset($transaction) && count($transaction) > 0)
        {

            $transactions = Transaction::where('order_id', $order->order_id)->where('product_id', $order->product_id)->first();
            
            //check if $transactions with same order_id and product_id
            if($transactions)
            {
                return back()->with('status','Silahkan buat pesanan baru!');
            }
            else{
                foreach($orders as $item)
                {
                    $products = Product::where('id', $item->product_id)->first();
                    $products->stock = $products->stock - $item->quantity;
                    $products->update();

                    $transactions = new Transaction([
                        'user_id' => auth()->id(),
                        'order_id' => $item->order_id,
                        'product_id' => $item->product_id,
                        'name' => $item->products->name,
                        'price' => $item->products->price,
                        'quantity' => $item->quantity,
                        'total' => $item->total,
                        'totalOrder' => $item->totalOrder,
                    ]);
                    
                    $transactions->save(); 
                }
            }
        }
        else{
            foreach($orders as $item)
            {

                $products = Product::where('id', $item->product_id)->first();
                    
                $products->stock = $products->stock - $item->quantity;
                $products->update();

                $transactions = new Transaction([
                    'user_id' => auth()->id(),
                    'order_id' => $item->order_id,
                    'product_id' => $item->product_id,
                    'name' => $item->products->name,
                    'price' => $item->products->price,
                    'quantity' => $item->quantity,
                    'total' => $item->total,
                    'totalOrder' => $item->totalOrder,
                ]);
                
                $transactions->save(); 
            }
            
        }
            
        return back()->with('message', 'Transaksi Berhasil');
    }

    public function export(Request $request)
    {
        return Excel::download(new DataExport($request), 'data.xlsx');
    }

    public function pdf()
    {
        $transactions = Transaction::all();
        $totalTransaction = Transaction::sum('total');  
        $pdf = PDF::loadView('reporttable', compact('transactions', 'totalTransaction'));
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('Transaction.pdf');
    }

    public function exportPDF(Request $request)
    {
        $orders = Order::all();
        $order = Order::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
        $customWidth = 310;

        for ($i = 1; $i < count($orders); $i++) {
            // Your code for each iteration goes here
            $customWidth = $customWidth + 30;
        }
        
        $cash = $request->cash;
        $change = $request->change;

        $pdf = PDF::loadview('printorder', compact('orders', 'order', 'cash', 'change'));
        $pdf->setPaper(array(0, 0, $customWidth, 600), 'landscape');
        return $pdf->download('penjualan-'.$order->order_id.'.pdf');
    }



}
