<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * عرض قائمة الأسئلة لاختبار معين
     */
    public function index(Course $course, Quiz $quiz)
    {
        if ($quiz->course_id !== $course->id) {
            return response()->json(['error' => 'الاختبار غير موجود في هذه الدورة'], 404);
        }

        $questions = $quiz->questions()->with('options')->get();
        return response()->json(['data' => $questions]);
    }

    /**
     * إضافة سؤال جديد
     */
    public function store(Request $request, Course $course, Quiz $quiz)
    {
        if ($quiz->course_id !== $course->id) {
            return response()->json(['error' => 'الاختبار غير موجود في هذه الدورة'], 404);
        }

        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,essay',
            'points' => 'required|integer|min:1',
            'options' => 'required_if:question_type,multiple_choice,true_false|array',
            'options.*.option_text' => 'required_with:options|string',
            'options.*.is_correct' => 'required_with:options|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $question = $quiz->questions()->create($request->except('options'));

        if ($request->has('options')) {
            $question->options()->createMany($request->options);
        }

        return response()->json(['data' => $question->load('options')], 201);
    }

    /**
     * عرض بيانات سؤال محدد
     */
    public function show(Course $course, Quiz $quiz, Question $question)
    {
        if ($quiz->course_id !== $course->id || $question->quiz_id !== $quiz->id) {
            return response()->json(['error' => 'السؤال غير موجود في هذا الاختبار'], 404);
        }

        return response()->json(['data' => $question->load('options')]);
    }

    /**
     * تحديث بيانات سؤال
     */
    public function update(Request $request, Course $course, Quiz $quiz, Question $question)
    {
        if ($quiz->course_id !== $course->id || $question->quiz_id !== $quiz->id) {
            return response()->json(['error' => 'السؤال غير موجود في هذا الاختبار'], 404);
        }

        $validator = Validator::make($request->all(), [
            'question_text' => 'sometimes|required|string',
            'question_type' => 'sometimes|required|in:multiple_choice,true_false,essay',
            'points' => 'sometimes|required|integer|min:1',
            'options' => 'required_if:question_type,multiple_choice,true_false|array',
            'options.*.id' => 'sometimes|exists:question_options,id',
            'options.*.option_text' => 'required_with:options|string',
            'options.*.is_correct' => 'required_with:options|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $question->update($request->except('options'));

        if ($request->has('options')) {
            // حذف الخيارات القديمة
            $question->options()->delete();
            // إضافة الخيارات الجديدة
            $question->options()->createMany($request->options);
        }

        return response()->json(['data' => $question->load('options')]);
    }

    /**
     * حذف سؤال
     */
    public function destroy(Course $course, Quiz $quiz, Question $question)
    {
        if ($quiz->course_id !== $course->id || $question->quiz_id !== $quiz->id) {
            return response()->json(['error' => 'السؤال غير موجود في هذا الاختبار'], 404);
        }

        $question->delete();
        return response()->json(null, 204);
    }
}
