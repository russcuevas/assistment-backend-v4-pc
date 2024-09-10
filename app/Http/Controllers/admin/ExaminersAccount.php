<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ExaminersAccount extends Controller
{
    public function ExaminersAccountPage()
    {
        // Displaying available default_id in the page
        $available_default_id = User::all();

        // Displaying the next default_id in the page
        $highest_id = User::max('default_id');
        $next_id = $highest_id ? $highest_id + 1 : 1;

        // Displaying users related to selected course
        $examiners = DB::table('users')
            ->leftJoin('chosen_courses', 'users.id', '=', 'chosen_courses.user_id')
            ->leftJoin('available_courses as course_1', 'chosen_courses.course_1', '=', 'course_1.id')
            ->leftJoin('available_courses as course_2', 'chosen_courses.course_2', '=', 'course_2.id')
            ->leftJoin('available_courses as course_3', 'chosen_courses.course_3', '=', 'course_3.id')
            ->select(
                'users.id',
                'users.default_id',
                'users.fullname',
                'users.gender',
                'users.age',
                'users.birthday',
                'users.strand',
                'course_1.course as course_1_name',
                'course_2.course as course_2_name',
                'course_3.course as course_3_name'
            )
            ->get();


        return view('admin.admin_examiners_account', compact('available_default_id', 'next_id', 'examiners'));
    }

    public function ExaminersAccountAdd(Request $request)
    {
        $new_id = $request->input('default_id');

        User::create([
            'default_id' => $new_id,
            'password' => Hash::make('ub1234')
        ]);

        return redirect()->route('admin.examiners.account')->with('success', 'Default ID added successfully');
    }
}
