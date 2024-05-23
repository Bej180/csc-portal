app.controller("HODResultsController", function ($scope) {
    $scope.pending_results = [];
    $scope.approved_results = [];
    $scope.initialized = false;
    $scope.view_course_results = [];
    $scope.search_input = null;
    $scope.sorting = {
        attr: "id",
        order: "ASC",
    };

    $scope.initializePage = () => {
        $scope.api(
            "/app/hod/results/index",
            {},
            (res) => {
                $scope.pending_results = Object.values(res.pendingResults);
                $scope.approved_results = Object.values(res.approvedResults);
                $scope.initialized = true;
                console.log(res);
            },
            (error) => {
                $scope.initialized = true;
            }
        );
    };

    $scope.SearchIn = (text) => {
        $scope.api(
            "/app/hod/results/index",
            {
                search: text,
            },
            (res) => {
                $scope.pending_results = Object.values(res.pendingResults);
                $scope.approved_results = Object.values(res.approvedResults);
                $scope.initialized = true;
            },
            (error) => {
                $scope.initialized = true;
            }
        );
    };

    $scope.sortResults = () => {
        $scope.api(
            "/app/hod/results/index",
            {
                sort: [$scope.sorting.attr, $scope.sorting.order],
            },
            (res) => {
                $scope.pending_results = Object.values(res.pendingResults);
                $scope.approved_results = Object.values(res.approvedResults);
                $scope.initialized = true;
            },
            (error) => {
                $scope.initialized = true;
            }
        );
    };

    $scope.approveResult = (result) => {
        console.log({ result });
        return $scope.api(
            "/app/hod/results/approve",
            {
                results_id: result.reference_id,
            },
            (res) => {
                $scope.pending_results = res.data.pendingResults;
                $scope.approved_results = res.data.approvedResults;
            }
        );
    };

    $scope.viewResults = (results) => {
        $scope.view_course_results = results;
        $scope.route("view_results");
    };
});

app.controller("HODStaffController", function ($scope) {
    $scope.staff_members = [];
    $scope.currentPage = 1;
    $scope.allocation_list = [];
    $scope.deallocation_list = [];
    $scope.display_course_allocations = false;
    $scope.staff_in_view = {};
    $scope.staff_courses = {};

    $scope.normalizeCourseToMainCourse = (course) => {
        if (course.course) {
            return course.course;
        }
        return course;
    };

    $scope.staffInView = (staff) => {
        $scope.allocation_list = [];
        $scope.deallocation_list = [];
        $scope.display_course_allocations = false;
        $scope.staff_in_view = staff;
        $scope.staff_id = staff.id;

        $scope.staff_courses = staff.courses.map((course) =>
            $scope.normalizeCourseToMainCourse(course)
        );

        $scope.popUp("display_staff");
    };

    $scope.init = function () {
        $scope.api(
            "/app/hod/staff/index",
            {},
            (res) => {
                $scope.staff_members = res;
            },
            (err) => {
                $scope.staff_members = null;
            }
        );
    };

    $scope.searchStaff = () => {
        $scope.api(
            "/app/hod/staff/index",
            {
                search: $scope.search_staff,
            },
            (res) => {
                $scope.staff_members = Object.values(res);
            },
            (err) => {
                $scope.staff_members = null;
            }
        );
    };

    $scope.loadMore = () => {
        $scope.api(
            "/app/hod/staff/index",
            {
                page: $scope.currentPage,
            },
            (res) => {
                $scope.staff_members = res.data;
                $scope.currentPage += 1;
            },
            (err) => {
                $scope.staff_members = null;
            }
        );
    };

    $scope.getStaff = async (
        id,
        successCallback = () => {},
        errorCallback = () => {}
    ) => {
        return $scope.api(
            "/app/hod/staff/show",
            {
                id,
            },
            successCallback,
            errorCallback
        );
    };
});

app.controller("HODCourseAllocationController", function ($scope) {
    $scope.allocation_courses = [];
    $scope.deallocation_courses = [];
    $scope.allocatables = [];

    $scope.toggleDisplay = () => {
        $scope.display_course_allocations = !$scope.display_course_allocations;
    };

    $scope.get_course_index_from_list_of_courses = (course_id, courses) => {
        for (var i = 0; i < courses.length; i++) {
            if (courses[i].id === course_id) {
                return i;
            }
        }
        return -1;
    };

    $scope.toggle_courses_for_deallocation = (course) => {
        const index = $scope.get_course_index_from_list_of_courses(
            course.id,
            $scope.deallocation_list
        );

        if (index >= 0) {
            $scope.deallocation_list.splice(index, 1);
        } else {
            $scope.deallocation_list.push(course);
        }
    };

    $scope.toggle_courses_for_allocation = (course) => {
        const index = $scope.get_course_index_from_list_of_courses(
            course.id,
            $scope.allocation_list
        );

        if (index >= 0) {
            $scope.allocation_list.splice(index, 1);
        } else {
            $scope.allocation_list.push(course);
        }
    };

    $scope.allocate_courses = () => {
        const course_ids = $scope.allocation_list.map((course) => course.id);

        $scope.api(
            "/app/hod/course_allocation/allocate",
            {
                courses: course_ids,
                id: $scope.staff_id,
            },
            (res) => {
                $scope.deallocation_list = [];
                // add the course to staff courses
                $scope.staff_courses = $scope.staff_courses.concat(
                    $scope.allocation_list
                );

                // remove the courses from allocation list
                $scope.allocation_list = $scope.allocation_list.filter(
                    (course) => !course_ids.includes(course.id)
                );

                // remove the courses from allocatables list
                $scope.allocatables = $scope.allocatables.filter(
                    (course) => !course_ids.includes(course.id)
                );
                $scope.$apply();
            }
        );
    };

    $scope.deallocate_courses = () => {
        const course_ids = $scope.deallocation_list.map((course) => course.id);
        let course = course_ids.length > 1 ? "courses" : "course";

        $.confirm(
            `Are you sure you want to deallocate the selected ${course}?`,
            {
                accept: () => {
                    $scope.api(
                        "/app/hod/course_allocation/deallocate",
                        {
                            id: $scope.staff_id,
                            courses: course_ids,
                        },
                        (staff) => {
                            //$scope.allocation_list
                            // add the course to allocation list
                            $scope.allocation_list =
                                $scope.allocation_list.concat(
                                    $scope.deallocation_list
                                );

                            // remove the courses from staff courses
                            $scope.staff_courses = $scope.staff_courses.filter(
                                (course) => !course_ids.includes(course.id)
                            );

                            // remove the courses from deallocation list

                            $scope.deallocation_list =
                                $scope.deallocation_list.filter(
                                    (course) => !course_ids.includes(course.id)
                                );

                            $scope.$apply();
                        }
                    );
                },
            }
        );
    };

    $scope.selected_for_deallocation = (course_id) => {
        const index = $scope.get_course_index_from_list_of_courses(
            course_id,
            $scope.deallocation_list
        );
        console.log({ index });
        return index >= 0;
    };
    $scope.selected_for_allocation = (course_id) => {
        const index = $scope.get_course_index_from_list_of_courses(
            course_id,
            $scope.allocation_list
        );
        return index >= 0;
    };

    $scope.getAllocatableCourses = (data) => {
        $scope.api(
            "/app/hod/course_allocation/allocatable/all",
            data,
            (res) => {
                $scope.allocation_list = [];
                $scope.allocatables = res.allocatables.map((course) =>
                    $scope.normalizeCourseToMainCourse(course)
                );
            }
        );
    };
});
