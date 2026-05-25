<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['status','course_name', 'course_code'];
    
    public function department()
    {
        return $this->belongsTo(Department::class,'course_department');
    }
public function questions()
{
    return $this->hasMany(Question::class);
}


public function programmes()
{
    return $this->belongsToMany(Programme::class, 'course_programme');
}


   
}