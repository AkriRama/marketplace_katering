<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(){
        $user = User::all();
        return view('profile', ['user' => $user]);
    }

    public function edit($slug)
    {       
        $users = User::where('slug', $slug)->first();
        return view('editprofile', ['users' => $users]);
    }

    public function update(Request $request, $slug)
    {
        $validate = $request->validate([
            'username' => 'required|max:255',
        ]);

        $newName = '';
        if($request->file('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $newName = $request->username.'-'.now()->timestamp.'.'.$extension;
            $request->file('image')->storeAs('photoprofile', $newName);
        }

        $users= $request['photo_profile'] = $newName;

        $users = User::where('slug', $slug)->first();
        $users = Auth::user();
        $users->update($request->all());

        // $user->username = $request->input('username');
        // $user->phone = $request->input('phone');
        // $user->address = $request->input('address');
        // $user->save();



        return redirect('profile')->with('status','Profile Updated Successfully');
    }
}
