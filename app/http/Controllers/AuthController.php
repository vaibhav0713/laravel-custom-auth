<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(){
        return view('auth.login');
    }
    public function registration(){
        return view('auth.register');
    }
    public function customLogin(Request $request){
        // step 1: validation
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // step 2: create variable and store passing parameter
        $credentials = $request -> only('email', 'password');

        // step 3: check if auth attempt
        if(Auth::attempt($credentials)){
            return redirect()->intended('dashboard')->withSuccess('Signed In');
        }

        return redirect('/login')->withSuccess('You do not have permission');
    }
    public function customRegistration(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required?min:6'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user -> save();

        return redirect("dashboard")->withSuccess('You have signed-in');
    }
    public function dashboard(){
        if(Auth::check()){
            return view('dashboard');
        }

        return redirect('/login')->withSuccess('You are not allowed to access this page!');
    }
    public function signout(){
        Session::flush();
        Auth::logout();

        return redirect('/login')->withSuccess("You have successfully logged out!");
    }
}