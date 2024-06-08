<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'birthdate',
        'address',
        'image',
        'staff_id',
        'id',
        'created_by',
        'is_class_advisor',
        'designation',
        'is_hod',
    ];

    protected $table = 'staffs';

    


    public function user()
    {
        return $this->hasOne(User::class, 'id');
    }

    




    public function picture() {
        return $this->user->picture();
    }

   

    public static function active() {
        return auth()->user()->staff;
    }


    public function advisor() {
        $staff_id = $this->id;

        $class = AcademicSet::where('advisor_id', $staff_id)->get();

        return $class;
    }








    // Define relationship 


    public function classes()
    {
        return $this->hasMany(AcademicSet::class, 'advisor_id', 'id');
    }
    

    

    public function getInfo()
    {
        $values = array_map(fn ($item) => $this->{$item}, $this->fillable);
        return array_combine($this->fillabe, $values);
    }



    public function students() {
        $class_ids = $this->classes->pluck('id');

        return Student::whereIn('set_id', $class_ids);
    }

    public function class() {
        return $this->hasOne(AcademicSet::class, 'advisor_id');
    }


    public function courses() {
        return $this->hasMany(CourseAllocation::class, 'staff_id', 'id')
            ->orderBy('created_at', 'ASC');
    }


    public function uploaded_results() {
        return $this->hasMany(Result::class, 'uploaded_by', 'id');
    }

    public function updated_results() {
        return $this->hasMany(Result::class, 'updated_by', 'id');
    }



    public function is($designation) {
        return $this->designation == $designation;
    }


    public function eitherOfMyCoursesHasPractical() {
        $allocations = $this->courses->pluck('course_id');
        return Course::whereIn('id', $allocations)->where('has_practical', true)->exists();
    }


   


    
}
