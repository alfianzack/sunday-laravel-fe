<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function showLogin()
    {
        if (Session::has('api_token')) {
            $user = Session::get('user');
            if ($user && $user['role'] === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $result = $this->apiService->login($request->email, $request->password);
            $user = Session::get('user');
            
            if ($user && $user['role'] === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->route('home');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function showRegister()
    {
        if (Session::has('api_token')) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        try {
            $this->apiService->register($request->email, $request->password, $request->name);
            return redirect()->route('home');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function logout()
    {
        $this->apiService->logout();
        return redirect()->route('home');
    }
}

