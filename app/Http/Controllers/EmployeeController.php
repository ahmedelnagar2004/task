<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * عرض قائمة الموظفين
     */
    public function index()
    {
        $employees = Employee::all();
        return response()->json(['data' => $employees]);
    }

    /**
     * إنشاء موظف جديد
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $employee = Employee::create($request->all());
        return response()->json(['data' => $employee], 201);
    }

    /**
     * عرض بيانات موظف محدد
     */
    public function show(Employee $employee)
    {
        return response()->json([
            'data' => $employee,
            'courses' => $employee->courses
        ]);
    }

    /**
     * تحديث بيانات موظف
     */
    public function update(Request $request, Employee $employee)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:employees,email,' . $employee->id,
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $employee->update($request->all());
        return response()->json(['data' => $employee]);
    }

    /**
     * حذف موظف
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json(null, 204);
    }

    /**
     * عرض الدورات التدريبية للموظف
     */
    public function courses(Employee $employee)
    {
        return response()->json([
            'data' => $employee->courses()->with('lessons', 'quizzes')->get()
        ]);
    }
}
