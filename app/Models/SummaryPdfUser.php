<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SummaryPdfUser extends Pivot
{
    public $timestamps = true;

    protected $table = 'summary_pdf_users';

    protected $fillable = [
        'user_id',
        'summary_pdf_id',
        // Add other pivot columns here if needed
    ];
}