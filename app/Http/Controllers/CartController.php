<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;    

class CartController extends Controller
{
    //cartShow -> index
    public function index(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        $cart = Cart::where('user_id', Auth::user()->id)->first();
        $orders = Order::where('user_id', Auth::user()->id)->first();
        
        return view('detailtransaction', ['cart' => $carts, 'orders' => $orders]);
    }

    //cart -> store
    public function store(Request $request, $slug)
    {
        $products = Product::where('slug', $slug)->first();

        $quantity = $request->input('quantity');
        $total = $quantity * $products->price;
        
        $orders = Order::where('user_id', auth()->id())->first();
        
        $orderId = auth()->user()->id.now()->timestamp;
        
        $users = Auth::user();
        $existingCartItem = Cart::where('product_id', $products->id)->where('user_id', $users->id)->first();
        
        $cartOrder = Cart::where('user_id', Auth::user()->id)->first();
        
        if($existingCartItem) {
            return redirect('detailtransaction');
        }

        //check order_id is empty if not it will redirect to else
        if(empty($cartOrder->order_id))
        {
            $cartItem = new Cart([
                'order_id' => $orderId,
                'product_id' => $products->id,
                'name' => $products->name,
                'price' => $products->price,
                'quantity' => $request->input('quantity'),
                'total' => $total,
                'user_id' => auth()->id() 
            ]);
            
            
            $cartItem->save();
        }else{
            $cartItem = new Cart([
                'order_id' => $cartOrder->order_id,
                'product_id' => $products->id,
                'name' => $products->name,
                'price' => $products->price,
                'quantity' => $request->input('quantity'),
                'total' => $total,
                'user_id' => auth()->id()
            ]);
            
            
            $cartItem->save();

        }
        
        //ordersSavetoDatabase -> checkout -> confirmationpayment
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        $cartOrder = Cart::where('user_id', Auth::user()->id)->first();
        
        $totalOrder = $carts->sum('total');

        if(empty($orders))
        {
            $orders = new Order ([
                'user_id' => auth()->id(),
                'order_id' => $orderId,
                'quantity' => $quantity,
                'total' => $total,
                'totalOrder' => $totalOrder,
            ]);
            
            $orders->save();    
        }
        else{
            $orders = Order::where('user_id', auth()->id())->where('order_id', $cartOrder->order_id)->first();

            $orders->totalOrder = null;
            $orders->totalOrder = $totalOrder;
            $orders->update();
        }
        return redirect('detailtransaction');
    }
    
    public function storeService(Request $request, $slug)
    {
        $services = Service::where('slug', $slug)->first();

        $quantity = $request->input('quantity');
        $total = $quantity * $services->price;
        
        $orders = Order::where('user_id', auth()->id())->first();
        
        $orderId = auth()->user()->id.now()->timestamp;
        
        $users = Auth::user();
        $existingCartItem = Cart::where('service_id', $services->id)->where('user_id', $users->id)->first();
        
        $cartOrder = Cart::where('user_id', Auth::user()->id)->first();
        
        if($existingCartItem) {
            return redirect('detailtransaction');
        }

        
        //check order_id is empty, if not, it will redirect to else
        if(empty($cartOrder->order_id))
        {
            $cartItem = new Cart([
                'order_id' => $orderId,
                'service_id' => $services->id,
                'name' => $services->name,
                'price' => $services->price,
                'quantity' => $request->input('quantity'),
                'total' => $total,
                'product_service' => $request->input('order'),
                'user_id' => auth()->id() 
            ]);
            
            
            $cartItem->save();
        }else{
            $cartItem = new Cart([
                'order_id' => $cartOrder->order_id,
                'service_id' => $services->id,
                'name' => $services->name,
                'price' => $services->price,
                'quantity' => $request->input('quantity'),
                'total' => $total,
                'product_service' => $request->input('order'),
                'user_id' => auth()->id()
            ]);
            
            
            $cartItem->save();

        }
        
        //ordersSavetoDatabase -> checkout -> confirmationpayment
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        $cartOrder = Cart::where('user_id', Auth::user()->id)->first();
        
        $totalOrder = $carts->sum('total');

        if(empty($orders) && $request->order == "service")
        {
            $orders = new Order ([
                'user_id' => auth()->id(),
                'order_id' => $orderId,
                'quantity' => $quantity,
                'total' => $total,
                'totalOrder' => $totalOrder,
            ]);
            
            $orders->save();    
        }
        else{
            $orders = Order::where('user_id', auth()->id())->where('order_id', $cartOrder->order_id)->first();

            $orders->totalOrder = null;
            $orders->totalOrder = $totalOrder;
            $orders->update();
        }
        return redirect('detailtransaction');
    }

    public function deleteCart($id)
    {
        $carts = Cart::where('product_id', $id)->first();
        $cartCount = Cart::where('user_id', Auth::user()->id)->get();
        $cartOrder = Cart::where('user_id', Auth::user()->id)->first();
        
        $orders = Order::where('user_id', auth()->user()->id)->first();
        
        if($carts)
        {
            $carts->delete();
            $cartSum = Cart::where('user_id', Auth::user()->id)->get();
            $totalOrder = $cartSum->sum('total');
            $orders = Order::where('user_id', auth()->id())->where('order_id', $cartOrder->order_id)->first();
            
            $orders->totalOrder = null;
            $orders->totalOrder = $totalOrder;
            $orders->update();
            if(count($cartCount) == 1)
            {
                $orders->delete();
            }
        }
        
        return redirect('detailtransaction');
    }
    
    public function deleteCartService($id)
    {
        $carts = Cart::where('service_id', $id)->first();
        $cartCount = Cart::where('user_id', Auth::user()->id)->get();
        $cartOrder = Cart::where('user_id', Auth::user()->id)->first();
        
        $orders = Order::where('user_id', auth()->user()->id)->first();
        
        if($carts)
        {
            $carts->delete();
            $cartSum = Cart::where('user_id', Auth::user()->id)->get();
            $totalOrder = $cartSum->sum('total');
            $orders = Order::where('user_id', auth()->id())->where('order_id', $cartOrder->order_id)->first();
            
            $orders->totalOrder = null;
            $orders->totalOrder = $totalOrder;
            $orders->update();
            if(count($cartCount) == 1)
            {
                $orders->delete();
            }
        }
        
        return redirect('detailtransaction');
    }

    public function checkout($orderId)
    {
        $orders = Order::where('user_id', Auth::user()->id)->where('order_id', $orderId)->first();

        $orders->status = '1';
        $orders->update();
        
        return redirect('confirmationpayment');
    }
}
