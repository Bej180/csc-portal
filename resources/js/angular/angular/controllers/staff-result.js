app.controller("StaffResultsController", function ($scope) {
    $scope.results = {};
    $scope.uploader = { student: [], data: {} };
    $scope.enrollments = {
        students: [],
    };

   
    $scope.displayStudentsEnrolledForCourse = (data) => {
        return $scope.api(
            "/app/staff/courses/students",
            data,
            (res) => {
                console.log({res});
                if (res.submitted) {
                   $scope.ViewCourseResult(res);
                }
                else {
                    $scope.enrollments = res;
                    $scope.route('add_result');
                }
            }
        );
    };

    $scope.updateGrade = (event, index) => {
       
        const results = $scope.enrollments.students[index].results;
       
        let exam = 0;
        let lab = 0;
        let test = 0;
        if (typeof results === 'object' && results !== null) {
            exam = parseInt(results.exam || exam);
            lab = parseInt(results.lab || lab);
            test = parseInt(results.test || test);
            
            let score = exam;
            score += lab;
            score += test;
            let grade = 'F';

            switch(true) {
                case $scope.enrollments.has_practical && lab < 1: grade = 'F'; break;
                case score > 69: grade = 'A'; break;
                case score > 59: grade = 'B'; break;
                case score > 49: grade = 'C'; break;
                case score > 44: grade = 'D'; break;
                case score > 39: grade = 'E'; break;
            }
            $scope.enrollments.students[index].results.score = score;
            $scope.enrollments.students[index].results.grade = grade;
            $scope.enrollments.students[index].results.remark = grade === 'F' ? 'FAILED':'PASSED';

        }

    

    }

    

    $scope.ViewCourseResult = ({ course_id, semester, session }) => {
        
        return $scope.api({
            url: "/app/staff/course/results",
            data: {
                course_id,
                semester,
                session,
            },
            success: function(res){
            console.log({event: this.event, response: res});
                $scope.view_course_results = res;
                $scope.route("course_results");
            }, 
            error: res => {
                console.log(res);
            }
        });
    };

    $scope.uploadResults = (event) => {
        const parent = $(event.target).closest('div');
        const buttons = parent.find('button');

        const {students, course_id, session} = $scope.processedData();
        
       
        $scope.api({
            url: "/app/staff/results/add",
            askPin: true,
            trigger: buttons,
            data: {
                students,
                course_id,
                session
            },
            success: (res) => {
                $scope.loadResults();
                $scope.route('index');
            }
        });
  
    };
    $scope.processedData = () => {
        let records = $scope.enrollments;
        

        records.students = records.students.map((student) => {
            return {
                
                lab: student.results?.lab,
                test: student.results?.test,
                exam: student.results?.exam,
                score: student.results?.score,
                reg_no: student.reg_no,
                ...student,
                
            };
        });
        return records;
    }

    $scope.saveResultsAsDraft = (event) => {

        const parent = $(event.target).closest('div');
        const buttons = parent.find('button');

        return $scope.api({
            trigger: buttons,
            url: "/app/staff/results/save_draft",
            data: $scope.processedData(),
            success: res => {
                $scope.loadResults();
            }
        });
    };

    $scope.loadResults = () => {
        return $scope.api("/app/staff/results/index", {}, (res) => {
            $scope.all_results = res;
        });
    };
});
