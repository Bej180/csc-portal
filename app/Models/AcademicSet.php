<?php

namespace App\Models;

use App\Models\Advisor;
use App\Models\Student;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AcademicSet
 *
 * @package App\Models
 * @property int $id
 * @property int $advisor_id
 * @property string|null $course_rep
 * @property string $name
 * @property string $token
 * @property string|null $description
 * @property int $start_year
 * @property int $end_year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class AcademicSet extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sets';

    /**
     * The length of the token.
     *
     * @var int
     */
    const TOKEN_LENGTH = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'advisor_id',
        'course_rep',
        'name',
        'token',
        'description',
        'start_year',
        'end_year'
    ];

    /**
     * Get the students associated with the academic set.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'set_id');
    }

    /**
     * Get the user (advisor) associated with the academic set.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    /**
     * Get the advisor associated with the academic set.
     */
    public function advisor()
    {
        return $this->belongsTo(Staff::class, 'advisor_id', 'id');
    }

    /**
     * Get the total number of students associated with the academic set.
     */
    public function totalStudents()
    {
        return $this->students->count();
    }

    /**
     * Get the courses associated with the academic set.
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Get the academic set from the given token.
     *
     * @param string $token The token to search for.
     * @return AcademicSet|null The academic set if found, otherwise null.
     */
    public static function getSetFromToken(string $token)
    {
        $set = self::where('token', $token);

        if ($set->exists()) {
            return $set->first();
        }

        return null;
    }

    /**
     * Retrieve the token for the given academic set.
     *
     * @param int $set_id The ID of the academic set.
     * @return string|null The token if found, otherwise null.
     */
    public static function retrieveToken(int $set_id)
    {
        $set = self::where('id', $set_id);

        if ($set->exists()) {
            return $set->first()->token;
        }

        return null;
    }

    /**
     * Get the token for the given academic set.
     *
     * @param AcademicSet $set The academic set.
     * @return string The token.
     */
    public static function getToken(AcademicSet $set)
    {
        return $set->token;
    }

    /**
     * Generate the URL for the registration page with the invite token.
     *
     * @param AcademicSet $set The academic set.
     * @param int $tokenLength The length of the token.
     * @return string|null The URL if token exists, otherwise null.
     */
    public static function tokenURL(AcademicSet $set, int $tokenLength = self::TOKEN_LENGTH)
    {
        if ($token = self::getToken($set, $tokenLength)) {
            return url("/register?invite={$token}");
        }

        return null;
    }

    /**
     * Get the academic set from the invite URL.
     *
     * @return AcademicSet|null The academic set if found, otherwise null.
     */
    public static function getSetFromURL()
    {
        if (request()->has('invite')) {
            $token = request('invite');
            $set = self::where('token', $token);
            if ($set->exists()) {
                return $set->first();
            }
        }
        return null;
    }
}
