<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPdfPayment extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'reference',
        'status',
        'pdf_ids',
    ];
}