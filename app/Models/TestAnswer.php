<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestAnswer extends Model
{
    // Mass assignable attributes
    protected $fillable = [
        'test_id',
        'question_id',
        'selected_option',
        'is_correct',
    ];

    /**
     * Get the test this answer belongs to.
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Get the question associated with this answer.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}