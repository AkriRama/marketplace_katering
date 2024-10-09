<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AuthController extends Controller
{
    public function login() 
    {
        return view('login');
    }

    public function register() 
    {
        return view('register');
    }

    public function authenticating(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        //cek login valid
        if (Auth::attempt($credentials)) {
            //cek user status active
            if (Auth::user()->status != 'active'){
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                Session::flash('status', 'failed');
                Session::flash('message', 'Your account is not active yet. please contact admin!!');
                return redirect('/login');
            }

            $request->session()->regenerate();
            if(Auth::user()->role_id == 1){
                return redirect('dashboard');
            }

            if(Auth::user()->role_id == 2){
                $users = Auth::user();
                return redirect('cashier');
            }
        } else {
            Session::flash('status','failed');
            Session::flash('message', 'Login Tidak Berhasil');
            return redirect('/login');
        }

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
    public function registerProcess(Request $request)
{
    $validated = $request->validate([
        'username' => 'required|unique:users|max:255',
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'required|min:6',
        'phone' => 'required|max:255',
        'address' => 'required',
    ]);
    
    $user = User::create([
        'username' => $request->username,
        'password' => bcrypt($request->password), 
        'phone' => $request->phone,
        'address' => $request->address,
    ]);

    Session::flash('status', 'Register Success');
    Session::flash('message', 'Wait admin for approval');
    return redirect('register');
}

}
