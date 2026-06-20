<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index() {
        $courses = Course::with('teacher')->latest()->paginate(20);
        return view('admin.courses.index', compact('courses'));
    }

    public function create() {
        return view('admin.courses.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'type' => 'required|string',
            'level' => 'required|string',
            'calligraphy_style' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'nullable|integer',
            'max_students' => 'nullable|integer',
            'status' => 'required|string',
        ]);

        $validated['teacher_id'] = auth()->id();
        $validated['slug'] = Str::slug($request->title_ar . '-' . time());

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        Course::create($validated);
        return redirect()->route('admin.courses.index')->with('success', 'تم إنشاء الدورة.');
    }

    public function edit(Course $course) {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course) {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|string',
        ]);

        $course->update($validated);
        return redirect()->route('admin.courses.index')->with('success', 'تم تحديث الدورة.');
    }

    public function destroy(Course $course) {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'تم حذف الدورة.');
    }
}
