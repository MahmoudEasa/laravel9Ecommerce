<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Admin;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }
    public function login(LoginRequest $request)
    {
        $remember_me = $request->has('remember_me') ? true : false;

        if(auth()->guard('admin')->attempt([
                'email' => $request->input('email'),
                'password' => $request->input('password')
        ])){
            // notify()->success('تم الدخول بنجاح');
            return redirect()->route('admin.dashboard');
        }

        // notify()->success('خطأ في البيانات برجاء المحاولة مجدداً');
        return redirect()->back()->with(['error'=> 'هناك خطأ بالبيانات']);
    }
}
