<?php

namespace App\Http\Controllers\examiners;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExaminersDashboardController extends Controller
{
    public function ExaminersDashboardPage()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to view the examination results.');
        }

        $availableCourses = DB::table('available_courses')->get();
    
        $chosenCourses = DB::table('chosen_courses')
            ->where('user_id', $user->id)
            ->first();
        
        if (!$chosenCourses) {
            return redirect()->route('home')->with('error', 'No chosen courses found.');
        }
    
        $courseScores = DB::table('course_scores')
            ->where('user_id', $user->id)
            ->select('course_id', 'points')
            ->get()
            ->keyBy('course_id');
    
        $courseTotalPoints = DB::table('question_courses')
            ->join('questions', 'question_courses.question_id', '=', 'questions.id')
            ->join('options', 'questions.id', '=', 'options.question_id')
            ->where('options.is_correct', 1)
            ->select('question_courses.course_id', DB::raw('count(options.id) as total_points'))
            ->groupBy('question_courses.course_id')
            ->pluck('total_points', 'course_id');
    
            $courseData = $availableCourses->map(function ($course) use ($courseScores, $courseTotalPoints) {
                $courseId = $course->id;
                $points = $courseScores->get($courseId)->points ?? 0;
                $over_points = $courseTotalPoints->get($courseId, 0);
                $percentage = $over_points ? ($points / $over_points) * 100 : 0;
            
                return [
                    'course_name' => $course->course,
                    'course_id' => $courseId,
                    'points' => $points,
                    'percentage' => round($percentage, 2),
                    'over_points' => $over_points
                ];
            })->sortByDesc('percentage');
            
    
        $chosenCourseIds = [
            $chosenCourses->course_1,
            $chosenCourses->course_2,
            $chosenCourses->course_3,
        ];
    
        $courseDataWithSuggestions = $courseData->map(function ($course) use ($chosenCourseIds) {
            return [
                'course_name' => $course['course_name'],
                'points' => $course['points'],
                'percentage' => $course['percentage'],
                'over_points' => $course['over_points'],
                'is_chosen' => in_array($course['course_id'], $chosenCourseIds),
            ];
        });
        
    
        $topCourses = $courseData->filter(function ($course) use ($chosenCourseIds) {
            return in_array($course['course_id'], $chosenCourseIds);
        });

        return view('examiners.examiners_dashboard', compact('courseDataWithSuggestions', 'chosenCourses', 'topCourses', 'user'));
    }
}
