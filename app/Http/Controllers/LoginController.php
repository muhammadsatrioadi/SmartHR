<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('index');
        }else{
            return view('login');
        }
    }

    public function actionlogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::Attempt(['email'=> $request->email,'password' => $request->password])) {
            $user = Auth::user();
            session(['user' => $user]);
            return redirect()->route('index');
        }else{
            Session::flash('error', 'Email atau Password Salah');
            return redirect('/');
        }
    }

    public function actionlogout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function actionAdminUpdate(Request $request)
{
    $data = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'oldPassword' => 'required',
        'password' => 'nullable',
        'imgProfile' => 'nullable',
    ]);

    if (Hash::check($request->oldPassword, $data->password)) {
        $update = User::where('name', $data->name)->first();
        $update->name = $request->input('name');
        $update->email = $request->input('email');
        if ($request->hasFile('imgProfile')) {
            $image = $request->file('imgProfile');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img'), $imageName);
            $update->imgProfile = $imageName; 
        }else{
            $update->imgProfile = $data->imgProfile; 
        }

        
        if ($request->filled('password')) {
            $update->password = Hash::make($request->password);
        } else {
            $update->password = Hash::make($request->oldPassword);
        }

        

        $update->save();

        Auth::logout();
        return redirect('/');
    }

    return view('profile', compact('data'));
    }

}
