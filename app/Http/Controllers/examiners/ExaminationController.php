<?php

namespace App\Http\Controllers\examiners;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExaminationController extends Controller
{
    public function ExaminationPage(Request $request)
    {
        $user = Auth::user();
        $currentStep = (int) $request->input('step', 1);
        $subjects = ['math', 'english', 'science'];
        $currentSubject = $subjects[$currentStep - 1] ?? 'math';
        $questions = DB::table('questions')
            ->leftJoin('options', 'questions.id', '=', 'options.question_id')
            ->where('questions.question_subject', $currentSubject)
            ->select(
                'questions.id as question_id',
                'questions.question_text',
                'options.id as option_id',
                'options.option_text',
                'options.is_correct'
            )
            ->get()
            ->groupBy('question_id');

        $questions = $questions->map(function ($questionGroup) {
            $uniqueOptions = $questionGroup->unique('option_id');
            return $questionGroup->groupBy('question_id')->map(function ($options) use ($uniqueOptions) {
                return $uniqueOptions;
            })->first();
        });

        return view('examiners.exam_form', compact('questions', 'user', 'currentStep', 'subjects'));
    }

    public function SubmitExamination(Request $request)
    {
        $user = Auth::user();
    
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to submit the examination.');
        }
    
        $course_points = [];
        $new_points = 0;
        $answers = $request->except('_token', 'step');
        $total_questions = DB::table('questions')->count();
    
        foreach ($answers as $questionKey => $select_option_id) {
            if (preg_match('/^question_(\d+)$/', $questionKey, $matches)) {
                $question_id = (int) $matches[1];
    
                $course_ids = DB::table('question_courses')
                    ->where('question_id', $question_id)
                    ->pluck('course_id');
    
                $isOptionValid = DB::table('options')
                    ->where('id', $select_option_id)
                    ->exists();
    
                if (!$isOptionValid) {
                    continue;
                }
    
                DB::table('responses')->insert([
                    'user_id' => $user->id,
                    'question_id' => $question_id,
                    'selected_option_id' => $select_option_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
    
                $is_correct = DB::table('options')
                    ->where('id', $select_option_id)
                    ->value('is_correct');
    
                if ($is_correct == 1) {
                    $new_points += 1;
    
                    foreach ($course_ids as $course_id) {
                        if (!isset($course_points[$course_id])) {
                            $course_points[$course_id] = 0;
                        }
    
                        $course_points[$course_id] += 1;
                    }
                }
            }
        }
    
        foreach ($course_points as $course_id => $points) {
            DB::table('course_scores')->updateOrInsert(
                ['user_id' => $user->id, 'course_id' => $course_id],
                ['points' => DB::raw('IFNULL(points, 0) + ' . $points)]
            );
        }
    
        $existing_result = DB::table('results')
            ->where('default_id', $user->id)
            ->first();
        
        $existing_total_results = $existing_result ? $existing_result->total_points : 0;
    
        DB::table('results')->updateOrInsert(
            ['default_id' => $user->id],
            [
                'fullname' => $user->fullname,
                'gender' => $user->gender,
                'age' => $user->age,
                'birthday' => $user->birthday,
                'strand' => $user->strand,
                'total_points' => $existing_total_results + $new_points,
                'number_of_items' => $total_questions,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    
        $currentStep = (int) $request->input('step', 1);
        $subjects = ['math', 'english', 'science'];
        $nextStep = $currentStep + 1;
    
        if ($nextStep <= count($subjects)) {
            return redirect()->route('examiners.examination.page', ['step' => $nextStep]);
        } else {
            return redirect()->route('examiners.examination.complete')->with('success', 'Examination submitted successfully.');
        }
    }

    

    public function ExaminationCompletePage()
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
    
        return view('examiners.examination_complete', compact('courseDataWithSuggestions', 'chosenCourses', 'topCourses', 'user'));
    }
    
}
