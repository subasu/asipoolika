<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getSignUpPage()
    {
        return view('auth.signup');
    }

    public function getSignInPage()
    {
        return view('auth.signin');
    }

    public function postSignUp(Request $request)
    {
        $user = new User();
        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->email = $request['email'];
        $user->password = $request['password'];
        $user->save();
        $user->roles()->attach(Role::where('name', 'User')->first());
        Auth::login($user);
        return redirect()->route('main');
    }

    public function postSignIn(Request $request)
    {
        if (Auth::attempt(['username' => $request['username'], 'password' => $request['password'],'active' =>1])) {
            return redirect()->route('main');
        }
        return redirect()->back();
    }

    public function getLogout()
    {
        Auth::logout();
        return redirect()->route('main');
    }
}
