<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    /**
     * عرض قائمة الاختبارات لدورة معينة
     */
    public function index(Course $course)
    {
        $quizzes = $course->quizzes()->with('questions.options')->get();
        return response()->json(['data' => $quizzes]);
    }

    /**
     * إنشاء اختبار جديد
     */
    public function store(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $quiz = $course->quizzes()->create($request->all());
        return response()->json(['data' => $quiz], 201);
    }

    /**
     * عرض بيانات اختبار محدد
     */
    public function show(Course $course, Quiz $quiz)
    {
        if ($quiz->course_id !== $course->id) {
            return response()->json(['error' => 'الاختبار غير موجود في هذه الدورة'], 404);
        }
        return response()->json(['data' => $quiz->load('questions.options')]);
    }

    /**
     * تحديث بيانات اختبار
     */
    public function update(Request $request, Course $course, Quiz $quiz)
    {
        if ($quiz->course_id !== $course->id) {
            return response()->json(['error' => 'الاختبار غير موجود في هذه الدورة'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'sometimes|required|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $quiz->update($request->all());
        return response()->json(['data' => $quiz]);
    }

    /**
     * حذف اختبار
     */
    public function destroy(Course $course, Quiz $quiz)
    {
        if ($quiz->course_id !== $course->id) {
            return response()->json(['error' => 'الاختبار غير موجود في هذه الدورة'], 404);
        }

        $quiz->delete();
        return response()->json(null, 204);
    }

    /**
     * تقديم إجابات الاختبار
     */
    public function submit(Request $request, Course $course, Quiz $quiz)
    {
        if ($quiz->course_id !== $course->id) {
            return response()->json(['error' => 'الاختبار غير موجود في هذه الدورة'], 404);
        }

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.selected_options' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // حساب النتيجة
        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($request->answers as $answer) {
            $question = $quiz->questions()->find($answer['question_id']);
            if (!$question) continue;

            $totalPoints += $question->points;
            
            if ($question->question_type === 'multiple_choice') {
                $correctOptions = $question->options()->where('is_correct', true)->pluck('id')->toArray();
                $selectedOptions = $answer['selected_options'];
                
                if (count(array_diff($correctOptions, $selectedOptions)) === 0 && 
                    count(array_diff($selectedOptions, $correctOptions)) === 0) {
                    $earnedPoints += $question->points;
                }
            }
        }

        $score = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;

        // تحديث تقدم الموظف في الدورة
        $employeeCourse = $course->employees()->where('employee_id', $request->employee_id)->first()->pivot;
        $employeeCourse->score = $score;
        if ($score >= $quiz->passing_score) {
            $employeeCourse->completed = true;
            $employeeCourse->completion_date = now();
        }
        $employeeCourse->save();

        return response()->json([
            'score' => $score,
            'passing_score' => $quiz->passing_score,
            'passed' => $score >= $quiz->passing_score,
        ]);
    }
}
