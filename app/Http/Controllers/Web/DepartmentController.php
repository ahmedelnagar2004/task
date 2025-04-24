<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the departments.
     */
    public function index(Request $request)
    {
        $query = Department::query();

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $departments = $query->withCount(['employees', 'activeCourses'])
            ->with('manager')
            ->latest()
            ->paginate(10);

        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        $employees = Employee::all();
        return view('departments.create', compact('employees'));
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'required|string',
            'manager_id' => 'required|exists:employees,id'
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')
            ->with('success', 'تم إضافة القسم بنجاح');
    }

    /**
     * Display the specified department.
     */
    public function show(Department $department)
    {
        $department->load(['manager', 'employees', 'activeCourses']);
        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(Department $department)
    {
        $employees = Employee::all();
        return view('departments.create', compact('department', 'employees'));
    }

    /**
     * Update the specified department in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'required|string',
            'manager_id' => 'required|exists:employees,id'
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')
            ->with('success', 'تم تحديث القسم بنجاح');
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy(Department $department)
    {
        // Check if department has employees
        if ($department->employees()->count() > 0) {
            return redirect()->route('departments.index')
                ->with('error', 'لا يمكن حذف القسم لأنه يحتوي على موظفين');
        }

        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'تم حذف القسم بنجاح');
    }
} 