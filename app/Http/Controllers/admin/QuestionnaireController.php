<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AvailableCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QuestionnaireController extends Controller
{
    public function QuestionnairePage()
    {
        $courses = AvailableCourse::all();
        $questions = DB::table('questions')
            ->leftJoin('options', 'questions.id', '=', 'options.question_id')
            ->leftJoin('question_courses', 'questions.id', '=', 'question_courses.question_id')
            ->leftJoin('available_courses as course', 'question_courses.course_id', '=', 'course.id')
            ->select(
                'questions.id as question_id',
                'questions.question_text',
                'questions.question_subject',
                'options.option_text',
                'options.is_correct',
                'course.course as course_name'
            )
            ->get()
            ->groupBy('question_id');

        return view('admin.admin_questionnaire', compact('courses', 'questions'));
    }

    public function AddQuestionnaire(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string|max:255',
            'question_subject' => 'required|string',
            'option_text' => 'required|array|min:2|max:4',
            'option_text.*' => 'required|string|max:255',
            'is_correct' => 'required|integer|between:0,3',
            'course_id' => 'required|array|min:1',
            'course_id.*' => 'required|integer|exists:available_courses,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $question_id = DB::table('questions')->insertGetId([
                'question_text' => $request->input('question_text'),
                'question_subject' => $request->input('question_subject'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $option_texts = $request->input('option_text');
            $is_correct = $request->input('is_correct');

            foreach ($option_texts as $index => $option_text) {
                DB::table('options')->insert([
                    'question_id' => $question_id,
                    'option_text' => $option_text,
                    'is_correct' => ($index == $is_correct) ? true : false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $course_ids = $request->input('course_id');

            foreach ($course_ids as $course_id) {
                DB::table('question_courses')->insert([
                    'question_id' => $question_id,
                    'course_id' => $course_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Question added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add question')->withInput();
        }
    }

    public function EditQuestionnaire($id)
    {
        $courses = AvailableCourse::all();
        $question = DB::table('questions')
            ->leftJoin('options', 'questions.id', '=', 'options.question_id')
            ->leftJoin('question_courses', 'questions.id', '=', 'question_courses.question_id')
            ->leftJoin('available_courses as course', 'question_courses.course_id', '=', 'course.id')
            ->select(
                'questions.id as question_id',
                'questions.question_text',
                'questions.question_subject',
                'options.option_text',
                'options.is_correct',
                'course.course as course_name'
            )
            ->where('questions.id', $id)
            ->get();

        if ($question->isEmpty()) {
            return redirect()->route('admin.questionnaire')->with('error', 'Question not found.');
        }

        $questionData = $question->first();
        $choices = $question->pluck('option_text')->unique()->values()->toArray();
        $correct_answer_index = $question->where('is_correct', true)->keys()->first() ?? '';
        $related_courses = $question->pluck('course_name')->unique()->toArray();
        $related_course_ids = DB::table('question_courses')
            ->where('question_id', $id)
            ->pluck('course_id')
            ->toArray();

        return view('admin.admin_edit_questionnaire', compact('courses', 'questionData', 'choices', 'correct_answer_index', 'related_course_ids'));
    }

    public function UpdateQuestionnaire(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string|max:255',
            'question_subject' => 'required|string',
            'option_text' => 'required|array|min:2|max:4',
            'option_text.*' => 'required|string|max:255',
            'is_correct' => 'required|integer|between:0,3',
            'course_id' => 'required|array|min:1',
            'course_id.*' => 'required|integer|exists:available_courses,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            DB::table('questions')->where('id', $id)->update([
                'question_text' => $request->input('question_text'),
                'question_subject' => $request->input('question_subject'),
                'updated_at' => now(),
            ]);

            DB::table('options')->where('question_id', $id)->delete();
            $option_texts = $request->input('option_text');
            $is_correct = $request->input('is_correct');

            foreach ($option_texts as $index => $option_text) {
                DB::table('options')->insert([
                    'question_id' => $id,
                    'option_text' => $option_text,
                    'is_correct' => ($index == $is_correct),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('question_courses')->where('question_id', $id)->delete();
            $course_ids = $request->input('course_id');

            foreach ($course_ids as $course_id) {
                DB::table('question_courses')->insert([
                    'question_id' => $id,
                    'course_id' => $course_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            DB::commit();

            return redirect()->route('admin.questionnaire')->with('success', 'Question updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update question')->withInput();
        }
    }

    public function DeleteQuestionnaire($id)
    {
        DB::beginTransaction();
        try {
            DB::table('options')->where('question_id', $id)->delete();
            DB::table('question_courses')->where('question_id', $id)->delete();
            DB::table('questions')->where('id', $id)->delete();
            DB::commit();

            return redirect()->route('admin.questionnaire')->with('success', 'Question deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.questionnaire')->with('error', 'Failed to delete question');
        }
    }
}
