<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Results;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboard extends Controller
{
    public function AdminDashboardPage()
    {
        $get_total_admin = Admin::count();
        $get_total_examinees = User::count();
        $get_total_results = Results::count();
        $examinees = Results::selectRaw('YEAR(created_at) as year, COUNT(DISTINCT default_id) as examinee_count')
            ->groupBy('year')
            ->orderBy('year')
            ->get();
            
        return view('admin.admin_dashboard', compact('get_total_admin', 'get_total_examinees', 'get_total_results', 'examinees'));
    }
}

