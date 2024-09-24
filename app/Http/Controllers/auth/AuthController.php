<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Str;

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

    // FORGOT PASSWORD
    public function ForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }

    public function ForgotForm($token)
    {
        return view('auth.reset_password_form', compact('token'));
    }


    public function ForgotPasswordRequest(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:admins,email']);
    
        $token = Str::random(60);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);
    
        $fullname = Admin::where('email', $request->email)->first()->fullname;
        $resetLink = route('password.reset', ['token' => $token]);
    
        Mail::to($request->email)->send(new ResetPasswordMail($fullname, $resetLink));
    
        return back()->with('status', 'Reset link sent!');
    }

    public function ResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        $passwordReset = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token)->first();

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Please request a new one']);
        }

        $admin = Admin::where('email', $request->email)->first();
        $admin->password = bcrypt($request->password);
        $admin->save();

        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('adminloginpage')->with('status', 'Password has been reset!');
    }

}    
