<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AcademicSet;
use App\Models\User;
use App\Models\Result;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grading;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->populateWithDummyData();
   
    }
    



    private function populateWithDummyData()  {

        $getJson = fn($data) => json_decode(file_get_contents(__DIR__ . "/../../public/js/dummy/{$data}.json"), true);
        $populate = fn($schema, $data) => array_map(fn($datum)=>$schema::create($datum), $data);
        

        $admins = $getJson('admins');
        $students = $getJson('students');
        $academicSet = $getJson('academicset');
        $courses = $getJson('courses');
        $results = $getJson('results');
        
        $gradings = $getJson('gradings');
        $enrollments = $getJson('enrollments');
        ob_start();
        ob_clean();
        echo "Populating tables\n";
        foreach($admins as $admin) {
            User::createAccount($admin);
            echo "  Populated admin tables\n";
        }

        foreach($academicSet as $set) {
            User::createAccount($set);
            echo "  Populated Academic Set table\n";
        }

        foreach($students as $student) {
            User::createAccount($student);
            echo "  Populated student table\n";
        }

        
        $populate(Course::class, $courses);
        echo "  Populating Course table\n";
        ob_clean();
        
        echo "Done!!";
    }
    
}
