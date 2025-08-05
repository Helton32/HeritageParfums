<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'phone',
        'is_read',
        'replied_at',
        'admin_notes',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'replied_at' => 'datetime',
    ];

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeReplied($query)
    {
        return $query->whereNotNull('replied_at');
    }

    public function scopeUnanswered($query)
    {
        return $query->whereNull('replied_at');
    }

    // Accessors
    public function getStatusAttribute()
    {
        if ($this->replied_at) {
            return 'replied';
        }
        
        return $this->is_read ? 'read' : 'unread';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'unread' => 'Non lu',
            'read' => 'Lu',
            'replied' => 'Répondu',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'unread' => 'warning',
            'read' => 'info',
            'replied' => 'success',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    // Methods
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function markAsReplied($adminNotes = null)
    {
        $this->update([
            'is_read' => true,
            'replied_at' => now(),
            'admin_notes' => $adminNotes,
        ]);
    }
}
