<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'topic',
        'message',
        'is_read',
        'is_replied',
        'replied_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_replied' => 'boolean',
        'replied_at' => 'datetime',
    ];

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function markAsReplied()
    {
        $this->update([
            'is_replied' => true,
            'replied_at' => now()
        ]);
    }

    public function getTopicLabelAttribute()
{
    return match($this->topic) {
        'order' => 'Order / Payment Issue',
        'request' => 'Request a Course Material',
        'general' => 'General Enquiry',
        'submit' => 'Submit Past Questions',
        'partnership' => 'Partnership / Advertising',
        default => ucfirst($this->topic),
    };
}
}