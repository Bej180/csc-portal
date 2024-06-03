app.controller("AdminStaffController", function ($scope) {
    $scope.staffData = {};
    $scope.staffs = [];
    $scope.courses_selected = [];
    $scope.assign_course = []; // Selected course to assign to staff
    $scope.currentPage = 1;
    $scope.propertyName = 'name';
    $scope.reverse = true;
    $scope.sorting = {
        attr: null,
        order: 'DESC'
    };

    $scope.boot = (type = 'lecturer') => {
        $scope.type = type;
        $scope.api(
            "/app/admin/staff/index",
            {
                // type
            },
            (res) => ($scope.staffs = res.data)
        );
    };
   
    

    /**
     * loadCourses
     * Loads courses based on the selected level and semester.
     */
    $scope.loadCourses = () => {
        if ($scope.level && $scope.semester) {
            $scope.api(
                "/app/admin/courses",
                {
                    level: $scope.level,
                    semester: $scope.semester,
                },
                (res) => {
                    $scope.courses = [...$scope.courses_selected, ...res];
                    // $scope.courses_selected = [];
                }
            );
        }
    };

    

});
