<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Device
 *
 * @package App\Models
 * @property int $id
 * @property int|null $user_id
 * @property string $user_agent
 * @property string $ip_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Device extends Model
{
    use HasFactory;

    /**
     * Check if the device is registered for the user.
     *
     * @param int|null $user_id The ID of the user (defaults to the authenticated user's ID).
     * @return \App\Models\Device|null The device record if found, otherwise null.
     */
    public static function check(?int $user_id = null)
    {
        $user_id ??= auth()->id();

        $request = request();
        $userAgent = $request->header('User-Agent');
        $ipAddress = $request->ip();

        return self::where('user_id', $user_id)
            ->where('user_agent', $userAgent)
            ->where('ip_address', $ipAddress)
            ->first();
    }

    /**
     * Store a new device record if it's not already registered.
     *
     * @param int|null $user_id The ID of the user (defaults to the authenticated user's ID).
     * @return void
     */
    public static function store(?int $user_id = null)
    {
        $user_id ??= auth()->id();

        if (!self::check($user_id)) {
            // If the device is not registered, create a new record
            $request = request();

            $userAgent = $request->header('User-Agent');
            $ipAddress = $request->ip();

            $device = new Device();
            $device->user_id = $user_id;
            $device->user_agent = $userAgent;
            $device->ip_address = $ipAddress;
            $device->save();
        }
    }
}
