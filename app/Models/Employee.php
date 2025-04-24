<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'position',
        'department_id',
        'profile_photo_url',
    ];
    
    protected $hidden = [
        'password',
    ];
    
    /**
     * العلاقة مع الدورات التدريبية
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'employee_courses')
            ->withPivot('progress', 'completed', 'score')
            ->withTimestamps();
    }
    
    /**
     * الدورات المكتملة للموظف
     */
    public function completedCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'completed_by');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function managedDepartment(): HasMany
    {
        return $this->hasMany(Department::class, 'manager_id');
    }
}
