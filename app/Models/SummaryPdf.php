<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SummaryPdf extends Model
{
    protected $fillable = ['course_code', 'file_path', 'price'];

    public function purchasers()
    {
        return $this->belongsToMany(User::class, 'summary_pdf_users')
                    ->using(SummaryPdfUser::class)
                    
                    ->withTimestamps();
    }

    
}