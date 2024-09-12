<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // ADMIN AUTH
    public function AdminLoginPage()
    {
        if (Auth::guard('admin')->check()){
            return redirect()->route('admin.dashboard');
        }

        return view('auth.admin_login');
    }

    public function AdminLoginRequest(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->intended('/admin/admin_dashboard');
        }

        return redirect()->route('adminloginpage')->withErrors([
            'error' => 'Invalid credentials'
        ]);
    }

    public function AdminLogout(){
        Auth::guard('admin')->logout();
        return redirect()->route('adminloginpage');
    }
    

    // EXAMINERS AUTH
    public function LoginPage()
    {
        if (Auth::guard('users')->check()){
            return redirect()->route('examiners.landing.page');
        }
        return view ('auth.login');
    }

    public function ExaminersLoginRequest(Request $request)
    {
        $credentials = $request->only('default_id', 'password');
        if (Auth::guard('users')->attempt($credentials)) {
            $user_id = Auth::guard('users')->id();
            $has_results = DB::table('course_scores')->where('user_id', $user_id)->exists();
            
            if ($has_results) {
                return redirect('examiners/dashboard');
            } else {
                return redirect()->intended('/examiners_landing');
            }
        }

        return redirect()->route('loginpage')->withErrors([
            'error' => 'Invalid credentials'
        ]);
    }
    
    public function ExaminersLogout(){
        Auth::guard('users')->logout();
        return redirect()->route('loginpage');
    }
}
