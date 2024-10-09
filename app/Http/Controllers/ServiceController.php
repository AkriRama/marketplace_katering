<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Product;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        $products = Product::all();
        $deletedServices = Service::onlyTrashed()->get();
        return view('adminservice', ['services' => $services, 'products' => $products, 'deletedServices' => $deletedServices]);
    }

    public function changeAvailable($slug)
    {
        $services = Service::where('slug', $slug)->first();
        $services->status = 'available';
        $services->save();

        return redirect('adminservice');
    }
    
    public function changeNotAvailable($slug)
    {
        $services = Service::where('slug', $slug)->first();
        $services->status = 'not available';
        $services->save();

        return redirect('adminservice');
    }
    
    //admin Service
    public function addService()
    {
        $products = Product::all();
        return view('addservice', ['products' => $products]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        $newName = '';
        if($request->file('image'))
        {
            $extension = $request->file('image')->getClientOriginalExtension();
            $newName = $request->name.'-'.now()->timestamp.'.'.$extension;
            $request->file('image')->storeAs('coverService', $newName);
        }

        $request['cover'] = $newName;
        $services = Service::create($request->all());
        $services->products()->sync($request->products);
        return redirect('adminservice')->with('status','Data Layanan Servis Berhasil Ditambahkan');
    }

    public function edit($slug)
    {
        $services = Service::where('slug', $slug)->first();
        $products = Product::all();
        return view('editservice', ['services' => $services, 'products' => $products]);
    }

    public function update(Request $request, $slug)
    {
        $services = Service::where('slug', $slug)->first();
        $validated = $request->validate([
            'name' => 'max:255',
        ]);
        
        $newName = '';
        if($request->file('image'))
        {
            $extension = $request->file('image')->getClientOriginalExtension();
            $newName = $request->name.'-'.now()->timestamp.'.'.$extension;
            $request->file('image')->storeAs('coverService', $newName);
            $request['cover'] = $newName;
        }
        elseif ($request->has('old_image')) {
            $services->cover = $request->old_image;
        }
        
        $services = Service::where('slug', $slug)->first();
        $services->name = null;
        $services->slug = null;
        
        $services->update($request->all());

        return redirect('adminservice')->with('status','Data Layanan Servis Berhasil Diperbarui');
    }

    public function delete($slug)
    {
        $services = Service::where('slug', $slug)->first();
        return view('deleteservice', ['services' => $services]);
    }

    public function destroy($slug)
    {
        $services = Service::where('slug', $slug)->first();
        $services->delete();
        return redirect('adminservice')->with('status', 'Data Layanan Servis Berhasil Dihapus');
    }

    public function deletedShow()
    {
        $deletedServices = Service::onlyTrashed()->get();
        return view('adminservice', ['deletedServices' => $deletedServices]);
    }

    public function restore($slug)
    {
        $services = Service::withTrashed()->where('slug', $slug)->first();
        $services->restore();
        return redirect('adminservice')->with('status', 'Data Layanan Servis Berhasil Dipulihkan');
    }

    public function forceDelete($slug)
    {
        $services = Service::withTrashed()->where('slug', $slug)->first();
        return view('forcedeleteservice', ['services' => $services]);
    }

    public function forceDestroy($slug)
    {
        $services = Service::onlyTrashed()->where('slug', $slug)->first();
        $services->forcedelete();
        return redirect('adminservice')->with('status', 'Data Layanan Servis Terhapus Berhasil Dihapus Secara Permanen');
    }

    //user Service

    public function indexUser() 
    {
        $services = Service::all();
        $services = Service::paginate(8);
        return view('service', ['services' => $services]);
    }

    public function detailService($slug)
    {
        $services = Service::where('slug', $slug)->first();
        return view('detailservice', ['services' => $services]);
    }

}
