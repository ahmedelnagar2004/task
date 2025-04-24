<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeCourse extends Model
{
    use HasFactory;
    
    protected $table = 'employee_courses';
    
    protected $fillable = [
        'employee_id',
        'course_id',
        'progress',
        'completed',
        'score',
        'start_date',
        'completion_date',
    ];
    
    /**
     * العلاقة مع الموظف
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    /**
     * العلاقة مع الدورة
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
} 