app.controller("AdvisorStudentController", function ($scope) {
    $scope.viewEnrollments = (enrollment) => {
        
        return $scope.api(
            "/advisor/student/enrollments",
            enrollment,
            (res) => {
              $scope.enrolledData = res;
              
              
            $scope.route("reg_details");
            },
            (err) => console.error(err)
        );
    };
});
