
/**
 * StaffController
 * Controller responsible for managing staff-related data and actions.
 * @param {Object} $scope - AngularJS scope object for data binding.
 */
app.controller("StaffController", function ($scope, $timeout) {
    // Initialize variables
    $scope.staff_members = [];
    $scope.currentPage = 1;
    $scope.allocation_list = [];
    $scope.deallocation_list = [];
    $scope.display_course_allocations = false;
    $scope.staff_in_view = {};
    $scope.staff_courses = {};
    $scope.loaded = false;
    $scope.propertyName = 'name';
    $scope.reverse = true;
    $scope.currentPage = 0;
    $scope.courses_selected = [];
    $scope.remove_class_list = [];

    

    $scope.selected_for_class_removal = (class_id) => {
        return $scope.get_index($scope.remove_class_list, class_id) >= 0;
    }

    $scope.toggle_mark_class = (set) => {
        const index = $scope.get_index($scope.remove_class_list, set.id);

        if (index >= 0) {
            $scope.remove_class_list.splice(index, 1);
        }
        else {
            $scope.remove_class_list.push(set);
        }
    }

    $scope.normalizeCourseToMainCourse = (course) => {
        if (course.course) {
            return course.course;
        }
        return course;
    };

    $scope.makeStaffAdvisor = (
        staff_id,
        session,
        confirmed = null,
        counter = 0
    ) => {
        return $timeout(function () {
            return api(
                "/app/moderator/make_staff_class_advisor",
                {
                    staff_id,
                    session,
                    confirmed,
                },
                (res) => {
                    $scope.staff_in_view.classes = $scope.staff_in_view.classes.concat({name:session});
                },
                (err) => {
                    const confirmed = $scope.Response(err, "confirm");
                    counter++;
                    if (confirmed) {
                        $.confirm(confirmed, {
                            acceptText: 'Continue',
                            accept: () => {
                                return $scope.makeStaffAdvisor(
                                    staff_id,
                                    session,
                                    true,
                                    counter
                                );
                            },
                        });
                    }
                }
            );
        });
    };

    /**
     * staffInView
     *  displays the details of a staff.
     * @param {Object} staff - The staff to display.
     */
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

    $scope.sortStaff = function() {
        $scope.propertyName = $scope.sorting.attr;
        $scope.reverse = $scope.sorting.order === 'DESC';
        
        return;
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
    $scope.reverseSort = () => {
        $scope.reverse = $scope.sorting.order === 'DESC';
    }


    /**
     * init
     * Initializes the controller by fetching data of the staff with the specified ID.
     */
    $scope.loadStaffRecords = function () {
        $scope.api(
            "/app/staff/index",
            {},
            (res) => {
                $scope.staff_members = res;
                $scope.loaded = true;
                $scope.currentPage = 1;
            },
            (err) => {
                $scope.loaded = true;
            }
        );
    };

    $scope.searchStaff = (search) => {
        $scope.loaded = false;
        $scope.api(
            "/app/staff/index",
            {
                search,
            },
            (res) => {
                $scope.loaded = true;
                $scope.staff_members = Object.values(res);
            },
            (err) => {
                $scope.loaded = true;
                $scope.staff_members = null;
            }
        );
    };

    $scope.loadMore = () => {
        $scope.api(
            "/app/staff/index",
            {
                page: $scope.currentPage,
            },
            (res) => {
                $scope.staff_members = $scope.staff_memebers.concat(res.data);
                $scope.currentPage += 1;
            }
        );
    };

    $scope.getStaff = async (
        id,
        successCallback = () => {},
        errorCallback = () => {}
    ) => {
        return $scope.api(
            "/app/staff/show",
            {
                id,
            },
            successCallback,
            errorCallback
        );
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
                $scope.staff_members = [response.data].concat($scope.staff_members);
            }
        );
    };


    $scope.displayCoursesToBeAssigned = (course, designation) => {
        $scope.api("/app/admin/courses", course, (res) => {
            $scope.courses = $scope.courses_selected.concat(res);

            if ( designation === 'technologist') {
                $scope.courses =  $scope.courses.filter(course => course.has_practical == 1);
            } 


        });
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
});

app.controller("StaffCourseAllocationController", function ($scope) {
        $scope.allocation_courses = [];
        $scope.deallocation_courses = [];
        $scope.allocatables = [];

        $scope.toggleDisplay = () => {
            $scope.display_course_allocations =
                !$scope.display_course_allocations;
        };

        

        $scope.toggle_courses_for_deallocation = (course) => {
            const index = $scope.get_index(
                $scope.deallocation_list,
                course.id
            );

            if (index >= 0) {
                $scope.deallocation_list.splice(index, 1);
            } else {
                $scope.deallocation_list.push(course);
            }
        };

        $scope.toggle_courses_for_allocation = (course) => {
            const index = $scope.get_index(
                $scope.allocation_list,
                course.id
            );

            if (index >= 0) {
                $scope.allocation_list.splice(index, 1);
            } else {
                $scope.allocation_list.push(course);
            }
        };

        $scope.allocate_courses = () => {
            const course_ids = $scope.allocation_list.map(
                (course) => course.id
            );

            return api(
                "/app/staff/course_allocation/allocate",
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
            const course_ids = $scope.deallocation_list.map(
                (course) => course.id
            );
            let course = course_ids.length > 1 ? "courses" : "course";

            $.confirm(
                `Are you sure you want to deallocate the selected ${course}?`,
                {
                    accept: () => {
                        return api(
                            "/app/staff/course_allocation/deallocate",
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
                                $scope.staff_courses =
                                    $scope.staff_courses.filter(
                                        (course) =>
                                            !course_ids.includes(course.id)
                                    );

                                // remove the courses from deallocation list

                                $scope.deallocation_list =
                                    $scope.deallocation_list.filter(
                                        (course) =>
                                            !course_ids.includes(course.id)
                                    );

                                $scope.$apply();
                            }
                        );
                    },
                }
            );
        };

        $scope.selected_for_deallocation = (course_id) => {
            const index = $scope.get_index(
                $scope.deallocation_list,
                course_id
            );
            return index >= 0;
        };
        $scope.selected_for_allocation = (course_id) => {
            const index = $scope.get_index(
                $scope.allocation_list,
                course_id
            );
            return index >= 0;
        };

        $scope.getAllocatableCourses = (data) => {
            $scope.api(
                "/app/staff/course_allocation/allocatable/all",
                data,
                (res) => {
                    $scope.allocation_list = [];
                    $scope.allocatables = res.allocatables.map((course) =>
                        $scope.normalizeCourseToMainCourse(course)
                    );
                }
            );
        };
    },
);


