<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Hash;

class AccessToken extends Model
{
    use HasFactory;

    protected $table = 'personal_access_tokens';

    /**
     * Generate the token string.
     *
     * @return string
     */
    public function generateTokenString(): string
    {
        $tokenEntropy = Str::random(40);
        return sprintf(
            '%s%s%s',
            config('sanctum.token_prefix', ''),
            $tokenEntropy,
            hash('crc32b', $tokenEntropy)
        );
    }


   

    /**
     * Check if the token has expired.
     *
     * @return bool
     */
    public function hasExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /**
     * Check if the given token matches the current token.
     *
     * @param string $token
     * @return bool
     */
    public function equals(string $token): bool
    {
        return $this->token === $token;
    }


   

    /**
     * Terminate all access tokens for the current tokenable.
     */
    public function terminateAll(): void
    {
        self::where('tokenable_id', $this->tokenable_id)->update([
            'expired_at' => Carbon::now(),
        ]);
    }

    /**
     * Terminate all access tokens for the current tokenable except this one.
     */
    public function terminateAllExceptCurrent(): void
    {
        self::where('tokenable_id', $this->tokenable_id)
            ->where('id', '!=', $this->id)
            ->update([
                'expired_at' => Carbon::now(),
            ]);
    }


    /**
     * Terminate all access token whose id was specified
     * @param array $tokens_ids
     */
    public function terminateAllIn(array $tokens_ids): void
    {
        self::where('tokenable_id', $this->tokenable_id)
            ->whereIn('id', $tokens_ids)
            ->update([
                'expired_at' => Carbon::now(),
            ]);
    }

    

    /**
     * Terminate the current access token.
     */
    public function terminate(bool $delete = true): void
    {
        if ($delete) {
            $this->delete(); 
        } else {
            $this->expired_at = Carbon::now();
            $this->save();
        }
    }

    /**
     * Regenerate the token string and save.
     *
     * @return string
     */
    public function regenerateToken(): string
    {
        $user = $this->tokenable;
        if ($user) {
            return $user->createToken($this->name)->plainTextToken;
        }
        return null;
    }

    /**
     * Extend the expiration time of the token.
     *
     * @param int $days
     * @return void
     */
    public function extendExpiration(int $days): void
    {
        $this->expires_at = Carbon::now()->addDays($days);
        $this->save();
    }

    /**
     * Revoke tokens by a specific attribute.
     *
     * @param string $attribute
     * @param mixed $value
     * @return void
     */
    public static function revokeByAttribute(string $attribute, $value): void
    {
        self::where($attribute, $value)->update([
            'expired_at' => Carbon::now(),
        ]);
    }

    /**
     * List all active tokens for a specific tokenable.
     *
     * @param int $tokenableId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function listActiveTokens(int $tokenableId)
    {
        return self::where('tokenable_id', $tokenableId)
            ->where(function ($query) {
                $query->whereNull('expired_at')
                      ->orWhere('expired_at', '>', Carbon::now());
            })
            ->get();
    }

    /**
     * Get the user or entity related to the token.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function tokenable()
    {
        return $this->morphTo();
    }

    /**
     * Check if the token is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return !$this->hasExpired();
    }

    /**
     * Get the plain text token.
     *
     * @return string
     */
    public function getPlainTextToken(): string
    {
        return $this->id . '|' . $this->token;
    }

    /**
     * Revoke a specific token by value.
     *
     * @param string $token
     * @return void
     */
    public static function revokeToken(string $token): void
    {
        self::where('token', $token)->update([
            'expired_at' => Carbon::now(),
        ]);
    }

    /**
     * Get the creation date of the token.
     *
     * @return \Carbon\Carbon
     */
    public function getCreationDate(): Carbon
    {
        return $this->created_at;
    }

    /**
     * Check if the token belongs to a specific tokenable.
     *
     * @param int $tokenableId
     * @return bool
     */
    public function belongsToTokenable(int $tokenableId): bool
    {
        return $this->tokenable_id === $tokenableId;
    }

    /**
     * Partially mask the token for security.
     *
     * @return string
     */
    public function maskToken(): string
    {
        return substr($this->token, 0, 4) . str_repeat('*', strlen($this->token) - 8) . substr($this->token, -4);
    }

    /**
     * Check if the token is valid (not expired and matches the given token).
     *
     * @param string $token
     * @return bool
     */
    public function isValid(string $token): bool
    {
        return $this->equals($token) && !$this->hasExpired();
    }

    /**
     * Get tokens by date range.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getTokensByDateRange(Carbon $startDate, Carbon $endDate)
    {
        return self::whereBetween('created_at', [$startDate, $endDate])->get();
    }

    /**
     * Get all tokens for a specific user or entity.
     *
     * @param int $tokenableId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllTokensForTokenable(int $tokenableId)
    {
        return self::where('tokenable_id', $tokenableId)->get();
    }

    /**
     * Revoke all tokens except the current one.
     */
    public function revokeAllExceptCurrent(): void
    {
        self::where('tokenable_id', $this->tokenable_id)
            ->where('id', '!=', $this->id)
            ->update([
                'expired_at' => Carbon::now(),
            ]);
    }

    /**
     * Refresh the token (extend expiry and regenerate token string).
     *
     * @param int $days
     * @return string
     */
    public function refreshToken(int $days): string
    {
        $this->token = $this->generateTokenString();
        $this->expires_at = Carbon::now()->addDays($days);
        $plainTextToken = $this->id . '|' . $this->token;
        $this->save();

        return $plainTextToken;
    }

    /**
     * Invalidate all tokens.
     *
     * @return void
     */
    public static function invalidateAllTokens(): void
    {
        self::update([
            'expired_at' => Carbon::now(),
        ]);
    }
}
