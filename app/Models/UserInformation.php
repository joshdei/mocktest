<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    protected $fillable = [
        'user_id',
        'mat_number',
        'username',
        'semester',
        'zone',
        'study_centre',
        'faculty',
        'department',
        'programme',
        'level',
    ];



    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}


// In UserInformation.php

public function programme()
{
    return $this->belongsTo(Programme::class, 'programme', 'id');
}

public function department()
{
    return $this->belongsTo(Department::class, 'department', 'id');
}

}