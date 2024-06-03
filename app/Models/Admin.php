<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Admin
 *
 * @package App\Models
 * @property int $id
 * @property int|null $admin_id
 * @property string|null $birthdate
 * @property string|null $address
 * @property string|null $gender
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Admin extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id',
        'birthdate',
        'address',
        'title',
        'staff_id',
        'id',
    ];

   

    /**
     * Get all academic sets.
     *
     * @return \Illuminate\Database\Eloquent\Collection All academic sets.
     */
    public static function academicSets()
    {
        return AcademicSet::all();
    }

    /**
     * Get the picture of the admin.
     *
     * @return string The picture URL.
     */
    public function picture()
    {
        return $this->user->picture();
    }

    /**
     * Get the user associated with the admin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne The user relationship.
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Create a new admin instance and save it to the database.
     *
     * @param array $data The admin data.
     * @return Admin The created admin instance.
     */
    public static function _create($data)
    {
       
        $obj = new Admin();
        $fillable = Arr::only($data, $obj->fillable);
        $fillable['id'] = $data['id'];
        return self::create($fillable);
    }
}
