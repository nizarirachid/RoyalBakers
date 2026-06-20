<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::where('status', 'active')
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->level, fn($q) => $q->where('level', $request->level))
            ->when($request->style, fn($q) => $q->where('calligraphy_style', $request->style))
            ->latest()
            ->paginate(9);

        return view('frontend.courses.index', compact('courses'));
    }

    public function show(string $slug)
    {
        $course = Course::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $lessons = $course->lessons;
        return view('frontend.courses.show', compact('course', 'lessons'));
    }

    public function enroll(Request $request, Course $course)
    {
        $this->middleware('auth');

        if (!$course->hasAvailableSlots()) {
            return back()->with('error', __('messages.course_full'));
        }

        $existing = $course->enrollments()->where('user_id', auth()->id())->exists();
        if ($existing) {
            return back()->with('info', __('messages.already_enrolled'));
        }

        $course->enrollments()->create([
            'user_id' => auth()->id(),
            'price_paid' => $course->price,
            'payment_status' => 'pending',
        ]);

        $course->increment('enrolled_count');

        return back()->with('success', __('messages.enrolled_success'));
    }
}
