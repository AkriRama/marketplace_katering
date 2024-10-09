<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expenses;

class ExpensesController extends Controller
{

    public function index ()
    {
        $expenses = Expenses::all();
        $deletedExpenses = Expenses::onlyTrashed()->get();
        return view('adminexpenses', ['expenses' => $expenses, 'deletedExpenses' => $deletedExpenses]);
    }

    public function add()
    {
        return view('addexpenses');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:expenses|max:255',
            'description' => 'required|max:255',
            'expense' => 'required|max:255',
        ]);

        $expenses = Expenses::create($request->all());
        return redirect('adminexpenses')->with('status','Data Pengeluaran Berhasil Ditambahkan');
    }

    public function edit($slug)
    {
        $expenses = Expenses::where('slug', $slug)->first();
        return view('editexpenses', ['expenses' => $expenses]);
    }

    public function update(Request $request, $slug)
    {
        // $validated = $request->validate([
        //     'name' => 'required|unique:expenses|max:255',
        // ]);

        if($request->input('from_modal')) {
            session(['from_modal' => true]);
        }

        $expenses = Expenses::where('slug', $slug)->first();
        $expenses->slug=null;
        $expenses->update($request->all());
        return redirect('adminexpenses')->with('status','Data Pengeluaran Berhasil Ditambahkan');
    }

    public function delete($slug)
    {
        $expenses = Expenses::where('slug', $slug)->first();
        return view('deleteexpenses', ['expenses' => $expenses]);
    }

    public function destroy($slug)
    {
        $expenses = Expenses::where('slug', $slug)->first();
        $expenses->delete();
        return redirect('adminexpenses')->with('status','Data Pengeluaran Berhasil Dihapus');
    }

    public function deletedExpenses()
    {
        $deletedExpenses = Expenses::onlyTrashed()->get();
        return view('adminexpenses', ['deletedExpenses' => $deletedExpenses]);
    }

    public function restore($slug)
    {
        $expenses = Expenses::withTrashed()->where('slug', $slug)->first();
        $expenses->restore();
        return redirect('adminexpenses')->with('status','Data Pengeluaran Berhasil Dipulihkan');
    }

    public function forcedelete($slug)
    {
        $expenses = Expenses::withTrashed()->where('slug', $slug)->first();
        return view('forcedeleteexpenses', ['expenses' => $expenses]);
    }

    public function forcedestroy($slug)
    {
        $expenses = Expenses::onlyTrashed()->where('slug', $slug)->first();
        $expenses->forcedelete();
        return redirect('adminexpenses')->with('status','Data Pengeluaran Terhapus Berhasil Dihapus Secara Permanen');

    }

}
