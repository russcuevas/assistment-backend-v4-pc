<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AvailableCourse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class Course extends Controller
{
    public function CoursePage()
    {
        $available_courses = AvailableCourse::all();
        return view('admin.admin_course', compact('available_courses'));
    }

    public function AddCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.course')
                             ->withErrors($validator)
                             ->withInput();
        }

        AvailableCourse::create([
            'course' => $request->input('course'),
        ]);

        return redirect()->route('admin.course')->with('success', 'Course added successfully!');
    }
}
