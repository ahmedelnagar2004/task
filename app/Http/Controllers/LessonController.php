<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    /**
     * عرض قائمة الدروس لدورة معينة
     */
    public function index(Course $course)
    {
        $lessons = $course->lessons()->orderBy('order')->get();
        return response()->json(['data' => $lessons]);
    }

    /**
     * إضافة درس جديد
     */
    public function store(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'order' => 'nullable|integer|min:1',
            'duration' => 'nullable|integer|min:1',
            'video_url' => 'nullable|url',
            'resource_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lesson = $course->lessons()->create($request->all());
        return response()->json(['data' => $lesson], 201);
    }

    /**
     * عرض بيانات درس محدد
     */
    public function show(Course $course, Lesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            return response()->json(['error' => 'الدرس غير موجود في هذه الدورة'], 404);
        }
        return response()->json(['data' => $lesson]);
    }

    /**
     * تحديث بيانات درس
     */
    public function update(Request $request, Course $course, Lesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            return response()->json(['error' => 'الدرس غير موجود في هذه الدورة'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'nullable|string',
            'order' => 'nullable|integer|min:1',
            'duration' => 'nullable|integer|min:1',
            'video_url' => 'nullable|url',
            'resource_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lesson->update($request->all());
        return response()->json(['data' => $lesson]);
    }

    /**
     * حذف درس
     */
    public function destroy(Course $course, Lesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            return response()->json(['error' => 'الدرس غير موجود في هذه الدورة'], 404);
        }

        $lesson->delete();
        return response()->json(null, 204);
    }

    /**
     * إعادة ترتيب الدروس
     */
    public function reorder(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), [
            'lessons' => 'required|array',
            'lessons.*.id' => 'required|exists:lessons,id',
            'lessons.*.order' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        foreach ($request->lessons as $lessonData) {
            $lesson = Lesson::find($lessonData['id']);
            if ($lesson->course_id === $course->id) {
                $lesson->update(['order' => $lessonData['order']]);
            }
        }

        return response()->json(['message' => 'تم إعادة ترتيب الدروس بنجاح']);
    }
}
