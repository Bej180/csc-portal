<?php

namespace App\Models;

use App\Mail\ResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PasswordReset extends Model
{
    use HasFactory;
    protected $table = 'password_reset_tokens';

    protected $fillable = [
        'expires', 
        'token',
        'email'
    ];


    public function user() {
        return $this->belongsTo(User::class, 'email', 'email');
    }


    public static function generateToken() {
        do {
            $token = Str::random(60);
        } while(self::where('token', $token)->exists());
        return $token;
    }

    public static function findAccountWithToken($token) {
        $account = self::where('token', $token)->with('user')->first();

        return $account;
    }

    public function tokenIsActive() {
       
        return time() <= strtotime($this->expires);
    }


    public function resetTimeChecker($token)
    {
        $account = PasswordReset::findAccountWithToken($token);
        if (!$account) {
            return null;
        }

        $expires = Carbon::parse($account->expires);
        $now = Carbon::now();

        $seconds = $now->diffInSeconds($expires, false);
        $minutes = $now->diffInMinutes($expires, false);

        return compact('seconds', 'minutes');
        
    }
}
