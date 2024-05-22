app.controller("AdvisorController", function ($scope) {
    $scope.currentCoursePage = 1;
    
    $scope.currentStudentPage = 1;
    $scope.initDashboard = () => {
        $scope.api(
            "/app/advisor/dashboard",
            {},
            (res) => {
                $scope.advisor = res;
            },
            (errr) => console.error(errr)
        );
    };

    $scope.moreCourses = () => {
        $scope.api(
            '/app/advisor/load_more_courses',
            {
                more_courses: $scope.currentCoursePage
            },
            res => {
                $scope.advisor.courses = $scope.advisor.courses.concat(res.courses);
                $scope.currentCoursePage += 1;
            }
        )
    }

    $scope.moreStudents = () => {
        $scope.api(
            '/app/advisor/load_more_students',
            {
                more_students:  $scope.currentStudentPage
            },
            res => {
                $scope.advisor.my_students = $scope.advisor.my_students.concat(res.my_students);
                $scope.currentStudentPage += 1;
            }
        )
    }
});
