app.controller("AdminStaffController", function ($scope) {
    $scope.staffData = {};
    $scope.staffs = [];
    $scope.courses_selected = [];
    $scope.assign_course = []; // Selected course to assign to staff
    $scope.currentPage = 1;
    $scope.sorting = {
        attr: null,
        order: 'DESC'
    };

    $scope.boot = (type = 'lecturer') => {
        $scope.type = type;
        $scope.api(
            "/app/admin/staff/index",
            {
                type
            },
            (res) => ($scope.staffs = res.data)
        );
    };
    $scope.sortStaff = function() {
        if ($scope.sorting.attr) {
            $scope.api(
                '/app/admin/staff/index',
                {
                    search: $scope.searchinput,
                    page:$scope.page,
                    sort: [$scope.sorting.attr, $scope.sorting.order]
                },
                res => {
                    $scope.staffs = res.data;
                }
            )
        }
    }

    $scope.more = () => {

        $scope.api(
            "/app/admin/staff/index",
            {
                type,
                search,
                page: $scope.currentPage
            },
            (res) => {
                $scope.staffs = res.data;
                $scope.currentPage += 1;
            }
        );

    }

    $scope.Search = (search) => {
        $scope.api(
            "/app/admin/staff/index",
            {
                search,
            },
            (res) => ($scope.staffs = res.data)
        );
    };
    $scope.courseIndex = (course_code) => {
        let index = -1;
        for (var i = 0; i < $scope.courses_selected.length; i++) {
            if ($scope.courses_selected[i].code == course_code) {
                index = i;
            }
        }
        return index;
    };

    $scope.toggleSelectCourse = (course) => {
        let index = $scope.courseIndex(course.code);

        if (index >= 0) {
            $scope.courses_selected.splice(index, 1);
        } else {
            $scope.courses_selected.push(course);
        }
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

    $scope.displayCoursesToBeAssigned = (course) => {
        $scope.api("/app/admin/courses", course, (res) => {
            $scope.courses = res;
        });
    };

    $scope.createStaffAccount = () => {
        let courses = [];

        $scope.courses_selected.forEach((course) => {
            courses.push(course.id);
        });

        return $scope.api(
            "/app/admin/staff/create",
            {
                courses,
                ...$scope.staffData,
            },
            (response) => {
                //$scope.searchFor('staffs');
            }
        );
    };
});
