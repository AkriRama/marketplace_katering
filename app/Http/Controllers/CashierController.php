<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;    


class CashierController extends Controller
{
    public function index(Request $request)
    {
        $product = Product::paginate(10);
        $orders = Order::all();
        $lastOrder = Order::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
     
        $search = $request->input('search');
        if ($search) {
            $product = Product::where('name','like','%'.$search.'%')->paginate(8);
        }
        if($request->input('from_modal')) {
            session(['from_modal' => true]);
        }

        
        $cash = $request->cash;
        if ($request->code_product) {
            
            $emptyProduct = Product::where('code_product', $request->code_product)->first();
            $products = Product::where('code_product', $request->code_product)->first();
            
            if(empty($emptyProduct))
            {
                return back()->with('status','Barang yang kamu cari tidak tersedia');
            }
            
            if($products->stock > 0)
            {
                if($request->quantity)
                {
                    if($request->quantity <= $products->stock)
                    {
                        $quantity = $request->quantity;
                        $total = $products->price * $quantity;
                        $orders = Order::where('user_id', Auth::user()->id)->get();
                        
                        return view('admincashier', ['product' => $product, 'products' => $products, 'quantity' => $quantity, 'total' => $total, 'orders' => $orders, 'order' => $lastOrder]);
                    }
                    elseif($request->quantity > $products->stock)
                    {
                        return redirect('admincashier')->with('status', 'Maaf persediaan barang tidak mencukupi');
                    }
    
                }

            }
            else{
                return redirect('admincashier')->with('status','Persediaan barang habis');
            }
            return view('admincashier', ['product' => $product, 'products' => $products, 'orders' => $orders, 'order' => $lastOrder]);
        }

        

        if(isset($lastOrder))
        {
            $lastOrder = Order::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
            $transactions = Transaction::where('order_id', $lastOrder->order_id)->first();


            if($request->cash)
            {
                $cash = $request->cash;
                if($cash < $lastOrder->totalOrder)
                {
                    return back()->with('status', 'Jumlah pembayaran kurang, silahkan ulangi!!');
                }
                else{
                    $changeCash = $cash - $lastOrder->totalOrder;
                    $transaction = Transaction::where('user_id', Auth::user()->id)->where('order_id', $lastOrder->order_id)->first();
                    return view('admincashier', ['product' => $product, 'orders' => $orders, 'order' => $lastOrder, 'cash' => $cash, 'change' => $changeCash, 'transactions' => $transaction]);
                }
            }
            return view('admincashier', ['product' => $product, 'orders' => $orders, 'order' => $lastOrder, 'transactions' => $transactions]);
        }
        return view('admincashier', ['product' => $product, 'orders' => $orders, 'order' => $lastOrder]);
    }

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
       
        return redirect('admincashier')->with('message', 'Barang berhasil ditambahkan ke pesanan');
    }

    public function refreshCashier()
    {
        $orders = Order::where('user_id', Auth::user()->id)->get();

        foreach($orders as $order)
        {
            $order->delete();
        }

        return back();
    }
}
