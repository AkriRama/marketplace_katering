<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderBuy;
use App\Models\Purchase;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;    

use PDF;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $purchases = Purchase::paginate(10);
        
        if($request->sortdown)
        {
            $purchases = Purchase::orderBy('created_at', $request->sortdown)->paginate(10);
            Session::put('sortdown', false);
        }
        elseif($request->sortup)
        {
            $purchases = Purchase::orderBy('created_at', $request->sortup)->paginate(10);
            Session::put('sortdown', true);
        }
        
        if(isset($request->paginate))
        {
            $purchases = Purchase::paginate($request->paginate);
        }
        // $purchases = Purchase::all();
        foreach ($purchases as $transaction)
        {
            $users = User::where('id', $transaction->user_id)->first();
        }
        return view('admintransactionpurchases', ['purchases' => $purchases, 'users' => $users]);
    }

    public function purchases(Request $request)
    {
        $product = Product::paginate(10);
        $orderbuy = OrderBuy::all();
        $lastOrderBuy = OrderBuy::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
        $categories = Category::all();
     
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
            if(empty($emptyProduct))
            {
                return back()->with('status','Barang yang kamu cari tidak tersedia');
            } 

            $products = Product::where('code_product', $request->code_product)->first();
            
            if($request->quantity)
            {
                
                $quantity = $request->quantity;
                $total = $products->price * $quantity;
                $orderbuy = OrderBuy::where('user_id', Auth::user()->id)->get();

                return view('purchaseproduct', ['product' => $product, 'products' => $products, 'quantity' => $quantity, 'total' => $total, 'orders' => $orderbuy, 'order' => $lastOrderBuy, 'categories' => $categories]);
            }
            return view('purchaseproduct', ['product' => $product, 'products' => $products, 'orders' => $orderbuy, 'order' => $lastOrderBuy, 'categories' => $categories]);
        }

        

        if(isset($lastOrderBuy))
        {
            $lastOrderBuy = OrderBuy::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
            $purchases = Purchase::where('purchase_id', $lastOrderBuy->purchase_id)->first();

            if($request->cash)
            {
                $cash = $request->cash;
                if($cash < $lastOrderBuy->totalOrder)
                {
                    return back()->with('status', 'Jumlah pembayaran kurang, silahkan ulangi!!');
                }
                else{
                    $changeCash = $cash - $lastOrderBuy->totalOrder;
                    
                    return view('purchaseproduct', ['product' => $product, 'orders' => $orderbuy, 'order' => $lastOrderBuy, 'cash' => $cash, 'change' => $changeCash, 'purchases' => $purchases, 'categories' => $categories]);
                }
            }
            return view('purchaseproduct', ['product' => $product, 'orders' => $orderbuy, 'order' => $lastOrderBuy, 'purchases' => $purchases, 'categories' => $categories]);
        }
        return view('purchaseproduct', ['product' => $product, 'orders' => $orderbuy, 'order' => $lastOrderBuy, 'categories' => $categories]);
    }


    public function storePurchase(Request $request, $slug)
    {
        $quantity = $request->quantity;
        $products = Product::where('slug', $slug)->first();
        $total = $quantity*$products->price;
        
        $orderBuyCheck = OrderBuy::where('user_id', Auth::user()->id)->first();
        $checkOrderBuyProduct = OrderBuy::where('user_id', Auth::user()->id)->where('product_id', $products->id)->first();
        $orderBuySum = OrderBuy::sum('total');
        $purchaseID = "PALJ".time(); 

        if(empty($orderBuyCheck->product_id))
        {
            $orderbuy = new OrderBuy([
                'user_id' => auth()->id(),
                'purchase_id' => $purchaseID,
                'product_id' => $products->id,
                'quantity' => $quantity,
                'total' => $total,
                'totalOrder' => $total,
            ]);
            
            $orderbuy->save();  
        }
        else{
            
            if(empty($checkOrderBuyProduct))
            {
                
                $orderbuy = new OrderBuy([
                    'user_id' => auth()->id(),
                    'purchase_id' => $orderBuyCheck->purchase_id,
                    'product_id' => $products->id,
                    'quantity' => $quantity,
                    'total' => $total,
                    'totalOrder' => $orderBuySum + $total,
                ]);
                
                $orderbuy->save();  
            }
            else{
                $orderbuy = OrderBuy::where('user_id', Auth::user()->id)->where('product_id', $products->id)->first();
                $orderbuy->quantity = $quantity;
                $orderbuy->total = $total;
                $orderbuy->totalOrder = $orderBuySum;
        
                $orderbuy->update();  

                return back()->with('message', 'Jumlah barang pembelian berhasil diperbarui');
            }
        }
                 
       
        return redirect('purchaseproduct')->with('message', 'Barang berhasil ditambahkan ke pembelian');
    }


    public function refreshPurchase()
    {
        $orderbuy = OrderBuy::where('user_id', Auth::user()->id)->get();

        foreach($orderbuy as $order)
        {
            $order->delete();
        }

        return redirect('purchaseproduct');
    }


    public function purchaseSuccess(Request $request, $purchase_id)
    {
        $orders = OrderBuy::where('purchase_id', $purchase_id)->get();
        $order = OrderBuy::where('purchase_id', $purchase_id)->first();
        $purchase = Purchase::all();
        
        
        if(isset($purchase) && count($purchase) > 0)
        {
            
            $purchases = Purchase::where('purchase_id', $order->purchase_id)->where('product_id', $order->product_id)->first();
            
            //check if $transactions with same order_id and product_id
            if($purchases)
            {
                return back()->with('status','Silahkan buat pembelian baru!');
            }
            else{
                foreach($orders as $item)
                {
                    $purchases = new Purchase([
                        'user_id' => auth()->id(),
                        'purchase_id' => $item->purchase_id,
                        'product_id' => $item->product_id,
                        'name' => $item->products->name,
                        'price' => $item->products->price,
                        'quantity' => $item->quantity,
                        'total' => $item->total,
                    ]);
                    
                    $purchases->save(); 
                }
            }
        }
        else{
            foreach($orders as $item)
            {
                $purchases = new Purchase([
                    'user_id' => auth()->id(),
                    'purchase_id' => $item->purchase_id,
                    'product_id' => $item->product_id,
                    'name' => $item->products->name,
                    'price' => $item->products->price,
                    'quantity' => $item->quantity,
                    'total' => $item->total,
                ]);
                
                $purchases->save(); 
            }

            
        }
        
        foreach($orders as $order)
        {
            $products = Product::where('id', $order->product_id)->first();
            
            $products->stock = $products->stock + $order->quantity;
            $products->update();

        }
            
        return back()->with('message', 'Transaksi Pembelian Berhasil');
    }

    public function exportPDF(Request $request)
    {
        $orders = OrderBuy::all();
        $order = OrderBuy::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
        $customWidth = 310;

        for ($i = 1; $i < count($orders); $i++) {
            // Your code for each iteration goes here
            $customWidth = $customWidth + 30;
        }
        
        $cash = $request->cash;
        $change = $request->change;

        $pdf = PDF::loadview('printpurchase', compact('orders', 'order', 'cash', 'change'));
        $pdf->setPaper(array(0, 0, $customWidth, 600), 'landscape');
        return $pdf->download('pembelian-'.$order->purchase_id.'.pdf');
    }

}
