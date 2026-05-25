<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty',
        'programme',
        'level',
        'semester',
        'course_code',
        'title',
        'unit',
        'status',
        'course_fee',
        'exam_fee',
        'course_material',
        'fee_status',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}