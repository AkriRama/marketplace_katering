<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;    

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        $deletedProducts = Product::onlyTrashed()->get();
        $categories = Category::all();
        return view('adminproduct', ['products' => $products, 'deletedProducts' => $deletedProducts, 'categories' => $categories]);
    }

    public function add()
    {
        $categories = Category::all();
        return view('addproduct', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_product' => 'required|unique:products',
            'name' => 'required|max:255',
        ]);

        $newName = '';
        if($request->file('image')){
            $extension = $request->file('image')->getClientOriginalExtension();
            $newName = $request->name.'-'.now()->timestamp.'.'.$extension;
            $request->file('image')->storeAs('cover', $newName);
        }

        $request['cover'] = $newName;
        $product = Product::create($request->all());
        $product->categories()->sync($request->categories);
        return redirect('adminproduct')->with('status','Barang Berhasil Ditambahkan');
    }
    
    public function edit($slug)
    {
        $product = Product::where('slug', $slug)->first();
        $categories = Category::all();
        return view('editproduct', ['product'=> $product], ['categories' => $categories]);
    }
    
    public function update(Request $request, $slug)
    {
        $validated = $request->validate([
            'name' => 'max:255',
        ]);
        $product = Product::where('slug', $slug)->first();

        
        $newName = '';
        if($request->file('image')){
            $extension = $request->file('image')->getClientOriginalExtension();
            $newName = $request->name.'-'.now()->timestamp.'.'.$extension;
            $request->file('image')->storeAs('cover', $newName);

            $product = $request['cover'] = $newName;

        }
        elseif ($request->has('old_image')) {
            $product->cover = $request->old_image;
        }
        
        $product = Product::where('slug', $slug)->first();
        $product->name=null;
        $product->slug=null;
        
        $product->update($request->all());
        return redirect('adminproduct')->with('status','Barang Berhasil Diperbarui');
    }

    public function delete1($slug)
    {
        $product = Product::where('slug', $slug)->first();
        $product->delete();
        return redirect('adminproduct')->with('status', 'Product Deleted Successfully'); 
    }

    public function delete($slug)
    {
        $products = Product::where('slug', $slug)->first();
        return view('deleteproduct', ['products' => $products]);
    }
    
    public function destroy($slug)
    {
        $products = Product::where('slug', $slug)->first();
        $products->delete();
        return redirect('adminproduct')->with('status', 'Barang Berhasil Dihapus');
    }

    public function deletedShow()
    {
        $deletedProducts = Product::onlyTrashed()->get();
        return view('deletedproduct', ['deletedProducts' => $deletedProducts]);
    }

    public function restore($slug)
    {
        $products = Product::withTrashed()->where('slug', $slug)->first();
        $products->restore();
        return redirect('adminproduct')->with('status', 'Barang Berhasil Dipulihkan');
    }

    public function forceDelete($slug)
    {
        $products = Product::withTrashed()->where('slug', $slug)->first();
        return view('forcedeleteproduct', ['products' => $products]);
    }

    public function forceDestroy($slug)
    {
        $products = Product::onlyTrashed()->where('slug', $slug)->first();
        $products->forcedelete();
        return redirect('deletedproduct')->with('status', 'Data Barang Terhapus Berhasil Dihapus Secara Permanen');
    }

    //productUser

    public function indexUser(Request $request)
    {
        $categories = Category::all();

        if ($request->search) {
            $products = Product::where('name','like','%'.$request->search.'%')->paginate(8);
        }
        elseif($request->input('category')){
            $products = Product::whereHas('categories', function($q) use($request){
                $q->where('categories.id', $request->input('category'));
            })->paginate(8);
            
            $selectedCategory = request('category');
        }
        else{
            $products = Product::paginate(8);
        }
        return view('product', ['products' => $products,'categories' => $categories]);
    }

    public function detailproduct($slug)
    {
        $products = Product::where('slug', $slug)->first();
        return view('detailproduct', ['products' => $products]);
    }

    public function searchItem(Request $request)
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
            // $products = Product::where('id','like','%'.$request->id.'%')->first();
            
            if($products->stock > 0)
            {
                if($request->quantity)
                {
                    if($request->quantity <= $products->stock)
                    {
                        $quantity = $request->quantity;
                        $total = $products->price * $quantity;
                        $orders = Order::where('user_id', Auth::user()->id)->get();
                        
                        return view('cashier', ['product' => $product, 'products' => $products, 'quantity' => $quantity, 'total' => $total, 'orders' => $orders, 'order' => $lastOrder]);
                    }
                    elseif($request->quantity > $products->stock)
                    {
                        return redirect('cashier')->with('status', 'Maaf persediaan barang tidak mencukupi');
                    }
                }

            }
            else{
                return redirect('cashier')->with('status','Persediaan barang habis');
            }
            return view('cashier', ['product' => $product, 'products' => $products, 'orders' => $orders, 'order' => $lastOrder]);
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
                    return view('cashier', ['product' => $product, 'orders' => $orders, 'order' => $lastOrder, 'cash' => $cash, 'change' => $changeCash, 'transactions' => $transaction]);
                }
            }
            return view('cashier', ['product' => $product, 'orders' => $orders, 'order' => $lastOrder, 'transactions' => $transactions]);
        }
        return view('cashier', ['product' => $product, 'orders' => $orders, 'order' => $lastOrder]);
    }

    public function indexCashier(Request $request)
    {
        if($request->search)
        {
            $product = Product::where('name','like','%'.$request->search.'%')->orWhere('code_product','like','%'.$request->search.'%')->paginate(8);
        }
        else{
            $product = Product::paginate(10);
        }

        return view('productcashier', ['products' => $product]);
    }

    public function modal()
    {
        return view('modalproduct');
    }
}
