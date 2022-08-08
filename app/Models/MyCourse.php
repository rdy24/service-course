<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyCourse extends Model
{
    use HasFactory;

    protected $table = 'my_courses';
    protected $with = ['course'];

    protected $fillable = [
        'course_id', 'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['user_id'] ?? false, function ($query, $userId) {
            return $query->where('user_id', '=', $userId);
        });
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
