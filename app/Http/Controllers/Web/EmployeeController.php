<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     */
    public function index(Request $request)
    {
        $query = Employee::query();

        // Search by name or email
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by department
        if ($request->has('department') && $request->department !== '') {
            $query->where('department_id', $request->department);
        }

        $employees = $query->with('department')
            ->withCount('completedCourses')
            ->latest()
            ->paginate(10);

        $departments = Department::all();

        return view('employees.index', compact('employees', 'departments'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        $departments = Department::all();
        return view('employees.create', compact('departments'));
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees',
            'password' => ['required', 'confirmed', Password::defaults()],
            'position' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('employees', 'public');
            $validated['profile_photo_url'] = Storage::url($path);
        }

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'تم إضافة الموظف بنجاح');
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
        $employee->load(['department', 'courses']);
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        $departments = Department::all();
        return view('employees.create', compact('employee', 'departments'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees,email,' . $employee->id,
            'position' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo
            if ($employee->profile_photo_url) {
                Storage::delete(str_replace('/storage/', 'public/', $employee->profile_photo_url));
            }
            
            $path = $request->file('profile_photo')->store('employees', 'public');
            $validated['profile_photo_url'] = Storage::url($path);
        }

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'تم تحديث بيانات الموظف بنجاح');
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee)
    {
        // Delete profile photo if exists
        if ($employee->profile_photo_url) {
            Storage::delete(str_replace('/storage/', 'public/', $employee->profile_photo_url));
        }

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'تم حذف الموظف بنجاح');
    }
} 