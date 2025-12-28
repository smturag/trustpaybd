<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $guarded = [];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the subject (the model that was acted upon)
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Get the causer (the user who performed the action)
     */
    public function causer()
    {
        return $this->morphTo();
    }

    /**
     * Get admin who caused the activity
     */
    public function admin()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'causer_id');
    }

    /**
     * Scope to filter by log name
     */
    public function scopeLogName($query, $logName)
    {
        return $query->where('log_name', $logName);
    }

    /**
     * Scope to filter by causer type
     */
    public function scopeCausedBy($query, $causer)
    {
        return $query->where('causer_type', get_class($causer))
                    ->where('causer_id', $causer->getKey());
    }

    /**
     * Scope to filter by subject type
     */
    public function scopeForSubject($query, $subject)
    {
        return $query->where('subject_type', get_class($subject))
                    ->where('subject_id', $subject->getKey());
    }

    /**
     * Scope to filter by event
     */
    public function scopeEvent($query, $event)
    {
        return $query->where('event', $event);
    }

    /**
     * Get formatted description
     */
    public function getFormattedDescriptionAttribute()
    {
        return ucfirst($this->description);
    }

    /**
     * Get icon class based on event type
     */
    public function getIconClassAttribute()
    {
        $icons = [
            'created' => 'bx bx-plus-circle text-success',
            'updated' => 'bx bx-edit text-primary',
            'deleted' => 'bx bx-trash text-danger',
            'login' => 'bx bx-log-in text-info',
            'logout' => 'bx bx-log-out text-warning',
            'approved' => 'bx bx-check-circle text-success',
            'rejected' => 'bx bx-x-circle text-danger',
            'viewed' => 'bx bx-show text-info',
        ];

        return $icons[$this->event] ?? 'bx bx-info-circle text-secondary';
    }

    /**
     * Get color class based on event type
     */
    public function getColorClassAttribute()
    {
        $colors = [
            'created' => 'success',
            'updated' => 'primary',
            'deleted' => 'danger',
            'login' => 'info',
            'logout' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'viewed' => 'info',
        ];

        return $colors[$this->event] ?? 'secondary';
    }
}
