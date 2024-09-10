<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboard extends Controller
{
    public function AdminDashboardPage()
    {
        $get_total_admin = Admin::count();
        $get_total_examinees = User::count();
        return view ('admin.admin_dashboard', compact('get_total_admin', 'get_total_examinees'));
    }
}
