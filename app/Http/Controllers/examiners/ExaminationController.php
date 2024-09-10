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
        $answers = $request->except('_token', 'step');
    
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
    
                $isCorrect = DB::table('options')
                    ->where('id', $select_option_id)
                    ->value('is_correct');
    
                if ($isCorrect == 1) {
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
                ['points' => DB::raw('points + ' . $points)]
            );
        }
    
        $currentStep = (int) $request->input('step', 1);
        $subjects = ['math', 'english', 'science'];
        $nextStep = $currentStep + 1;

        if ($nextStep <= count($subjects)) {
            return redirect()->route('examiners.examination.page', ['step' => $nextStep]);
        } else {
            return redirect()->route('examiners.examination.complete')->with('success', 'Examination submitted successfully.');
        }
    }

    public function ExaminationCompletePage(){
        return view('examiners.examination_complete');
    }
}
