<?php

namespace App\Traits;

use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    protected static function bootLogsActivity()
    {
        // Log when a model is created
        static::created(function ($model) {
            $model->logActivity('created', 'Created ' . class_basename($model));
        });

        // Log when a model is updated
        static::updated(function ($model) {
            $model->logActivity('updated', 'Updated ' . class_basename($model), $model->getChanges());
        });

        // Log when a model is deleted
        static::deleted(function ($model) {
            $model->logActivity('deleted', 'Deleted ' . class_basename($model));
        });
    }

    /**
     * Log an activity
     *
     * @param string $event
     * @param string $description
     * @param array $properties
     * @param string|null $logName
     * @return AdminActivityLog|null
     */
    public function logActivity(string $event, string $description, array $properties = [], ?string $logName = null)
    {
        try {
            $causer = Auth::guard('admin')->user() ?? Auth::user();
            
            return AdminActivityLog::create([
                'log_name' => $logName ?? $this->getActivityLogName(),
                'description' => $description,
                'subject_type' => get_class($this),
                'subject_id' => $this->getKey(),
                'causer_type' => $causer ? get_class($causer) : null,
                'causer_id' => $causer ? $causer->getKey() : null,
                'properties' => $properties,
                'event' => $event,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log activity: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the log name for the model
     *
     * @return string
     */
    protected function getActivityLogName(): string
    {
        if (property_exists($this, 'activityLogName')) {
            return $this->activityLogName;
        }

        return strtolower(class_basename($this));
    }

    /**
     * Disable activity logging for this model instance
     *
     * @return $this
     */
    public function disableLogging()
    {
        $this->loggingDisabled = true;
        return $this;
    }

    /**
     * Enable activity logging for this model instance
     *
     * @return $this
     */
    public function enableLogging()
    {
        $this->loggingDisabled = false;
        return $this;
    }

    /**
     * Check if logging is disabled
     *
     * @return bool
     */
    public function isLoggingDisabled(): bool
    {
        return property_exists($this, 'loggingDisabled') && $this->loggingDisabled === true;
    }

    /**
     * Log a custom activity
     *
     * @param string $description
     * @param string $event
     * @param array $properties
     * @return AdminActivityLog|null
     */
    public static function logCustomActivity(string $description, string $event = 'custom', array $properties = [])
    {
        try {
            $causer = Auth::guard('admin')->user() ?? Auth::user();
            
            return AdminActivityLog::create([
                'log_name' => 'custom',
                'description' => $description,
                'subject_type' => null,
                'subject_id' => null,
                'causer_type' => $causer ? get_class($causer) : null,
                'causer_id' => $causer ? $causer->getKey() : null,
                'properties' => $properties,
                'event' => $event,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log custom activity: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Log login activity
     *
     * @param mixed $user
     * @param array $properties
     * @return AdminActivityLog|null
     */
    public static function logLogin($user, array $properties = [])
    {
        try {
            return AdminActivityLog::create([
                'log_name' => 'authentication',
                'description' => 'User logged in',
                'subject_type' => get_class($user),
                'subject_id' => $user->getKey(),
                'causer_type' => get_class($user),
                'causer_id' => $user->getKey(),
                'properties' => array_merge([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ], $properties),
                'event' => 'login',
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log login activity: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Log logout activity
     *
     * @param mixed $user
     * @param array $properties
     * @return AdminActivityLog|null
     */
    public static function logLogout($user, array $properties = [])
    {
        try {
            return AdminActivityLog::create([
                'log_name' => 'authentication',
                'description' => 'User logged out',
                'subject_type' => get_class($user),
                'subject_id' => $user->getKey(),
                'causer_type' => get_class($user),
                'causer_id' => $user->getKey(),
                'properties' => array_merge([
                    'ip' => request()->ip(),
                ], $properties),
                'event' => 'logout',
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log logout activity: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Log approval activity
     *
     * @param string $description
     * @param array $properties
     * @return AdminActivityLog|null
     */
    public function logApproval(string $description, array $properties = [])
    {
        return $this->logActivity('approved', $description, $properties);
    }

    /**
     * Log rejection activity
     *
     * @param string $description
     * @param array $properties
     * @return AdminActivityLog|null
     */
    public function logRejection(string $description, array $properties = [])
    {
        return $this->logActivity('rejected', $description, $properties);
    }

    /**
     * Log view activity
     *
     * @param string $description
     * @param array $properties
     * @return AdminActivityLog|null
     */
    public function logView(string $description, array $properties = [])
    {
        return $this->logActivity('viewed', $description, $properties);
    }
}
