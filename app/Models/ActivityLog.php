<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ActivityLog
 * 
 * @package App\Models
 * @property int $id
 * @property int|null $user_id
 * @property string $log_name
 * @property string|null $description
 * @property string $ip_address
 * @property string $user_agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class ActivityLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'log_name',
        'description',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the user associated with the activity log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log a generic activity.
     *
     * @param User $user The user to log into the activity for.
     * @param string $logName The name of the log.
     * @param string|null $description The description of the log.
     * @return void
     */
    public static function log(User $user, $logName, $description = null)
    {
        self::create([
            'user_id' => $user->id,
            'log_name' => $logName,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Log a password change activity.
     *
     * @param User $user The user who changed the password.
     * @return void
     */
    public static function logPasswordChangeActivity(User $user)
    {
        self::create([
            'user_id' => $user->id,
            'log_name' => 'password_changed',
            'description' => 'changed password',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Log a login activity.
     *
     * @param User $user The user who logged in.
     * @return void
     */
    public static function logLoginActivity(User $user)
    {
        self::create([
            'user_id' => $user->id,
            'log_name' => 'login',
            'description' => 'logged in',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Log a password reset request activity.
     *
     * @param User $user The user who requested the password reset.
     * @return void
     */
    public static function logRequestPasswordChangeActivity(User $user, ?string $message = null)
    {
        self::create([
            'user_id' => $user->id,
            'log_name' => 'password_reset',
            'description' => $message ?? 'requested for password reset',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Log an updated profile activity.
     *
     * @param User $user The user who updated their profile.
     * @return void
     */
    public static function logUpdatedProfileActivity(User $user, ?string $message = null)
    {
        self::create([
            'user_id' => $user->id,
            'log_name' => 'profile_update',
            'description' => $message ?? 'Updated profile',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

   
    /**
     * Log a registered course activity.
     *
     * @param User $user The user who registered for the course.
     * @return void
     */
    public static function logCourseRegistrationActivity(User $user, ?string $message = null)
    {
        self::create([
            'user_id' => $user->id,
            'log_name' => 'registered_courses',
            'description' => $message ?? 'registered courses',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}


