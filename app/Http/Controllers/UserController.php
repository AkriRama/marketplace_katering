<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function user(Request $request)
    {
        $users = User::where('role_id',2)->get();
        $deletedusers = User::onlyTrashed()->get();
        return view('adminuser', ['users' => $users, 'deletedusers' => $deletedusers]);
    }

    public function index()
    {
        $users = User::where('role_id',2)->get();
        $users= Auth::user();
        return view('profile', ['users' => $users]);
    }

    public function changeActive($slug)
    {
        $users = User::where('slug', $slug)->first();

        $users->status = "active";
        $users->update();
        return back();
    }
    
    public function changeInActive($slug)
    {
        $users = User::where('slug', $slug)->first();

        $users->status = "inactive";
        $users->update();
        return back();
    }

    public function detailUser($slug)
    {
        $users = User::where('slug', $slug)->first();
        return view('detailuser', ['users' => $users]);
    }

    public function add()
    {
        return view('adduser');
    }
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'username' => 'required|max:255',
            'password' => 'min:6|required',
            'password_confirmation'=> 'min:6',
            'phone' => 'required|max:255',
            'address' => 'required',
        ]);
        
        $users = User::all();
        foreach($users as $item)
        {
            if($item->username == $request->username)
            {
                return redirect('users')->with('message','Nama Pengguna sudah terpakai, silahkan ganti!');
            }
            
        }
        $newName = '';
        if($request->file('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $newName = $request->username.'-'.now()->timestamp.'.'.$extension;
            $request->file('image')->storeAs('photoprofile', $newName);
        }
        $user= $request['photo_profile'] = $newName;
        
        $user = User::create($request->all());
        return redirect('users');
    }

    public function edit($slug)
    {
        $user = User::where('slug', $slug)->first();
        return view('adminuser', ['user' => $user]);
    }

    public function update(Request $request, $slug)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users|max:255',
        ]);

        
        $newName = '';
        if($request->file('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $newName = $request->username.'-'.now()->timestamp.'.'.$extension;
            $request->file('image')->storeAs('photoprofile', $newName);
        }

        $users= $request['photo_profile'] = $newName;

        $users = User::where('slug', $slug)->first();
        $users->username = null;
        $users->slug = null;
        $users->update($request->all());
        return redirect('users')->with('status','User Berhasil Diperbarui');
    }
    
    public function destroy($slug)
    {
        $users = User::where('slug', $slug)->first();
        $users->delete();
        return redirect('users')->with('status', 'User Berhasil Dihapus');
    }

    public function deletedShow()
    {
        $deletedusers = User::onlyTrashed()->get();
        return view('users', ['deletedusers' => $deletedusers]);
    }

    public function restore($slug)
    {
        $users = User::withTrashed()->where('slug', $slug)->first();
        $users->restore();
        return redirect('users')->with('status', 'User Berhasil Dipulihkan');
    }

    public function forceDelete($slug)
    {
        $users = User::withTrashed()->where('slug', $slug)->first();
        return back();
    }

    public function forceDestroy($slug)
    {
        $users = User::onlyTrashed()->where('slug', $slug)->first();
        $users->forcedelete();
        return redirect('users')->with('status', 'Data User Terhapus Berhasil Dihapus Secara Permanen');
    }

    public function modal()
    {
        return view('modaldeleteduser');
    }
}
