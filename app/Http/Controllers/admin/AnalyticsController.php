<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function AnalyticsPage()
    {
        return view('admin.admin_analytics');
    }
}
