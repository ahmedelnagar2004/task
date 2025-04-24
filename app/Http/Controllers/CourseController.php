<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * عرض قائمة الدورات التدريبية
     */
    public function index()
    {
        $courses = Course::with(['lessons', 'quizzes'])->get();
        return response()->json(['data' => $courses]);
    }

    /**
     * إنشاء دورة تدريبية جديدة
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'status' => 'required|in:active,draft,archived',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $course = Course::create($request->all());
        return response()->json(['data' => $course], 201);
    }

    /**
     * عرض بيانات دورة تدريبية محددة
     */
    public function show(Course $course)
    {
        return response()->json([
            'data' => $course->load(['lessons', 'quizzes'])
        ]);
    }

    /**
     * تحديث بيانات دورة تدريبية
     */
    public function update(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'status' => 'sometimes|required|in:active,draft,archived',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $course->update($request->all());
        return response()->json(['data' => $course]);
    }

    /**
     * حذف دورة تدريبية
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return response()->json(null, 204);
    }

    /**
     * عرض الموظفين المسجلين في الدورة
     */
    public function employees(Course $course)
    {
        return response()->json([
            'data' => $course->employees()->with('employee_courses')->get()
        ]);
    }

    /**
     * تسجيل موظف في دورة تدريبية
     */
    public function enrollEmployee(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $course->employees()->attach($request->employee_id, [
            'progress' => 0,
            'completed' => false,
            'start_date' => now(),
        ]);

        return response()->json(['message' => 'تم تسجيل الموظف في الدورة بنجاح']);
    }
}
