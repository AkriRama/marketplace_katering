<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
// use DB;

use App\Models\Cart;
use App\Models\Slider;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Payment;

use Illuminate\Database\Eloquent\Collection;

class PublicController extends Controller
{

    public function confirmation(Request $request, $id)
    {
        $quantity = $request->input('quantity');
        $products = Product::where('id', $id)->first();
        $products->stock -= $quantity;
        $products->update();
        $orders = Order::where('product_id', $id)->first();

        $payments = Payment::where('id', $request->payment)->first();


        dd($payments->id.now()->timestamp);


        $transactions = new Transaction ([
            'user_id' => auth()->user()->id,
            'order_id' => $orders->order_id,
            'code_product' => $products->code_product,
            'name_product' => $products->name,
            'price' => $products->price,
            'quantity' => $quantity,
            'total' => $quantity * $products->price,
            'method' => $payments->name,

        ]);

            return redirect('product')->with('status','Harap Bayar');
    }

    public function success()
    {
        return view('transactionsuccess');
    }

    public function home(Request $request)
    {
        $sliders = Slider::all();
        $product = Product::where('discount', '>', 0)->get();
       
        $discountCount = count($product);
        $products = Product::limit($discountCount)->get();
        $services = DB::table('services')->limit(5)->get();
        return view('home', ['products' => $products, 'product' => $discountCount, 'sliders' => $sliders, 'services' => $services]);
    }

    public function print()
    {
        return view('printtransaction');
    }

    public function indexService(Request $request)
    {
        if ($request->title) {
            $services = Service::where('name','like','%'.$request->title.'%')->paginate(8);
        }
        else{
            $services = Service::paginate(8);
        }


        return view('service', ['services' => $services]);
    }


    public function export()
    {
        return Excel::download();
    }
}

