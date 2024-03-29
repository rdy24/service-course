<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $table = 'chapters';

    protected $fillable = [
        'name', 'course_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['courseId'] ?? false, function ($query, $courseId) {
            return $query->where('course_id', '=', $courseId);
        });
    }

    public function lessons() 
    {
        return $this->hasMany(Lesson::class)->orderBy('id', 'ASC');
    }
}
