<?php

namespace App\Http\Controllers\examiners;

use App\Http\Controllers\Controller;
use App\Models\AvailableCourse;
use App\Models\ChosenCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ExaminersInformation extends Controller
{
    public function ExaminersInformationPage()
    {
        // FETCHING EXAMINERS DEFAULT ID
        $examiners = Auth::guard('users')->user();

        // FETCHING COURSE DISPLAY TO SELECT OPTION
        $courses = AvailableCourse::all();

        // CHOSEN COURSE
        // $chosen_course = ChosenCourse::where('user_id', $examiners->id)->first();
        return view('examiners.exam_landing', compact('examiners', 'courses'));
    }

    public function AddInformation(Request $request)
    {
        $request->validate([
            'fullname' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:10',
            'age' => 'nullable|string|max:3',
            'birthday' => 'nullable|date',
            'strand' => 'nullable|string|max:255',
            'course_1' => 'nullable|exists:available_courses,id',
            'course_2' => 'nullable|exists:available_courses,id',
            'course_3' => 'nullable|exists:available_courses,id',
        ]);
    
        // Getting the auth user
        $user = Auth::guard('users')->user();
    
        $birthday = $request->input('birthday');
        if ($birthday) {
            $formatted_password = date('Ymd', strtotime($birthday));
        } else {
            $formatted_password = $user->password;
        }
    
        // Update the user details
        $user->update([
            'fullname' => $request->input('fullname'),
            'gender' => $request->input('gender'),
            'age' => $request->input('age'),
            'birthday' => $request->input('birthday'),
            'strand' => $request->input('strand'),
            'password' => Hash::make($formatted_password),
        ]);
    
        // Insert or update in chosen_course table
        ChosenCourse::updateOrCreate(
            ['user_id' => $user->id],
            [
                'course_1' => $request->input('course_1'),
                'course_2' => $request->input('course_2'),
                'course_3' => $request->input('course_3'),
            ]
        );
    
        return redirect()->route('examiners.examination.page')->with('success');
    }
    
}
