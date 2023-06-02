<?php

namespace App\Http\Controllers;

use App\Logic\UserLogic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected UserLogic $userLogic)
    {
    }

    public function showLogin()
    {
        return view("login");
    }

    public function showRegister()
    {
        return view("register");
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        $rememberMe = $request->has("remember_me");
        $isValid = Auth::attempt($credentials, $rememberMe);


        if(!$isValid){
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors("Wrong email or password, please try again");
        }

        $isActive = Auth::user()->is_active;

        if (!$isActive) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors("Account is inactive");
        }

        return redirect()->route('home');
    }

    public function doRegister(Request $request)
    {
        $request->validate([
            "name" => "bail|required|string|max:35",
            "email" => "bail|required|unique:users,email|max:35",
            "password" => "bail|required|string|confirmed"
        ]);

        $this->userLogic->insert(
            $request->input('name'),
            $request->input('email'),
            $request->input('password'),
        );

        return redirect()->route("login")
            ->with("notif", ["Succsess\nRegistration success, please login using your account!"]);
    }
}
