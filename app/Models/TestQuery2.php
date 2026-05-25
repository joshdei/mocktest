<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Question; // Add this import

class TestQuery2 extends Model
{
    protected $fillable = [
        'test_id',
        'plan_id',
        'number_of_questions',
        'exam_question_ids'
    ];

    protected $casts = [
        'exam_question_ids' => 'array' // This automatically handles JSON encoding/decoding
    ];

    /**
     * Get the test associated with this query.
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Get the plan associated with this query.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(MockPrice::class, 'plan_id');
    }

    /**
     * Get exam questions relationship.
     */
    public function examQuestions()
    {
        if (empty($this->exam_question_ids)) {
            return collect();
        }
        
        // Ensure it's an array (casting might not have worked)
        $questionIds = is_array($this->exam_question_ids) 
            ? $this->exam_question_ids 
            : json_decode($this->exam_question_ids, true) ?? [];
        
        return Question::whereIn('id', $questionIds)->get();
    }

    /**
     * Set exam question IDs (safer version).
     */
    public function setExamQuestionIds(array $questionIds): self
    {
        $this->exam_question_ids = $questionIds;
        return $this; // Return self for method chaining
    }

    /**
     * Get the number of questions that were actually in the exam.
     */
    public function actualExamQuestionCount(): int
    {
        if (empty($this->exam_question_ids)) {
            return 0;
        }
        
        if (is_array($this->exam_question_ids)) {
            return count($this->exam_question_ids);
        }
        
        // If it's a string (JSON), decode it
        $decoded = json_decode($this->exam_question_ids, true);
        return is_array($decoded) ? count($decoded) : 0;
    }

    /**
     * Check if a specific question was in the exam.
     */
    public function hasQuestionInExam(int $questionId): bool
    {
        if (empty($this->exam_question_ids)) {
            return false;
        }
        
        if (is_array($this->exam_question_ids)) {
            return in_array($questionId, $this->exam_question_ids);
        }
        
        // If it's a string (JSON), decode it
        $decoded = json_decode($this->exam_question_ids, true);
        return is_array($decoded) ? in_array($questionId, $decoded) : false;
    }

    /**
     * Get exam question IDs as array (ensures array return).
     */
    public function getExamQuestionIdsArray(): array
    {
        if (empty($this->exam_question_ids)) {
            return [];
        }
        
        if (is_array($this->exam_question_ids)) {
            return $this->exam_question_ids;
        }
        
        // If it's a string (JSON), decode it
        $decoded = json_decode($this->exam_question_ids, true);
        return is_array($decoded) ? $decoded : [];
    }
}