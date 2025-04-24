<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'duration',
        'status',
        'department_id',
        'image_url',
    ];
    
    /**
     * العلاقة مع الموظفين
     */
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_courses')
            ->withPivot('progress', 'completed', 'score')
            ->withTimestamps();
    }
    
    /**
     * العلاقة مع الدروس
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
    
    /**
     * العلاقة مع الاختبارات
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
} 