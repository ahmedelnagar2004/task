<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'passing_score',
        'time_limit',
    ];
    
    /**
     * العلاقة مع الدورة
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    /**
     * العلاقة مع الأسئلة
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
} 