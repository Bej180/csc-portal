<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Course
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $level
 * @property int $units
 * @property string $option
 * @property int|null $prerequisite
 * @property int|null $reference_id
 * @property string $semester
 * @property string $exam
 * @property string $test
 * @property string $image
 * @property string|null $outline
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'level',
        'units',
        'option',
        'prerequisite',
        'reference_id',
        'semester',
        'image',
        'outline',
        'cordinator'
    ];

    /**
     * Get the result associated with the course.
     */
    public function result()
    {
        return $this->hasOne(Result::class, 'course_id', 'id');
    }

    /**
     * Get all active courses.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function active()
    {
        return Course::where('status', 'active');
    }

    /**
     * Get all inactive courses.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function inActive()
    {
        return Course::where('status', 'inactive')->get();
    }

    /**
     * Get the materials associated with the course.
     */
    public function materials()
    {
        return $this->hasMany(Material::class, 'course_code', 'code');
    }

    /**
     * Get all active courses.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllCourses()
    {
        return self::active()->get()->unique('code');
    }

    /**
     * Archive a course.
     *
     * @param int $course_id The ID of the course to archive.
     * @return void
     */
    public static function archiveCourse(int $course_id)
    {
        $course = Course::find($course_id)->get();
        // Logic for archiving course
    }

    /**
     * Get the prerequisites of the course.
     */
    public function prerequisites()
    {
        return $this->hasOne(Course::class, 'prerequisite', 'id');
    }

    /**
     * Get the enrollments associated with the course.
     */
    public function enrollments()
    {
        return $this->hasOne(Enrollment::class, 'course_id');
    }


    public static function getEnrollments($semester, $session, ?int $reg_no = null)
    {
        $reg_no ??= auth()->user()->student->reg_no;

        return Enrollment::where('semester', $semester)
            ->with('course')
            ->where('session', $session)
            ->where('reg_no', $reg_no)
            ->get();
    }

    /**
     * Get courses by level and semester.
     *
     * @param string $level The level of the course.
     * @param string $semester The semester of the course.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCourses($level, $semester)
    {
        return self::active()
            ->with('enrollments')
            ->where('level', $level)
            ->where('semester', $semester)
            ->orderBy('option', 'desc')
            ->get();
    }

    /**
     * List courses for registration.
     *
     * @param string $level The level of the course.
     * @param string $semester The semester of the course.
     * @param array|null $borrowed The IDs of borrowed courses.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function listCoursesForRegistrations($level, $semester, ?array $borrowed)
    {
        $borrowed ??= [];
        $borrowed = array_filter($borrowed, fn ($item) => is_numeric($item));

        return self::active()
            ->where('level', $level)
            ->where('semester', $semester)
            ->orWhereIn('id', $borrowed)
            ->with('enrollments')
            ->orderBy('option', 'desc')->get();
    }

    /**
     * Generate academic sessions.
     *
     * @param int|null $from The starting year.
     * @param int|null $to The ending year.
     * @param string $separator The separator between years.
     * @return array The generated academic sessions.
     */
    public static function generateSessions(?int $from = null, ?int $to = null, $separator = '/')
    {
        $sessions = [];
        $to ??= date('Y');

        // Generate sessions for the last ten years
        if (!$from) {
            $to = date('Y');
            $from = $to - 10;
        }

        $from = min($from, $to);
        $to = max($from, $to);

        if ($to == $from) {
            $to += 1;
        }

        $diff = $to - $from;
        for ($i = 0; $i < $diff; $i++) {
            $startSemester = $from + $i;
            $endSemester = $from + $i + 1;

            $sessions[] = $startSemester . $separator . $endSemester;
        }

        return $sessions;
    }


    public function lecturers()
    {
        return $this->hasMany(CourseAllocation::class, 'course_id', 'id');
    }

    public function cordinator()
    {
        return $this->hasOne(Staff::class, 'id', 'cordinator');
    }

    public function practical()
    {
        return $this->lecturers->where('role', 'technologies')->get();
    }

    public function technologists()
    {
        $technologist = Staff::where('designation', 'technologist')->get();

        $ids = $technologist->pluck('id');
        return CourseAllocation::whereIn('staff_id', $ids)
            ->where('course_id', '=', $this->id)
            ->with('user')
            ->get();
    }
}
