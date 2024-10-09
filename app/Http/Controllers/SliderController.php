<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
        $deletedslider = Slider::onlyTrashed()->get();
        return view('adminslider', ['sliders' => $sliders, 'deletedSliders' => $deletedslider]);
    }

    public function add()
    {
        $sliders = Slider::all();
        return view('addslider', ['sliders' => $sliders]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
        ]);

        $newName = '';
        if($request->file('image'))
        {
            $extension = $request->file('image')->getClientOriginalExtension();
            $newName = $request->name.'-'.now()->timestamp.'.'.$extension;
            $request->file('image')->storeAs('slider', $newName);
        }

        $request['cover'] = $newName;
        $sliders = Slider::create($request->all());
        return redirect('adminslider');
    }

    public function edit($slug)
    {
        $sliders = Slider::where('slug', $slug)->first();
        return view('editslider', ['sliders' => $sliders]);
    }

    public function update(Request $request, $slug)
    {
        $sliders = Slider::where('slug', $slug)->first();
        $validated = $request->validate([
            'name' => 'max:255',
            'description' => 'max:255',
        ]);

        $newName = '';
        if($request->file('image')){
            $extension = $request->file('image')->getClientOriginalExtension();
            $newName = $request->name.'-'.now()->timestamp.'.'.$extension;
            $request->file('image')->storeAs('slider', $newName);
            
            $sliders = $request['cover'] = $newName;
        }
        elseif ($request->has('old_image')) {
            $sliders->cover = $request->old_image;
        }

        $sliders = Slider::where('slug', $slug)->first();
        $slug = null;
        $sliders->update($request->all());

        return redirect('adminslider');
    }

    public function delete($slug)
    {
        $sliders = Slider::where('slug', $slug)->first();
        return view('deleteslider', ['sliders' => $sliders]);
    }
    
    public function destroy($slug)
    {
        $sliders = Slider::where('slug', $slug)->first();
        $sliders->delete();
        return redirect('adminslider')->with('status', 'Data Slider Berhasil Dihapus');
    }

    public function deletedShow()
    {
        $deletedslider = Slider::onlyTrashed()->get();
        return view('adminslider', ['deletedSliders' => $deletedslider]);
    }

    public function restore($slug)
    {
        $sliders = Slider::withTrashed()->where('slug', $slug)->first();
        $sliders->restore();
        return redirect('adminslider')->with('status', 'Data Slider Berhasil Dipulihkan');
    }

    public function forceDelete($slug)
    {
        $sliders = Slider::withTrashed()->where('slug', $slug)->first();
        return view('forcedeleteslider', ['sliders' => $sliders]);
    }

    public function forceDestroy($slug)
    {
        $sliders = Slider::onlyTrashed()->where('slug', $slug)->first();
        $sliders->forcedelete();
        return redirect('adminslider')->with('status', 'Data Slider Terhapus Berhasil Dihapus Secara Permanen');
    }
}
