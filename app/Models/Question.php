<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'quiz_id',
        'question_text',
        'question_type', // multiple_choice, true_false, essay
        'points',
    ];
    
    /**
     * العلاقة مع الاختبار
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
    
    /**
     * العلاقة مع خيارات الإجابة
     */
    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }
} 