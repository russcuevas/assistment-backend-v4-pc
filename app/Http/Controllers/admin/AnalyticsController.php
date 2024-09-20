<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AvailableCourse;
use Illuminate\Http\Request;
use App\Models\Results;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function AnalyticsPage()
    {
        $number_of_examiners = User::count();
        $number_of_top_notchers = Results::count();
        $number_of_course = AvailableCourse::count();
        $total_examiners = $this->getTotalExaminers();
        $genderCounts = $this->getGenderCounts();
        $topNotchers = $this->getTopNotchers();

        return view('admin.admin_analytics', compact('number_of_examiners', 'number_of_top_notchers', 'number_of_course', 'total_examiners', 'genderCounts', 'topNotchers'));
    }

    private function getTotalExaminers()
    {
        return DB::table('users')
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
    }

    private function getGenderCounts()
    {
        $genderCounts = Results::selectRaw('gender, COUNT(*) as count')
            ->groupBy('gender')
            ->pluck('count', 'gender')
            ->toArray();

        return array_merge([
            'Male' => 0,
            'Female' => 0
        ], $genderCounts);
    }

    private function getTopNotchers()
    {
        return Results::orderBy('total_points', 'desc')
            ->limit(10)
            ->get(['fullname', 'default_id', 'total_points', 'number_of_items']);
    }

    public function GetAvailableCourses()
    {
        $availableCourses = DB::table('available_courses')
            ->select('course', DB::raw('COUNT(*) as count'))
            ->groupBy('course')
            ->pluck('count', 'course')
            ->toArray();

        return response()->json(['availableCourses' => $availableCourses]);
    }

    public function getTopNotchersByYear($year)
    {
        $topNotchers = Results::whereYear('created_at', $year)
            ->orderBy('total_points', 'desc')
            ->limit(10)
            ->get(['default_id', 'fullname', 'total_points', 'number_of_items']);

        return response()->json(['topNotchers' => $topNotchers]);
    }

}
