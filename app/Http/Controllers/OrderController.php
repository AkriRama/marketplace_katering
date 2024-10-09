<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;    


class OrderController extends Controller
{
    //number 2
    public function index()
    {
        $orders = Order::where('user_id', Auth::user()->id)->where('status', 1)->first();
        $carts = Cart::where('order_id', $orders->order_id)->get();

        $payments = Payment::all();
        return view('confirmationpayment', ['orders' => $orders, 'payments' => $payments, 'carts' => $carts]);
    }

    //number 1
    public function store(Request $request, $slug)
    {
        $quantity = $request->input('quantity');
        $products = Product::where('slug', $slug)->first();
        $total = $quantity*$products->price;

        $payments = Payment::all();

        $orderId = auth()->user()->id.now()->timestamp;
        $orders = new Order ([
            'user_id' => auth()->id(),
            'order_id' => $orderId,
            'product_id' => $products->id,
            'quantity' => $quantity,
            'totalOrder' => $total,
        ]);

        $orders->save();    

        if($products->stock > 0 && $quantity <= $products->stock)
        {
            return redirect('confirmationpayment');
        }
        else{
            return back()->with('status','Stok Kurang!!');
        }
    }
    
    
    //directToBuy -> detailproduct -> buy -> confirmationpayment
    public function storeBuy(Request $request, $slug)
    {
        $quantity = $request->input('quantity');
        $products = Product::where('slug', $slug)->first();
        $total = $quantity*$products->price;
    
        $payments = Payment::all();
    
        $orderId = auth()->user()->id.now()->timestamp;
        $orders = new Order ([
            'user_id' => auth()->id(),
            'order_id' => $orderId,
            'product_id' => $products->id,
            'quantity' => $quantity,
            'total' => $total,
            'totalOrder' => $total,
            'status' => $request->statusBuy,
        ]);
    
        $orders->save();    
    
        if($products->stock > 0 && $quantity <= $products->stock)
        {
            return redirect('confirmationpayment');
        }
        else{
            return back()->with('status','Stok Kurang!!');
        }

    }

    public function purchase(Request $request, $orderId)
    {
        $transactions = Transaction::where('order_id', $orderId)->first();

        if($transactions)
        {
            return view('transactionsuccess');
        }
        $orders = Order::where('order_id', $orderId)->first();
        
        // dd($orders->created_at->format('d-m-Y H:i:s'));
        $carts = Cart::where('order_id', $orderId)->get();
        foreach($carts as $carts)
        {
            if($carts->product_service == "service")
            {
                $services = Service::where('id', $carts->service_id)->first();
                $transactions = new Transaction([
                    'user_id' => auth()->id(),
                    'order_id' => $orderId,
                    'service_id' => $carts->service_id,
                    'name' => $services->name,
                    'price' => $services->price,
                    'quantity' => $carts->quantity,
                    'total' => $carts->total,
                    'totalOrder' => $orders->totalOrder,
                    'method' => $request->payment,
                    'service' => "1",
                    'status_service' => "Belum diproses",
                ]);
            
                $transactions->save();
                

            }
            
            else{
                $products = Product::where('id', $carts->product_id)->first();
                $transactions = new Transaction([
                    'user_id' => auth()->id(),
                    'order_id' => $orderId,
                    'product_id' => $carts->product_id,
                    'name' => $products->name,
                    'price' => $products->price,
                    'quantity' => $carts->quantity,
                    'total' => $carts->total,
                    'totalOrder' => $orders->totalOrder,
                    'method' => $request->payment,
                ]);
            
                $transactions->save();
                

            }
        }

        $transactionDetail = new TransactionDetail([
            'user_id' => auth()->id(),
            'order_id' => $transactions->order_id,
            'totalOrder' => $transactions->totalOrder,
            'method' => $transactions->method,
        ]);
    
        $transactionDetail->save();
        
        return view('transactionsuccess');
    }
    
    //cashier
    public function storeCashier(Request $request, $slug)
    {
        $quantity = $request->quantity;
        $products = Product::where('slug', $slug)->first();
        $total = $quantity*$products->price;
        
        $orderId = auth()->user()->id.now()->timestamp;
        $orderCheck = Order::where('user_id', Auth::user()->id)->first();
        $checkOrderProduct = Order::where('user_id', Auth::user()->id)->where('product_id', $products->id)->first();
        $orderSum = Order::sum('total');
        
        if(empty($orderCheck->order_id))
        {
            $orders = new Order([
                'user_id' => auth()->id(),
                'order_id' => $orderId,
                'product_id' => $products->id,
                'quantity' => $quantity,
                'total' => $total,
                'totalOrder' => $total,

            ]);
            
            $orders->save();  
        }
        else{

            if(empty($checkOrderProduct))
            {
                
                $orders = new Order([
                    'user_id' => auth()->id(),
                    'order_id' => $orderCheck->order_id,
                    'product_id' => $products->id,
                    'quantity' => $quantity,
                    'total' => $total,
                    'totalOrder' => $orderSum + $total,
                ]);
                
                $orders->save();  
            }
            else{
                $orderSum = Order::sum('total');
                $orders = Order::where('user_id', Auth::user()->id)->where('product_id', $products->id)->first();
                $orders->quantity = $quantity;
                $orders->total = $total;
                $orders->totalOrder = $orderSum;
        
                $orders->update();  
    
                return back()->with('message', 'Jumlah barang pembelian berhasil diperbarui');
            }
             
        }        
       
        return redirect('cashier')->with('message', 'Barang berhasil ditambahkan ke pesanan');
    }

    public function refreshCashier()
    {
        // $orders = Order::where('user_id', Auth::user()->id)->get();
        $orders = Order::all();

        foreach($orders as $order)
        {
            $order->delete();
        }

        return redirect('cashier');
    }
}
