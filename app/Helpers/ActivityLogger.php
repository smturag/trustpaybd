<?php

namespace App\Helpers;

use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Log a generic activity
     *
     * @param string $description
     * @param string $event
     * @param string|null $logName
     * @param mixed|null $subject
     * @param array $properties
     * @return AdminActivityLog|null
     */
    public static function log(
        string $description,
        string $event = 'custom',
        ?string $logName = null,
        $subject = null,
        array $properties = []
    ) {
        try {
            $causer = Auth::guard('admin')->user() ?? Auth::user();
            
            // Get device and location info
            $deviceInfo = DeviceDetector::getFullDeviceDetails();
            
            $data = [
                'log_name' => $logName ?? 'default',
                'description' => $description,
                'causer_type' => $causer ? get_class($causer) : null,
                'causer_id' => $causer ? $causer->getKey() : null,
                'properties' => $properties,
                'event' => $event,
                'ip_address' => $deviceInfo['ip_address'],
                'user_agent' => $deviceInfo['user_agent'],
                'device' => $deviceInfo['device'],
                'browser' => $deviceInfo['browser'],
                'platform' => $deviceInfo['platform'],
                'country' => $deviceInfo['country'],
                'country_code' => $deviceInfo['country_code'],
                'city' => $deviceInfo['city'],
            ];

            if ($subject) {
                $data['subject_type'] = is_object($subject) ? get_class($subject) : null;
                $data['subject_id'] = is_object($subject) ? $subject->getKey() : null;
            }

            return AdminActivityLog::create($data);
        } catch (\Exception $e) {
            \Log::error('ActivityLogger: Failed to log activity - ' . $e->getMessage());
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
        $userName = $user->admin_name ?? $user->name ?? $user->username ?? 'User';
        
        return self::log(
            description: "{$userName} logged in",
            event: 'login',
            logName: 'authentication',
            subject: $user,
            properties: $properties
        );
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
        $userName = $user->admin_name ?? $user->name ?? $user->username ?? 'User';
        
        return self::log(
            description: "{$userName} logged out",
            event: 'logout',
            logName: 'authentication',
            subject: $user,
            properties: $properties
        );
    }

    /**
     * Log model created
     *
     * @param mixed $model
     * @param string|null $description
     * @return AdminActivityLog|null
     */
    public static function logCreated($model, ?string $description = null)
    {
        $modelName = class_basename($model);
        $desc = $description ?? "Created {$modelName}";
        
        return self::log(
            description: $desc,
            event: 'created',
            logName: strtolower($modelName),
            subject: $model
        );
    }

    /**
     * Log model updated
     *
     * @param mixed $model
     * @param array $changes
     * @param string|null $description
     * @return AdminActivityLog|null
     */
    public static function logUpdated($model, array $changes = [], ?string $description = null)
    {
        $modelName = class_basename($model);
        $desc = $description ?? "Updated {$modelName}";
        
        return self::log(
            description: $desc,
            event: 'updated',
            logName: strtolower($modelName),
            subject: $model,
            properties: ['changes' => $changes]
        );
    }

    /**
     * Log model deleted
     *
     * @param mixed $model
     * @param string|null $description
     * @return AdminActivityLog|null
     */
    public static function logDeleted($model, ?string $description = null)
    {
        $modelName = class_basename($model);
        $desc = $description ?? "Deleted {$modelName}";
        
        return self::log(
            description: $desc,
            event: 'deleted',
            logName: strtolower($modelName),
            subject: $model
        );
    }

    /**
     * Log approval
     *
     * @param mixed $model
     * @param string|null $description
     * @param array $properties
     * @return AdminActivityLog|null
     */
    public static function logApproved($model, ?string $description = null, array $properties = [])
    {
        $modelName = class_basename($model);
        $desc = $description ?? "Approved {$modelName}";
        
        return self::log(
            description: $desc,
            event: 'approved',
            logName: strtolower($modelName),
            subject: $model,
            properties: $properties
        );
    }

    /**
     * Log rejection
     *
     * @param mixed $model
     * @param string|null $description
     * @param array $properties
     * @return AdminActivityLog|null
     */
    public static function logRejected($model, ?string $description = null, array $properties = [])
    {
        $modelName = class_basename($model);
        $desc = $description ?? "Rejected {$modelName}";
        
        return self::log(
            description: $desc,
            event: 'rejected',
            logName: strtolower($modelName),
            subject: $model,
            properties: $properties
        );
    }

    /**
     * Log view action
     *
     * @param mixed $model
     * @param string|null $description
     * @return AdminActivityLog|null
     */
    public static function logViewed($model, ?string $description = null)
    {
        $modelName = class_basename($model);
        $desc = $description ?? "Viewed {$modelName}";
        
        return self::log(
            description: $desc,
            event: 'viewed',
            logName: strtolower($modelName),
            subject: $model
        );
    }

    /**
     * Log payment request action
     *
     * @param string $action
     * @param mixed $paymentRequest
     * @param array $properties
     * @return AdminActivityLog|null
     */
    public static function logPaymentRequest(string $action, $paymentRequest, array $properties = [])
    {
        return self::log(
            description: "Payment request {$action}",
            event: $action,
            logName: 'payment_request',
            subject: $paymentRequest,
            properties: $properties
        );
    }

    /**
     * Log withdrawal action
     *
     * @param string $action
     * @param mixed $withdrawal
     * @param array $properties
     * @return AdminActivityLog|null
     */
    public static function logWithdrawal(string $action, $withdrawal, array $properties = [])
    {
        return self::log(
            description: "Withdrawal {$action}",
            event: $action,
            logName: 'withdrawal',
            subject: $withdrawal,
            properties: $properties
        );
    }

    /**
     * Log balance update
     *
     * @param mixed $user
     * @param float $oldBalance
     * @param float $newBalance
     * @param string $reason
     * @return AdminActivityLog|null
     */
    public static function logBalanceUpdate($user, float $oldBalance, float $newBalance, string $reason = '')
    {
        return self::log(
            description: "Balance updated from {$oldBalance} to {$newBalance}" . ($reason ? " - {$reason}" : ''),
            event: 'balance_updated',
            logName: 'balance',
            subject: $user,
            properties: [
                'old_balance' => $oldBalance,
                'new_balance' => $newBalance,
                'difference' => $newBalance - $oldBalance,
                'reason' => $reason
            ]
        );
    }

    /**
     * Log settings change
     *
     * @param string $setting
     * @param mixed $oldValue
     * @param mixed $newValue
     * @return AdminActivityLog|null
     */
    public static function logSettingsChange(string $setting, $oldValue, $newValue)
    {
        return self::log(
            description: "Settings '{$setting}' changed",
            event: 'settings_changed',
            logName: 'settings',
            properties: [
                'setting' => $setting,
                'old_value' => $oldValue,
                'new_value' => $newValue
            ]
        );
    }

    /**
     * Log permission change
     *
     * @param mixed $user
     * @param string $action
     * @param array $permissions
     * @return AdminActivityLog|null
     */
    public static function logPermissionChange($user, string $action, array $permissions)
    {
        $userName = $user->admin_name ?? $user->name ?? $user->username ?? 'User';
        
        return self::log(
            description: "Permissions {$action} for {$userName}",
            event: 'permissions_changed',
            logName: 'permissions',
            subject: $user,
            properties: [
                'action' => $action,
                'permissions' => $permissions
            ]
        );
    }

    /**
     * Log role change
     *
     * @param mixed $user
     * @param string $action
     * @param array $roles
     * @return AdminActivityLog|null
     */
    public static function logRoleChange($user, string $action, array $roles)
    {
        $userName = $user->admin_name ?? $user->name ?? $user->username ?? 'User';
        
        return self::log(
            description: "Roles {$action} for {$userName}",
            event: 'roles_changed',
            logName: 'roles',
            subject: $user,
            properties: [
                'action' => $action,
                'roles' => $roles
            ]
        );
    }
}
