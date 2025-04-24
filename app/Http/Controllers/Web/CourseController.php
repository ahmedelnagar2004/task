<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index(Request $request)
    {
        $query = Course::query();

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $courses = $query->latest()->paginate(12);
        $departments = Department::all();

        return view('courses.index', compact('courses', 'departments'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        $departments = Department::all();
        return view('courses.create', compact('departments'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:upcoming,active,completed',
            'department_id' => 'required|exists:departments,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        Course::create($validated);

        return redirect()->route('courses.index')
            ->with('success', 'تم إضافة الدورة التدريبية بنجاح');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        $departments = Department::all();
        return view('courses.create', compact('course', 'departments'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:upcoming,active,completed',
            'department_id' => 'required|exists:departments,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($course->image_url) {
                Storage::delete(str_replace('/storage/', 'public/', $course->image_url));
            }
            
            $path = $request->file('image')->store('courses', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        $course->update($validated);

        return redirect()->route('courses.index')
            ->with('success', 'تم تحديث الدورة التدريبية بنجاح');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        // Delete course image if exists
        if ($course->image_url) {
            Storage::delete(str_replace('/storage/', 'public/', $course->image_url));
        }

        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'تم حذف الدورة التدريبية بنجاح');
    }
} 