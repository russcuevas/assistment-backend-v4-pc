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

    public function EditCourse($id)
    {
        $course = AvailableCourse::findOrFail($id);
        return view('admin.admin_edit_course', compact('course'));
    }
    

    public function UpdateCourse(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'course' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('admin.course')
                             ->withErrors($validator)
                             ->withInput();
        }
    
        $course = AvailableCourse::findOrFail($id);
        $course->course = $request->input('course');
        $course->save();
    
        return redirect()->route('admin.course')->with('success', 'Course updated successfully!');
    } 
}
