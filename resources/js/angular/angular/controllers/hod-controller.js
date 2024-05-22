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

    $scope.displayStaff = (staff) => {
        console.log({ staff });
        $scope.display_staff = staff;
        $scope.popUp("display_staff");
    };
});

app.controller("HODCourseAllocationController", function ($scope) {
    $scope.allocation_courses = [];
    $scope.deallocation_courses = [];

    $scope.toggleAppendForDeallocation = (course_id) => {
        const index = $scope.deallocation_courses.indexOf(course_id);

        if (index >= 0) {
            $scope.deallocation_courses.splice(index, 1);
        } else {
            $scope.deallocation_courses.push(course_id);
        }
    };

  

    $scope.selectedForDeallocation = (course_id) => {
        const index = $scope.deallocation_courses.indexOf(course_id);
        return index >= 0;
    };
    $scope.selectedForAllocation = (course_id) => {
        const index = $scope.allocation_courses.indexOf(course_id);
        return index >= 0;
    };

    $scope.deallocate = (id) => {
        let course =
            $scope.deallocation_courses.length > 1 ? "courses" : "course";

        $.confirm(
            `Are you sure you want to deallocate the selected ${course}?`,
            {
                accept: () => {
                    $scope.api(
                        "/app/hod/course_allocation/deallocate",
                        {
                            id,
                            courses: $scope.deallocation_courses,
                        },
                        (staff) => {
                            $scope.display_staff.courses = $scope.display_staff.courses.filter(staff => !(staff.id in $scope.deallocation_courses));
                            

                            $scope.$apply();
                        }
                    );
                },
            }
        );
    };

    $scope.allocate = () => {};
});
