<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'course_id',
        'title',
        'content',
        'order',
        'duration',
        'video_url',
        'resource_url',
    ];
    
    /**
     * العلاقة مع الدورة
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
} 