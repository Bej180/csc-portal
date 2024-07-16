/**
 * Course Controller
 * Manages the functionality related to courses in the application.
 * Handles loading, updating, and adding courses.
 */
app.controller("AdminCoursesController", function ($scope) {
    // Initialize variables
    $scope.level = "";
    $scope.semester = "";
    $scope.active_id = null;
    $scope.active_course = null;
    $scope.course = null;
    $scope.courses = [];
    $scope.data = {};
    $scope.editData = null;
    $scope.data = { test: "0", lab: "0" };
    $scope.check = false;
    $scope.selected_prerequisite_courses = [];
    $scope.prerequisite_courses = [];
    $scope.allocate_course_to = [];
    $scope.allocate_now = [];
    $scope.currentPage = 1;

    $scope.loadMore = () => {
        $scope.api(
            "/app/admin/courses/index",
            {
                level: $scope.level,
                semester: $scope.semester,
                search: $scope.searchtext,
                page: $scope.currentPage,
            },
            (res) => {
                $scope.courses = $scope.courses.concat(res.data);
                $scope.currentPage++;
            }
        );
    };

    $scope.get_courses = (scope, courses, index) => {
        const course = courses[index];
        const course_id = course.id;

        switch (scope.option) {
            case "delete":
                $scope.api("/app/admin/course/delete", {
                    course_id,
                });
                break;
            case "edit":
                break;
        }
    };

    $scope.showCourse = course => {
        $scope.show_course = course;
        $scope.popUp('show_course');
    }

    $scope.editCourse = course => {
        $scope.edit_course = course;
        $scope.popUp('edit_course');
    }

    $scope.showSelection = (course_id) => {
        const index = $scope.allocate_now.indexOf(course_id);

        if (index > -1) {
            $scope.allocate_now.splice(index, 1);
        } else {
            $scope.allocate_now.push(course_id);
        }
    };

    $scope.makeCourseCordinator = (scope, course_id, index) => {
        if (scope.allocate_course_to) {
            return $scope.api(
                "/app/admin/courses/make_cordinator",
                {
                    course_id: course_id,
                    staff_id: scope.allocate_course_to,
                },
                (res) => {
                    $scope.courses[index].cordinator = res.data;
                }
            );
        }
    };

    /**
     * displayPrequisiteCourses
     * Loads courses based on the selected level and semester.
     */
    $scope.displayPrequisiteCourses = () => {
        $scope.api(
            "/app/admin/courses/prerequisites/index",
            {
                level: $scope.data.level,
                semester: $scope.data.semester,
                prerequisites: $scope.data.prerequisites,
            },
            (res) => {
                $scope.prerequisite_courses = [
                    ...$scope.selected_prerequisite_courses,
                    ...res,
                ];
            }
        );
    };

    $scope.courseIndex = (course_code) => {
        let index = -1;
        for (var i = 0; i < $scope.selected_prerequisite_courses.length; i++) {
            if ($scope.selected_prerequisite_courses[i].code == course_code) {
                index = i;
            }
        }
        return index;
    };

    $scope.toggleSelectPrerequesiteCourse = (course) => {
        let index = $scope.courseIndex(course.code);

        if (index >= 0) {
            $scope.selected_prerequisite_courses.splice(index, 1);
        } else {
            $scope.selected_prerequisite_courses.push(course);
        }
    };

    $scope.enterSearch = (event) => {
        if ($scope.keyCode === 13) {
            $scope.searchForCourse();
        }
    };

    $scope.searchForCourse = (indicator) => {
        $scope.cache("courses", $scope.courses);
        if ($scope.searchtext) {
            $scope.currentPage = 1;
            $scope.api(
                "/app/admin/courses/search",
                {
                    search: $scope.searchtext,
                },
                (res) => {
                    $scope.courses = res.data;
                    $scope.currentPage = 2;
                },
                (error) => {
                    setTimeout(() => {
                        $scope.courses = $scope.cache("courses");
                    }, 5000);
                }
            );
        }
    };

    /**
     * display_course
     * Loads a specific course and displays its details.
     * @param {Event} event - The event triggering the course load.
     */

    $scope.viewCourse = (course) => {
        $scope.active_course = course;
        $scope.popUp("active_course");
    };

    $scope.archiveCourse = (course) => {
        return $scope.api({
            url: "/app/admin/courses/archive",
            data: {
                course_id: course.id,
            },
            loadingText: "Archiving Course",
            confirm: "Are you sure you want to archive " + course.code + "?",
        });
    };
    $scope.display_course = (course) => {
        let course_id = null;
        if (typeof course === "number") {
            course_id = course;
        } else {
            course_id = course.id;
        }
        $scope.api(
            "/course",
            {
                course_id: course_id,
            },
            (response) => {
                $scope.active_course = response;
                $scope.popend("active_course");
                $scope.$apply();
            }
        );
    };

    /**
     * updateCourse
     * Updates the selected course's details.
     */
    $scope.updateCourse = (course) => {
        return $scope.api({
            askPin: true,
            url: "/app/admin/courses/update",
            data: course,
            success: (response) => {
                $scope.editData = response;
            },
            error: error => {
                $scope.registerError(error, (err) => {
                    $scope.edit_course_error = err;
                });
            }
        });
        
    };

    /**
     * init
     * Initializes the course controller.
     * Loads courses based on the provided level and semester.
     */
    $scope.init = () => {
        $scope.courses = [];

        $scope.api({
            url: "/app/admin/courses/index",
            data: {
                level: $scope.level,
                semester: $scope.semester,
                search: $scope.searchtext,
            },
            success: (res) => {
                $scope.courses = res.data;
                $scope.currentPage = 2;
                $scope.$apply();
            },
        });
    };

    /**
     * suggestLevelAndSemester
     * Suggests level and semester based on the course code.
     */

    $scope.suggestLevelAndSemester = () => {
        if (!$scope.data.code) {
            return;
        }
        const match = $scope.data.code.trim().match(/([1-5])[0-9]([1-9])$/);
        if (match) {
            $scope.data.level = parseInt(match[1]) * 100;
            $scope.data.semester =
                parseInt(match[2]) % 2 == 0 ? "RAIN" : "HARMATTAN";
        }
    };

    $scope.updateUnits = () => {
        $scope.data.units = 0;
        $scope.data.units += parseInt($scope.data.lab) || 0;
        $scope.data.units += parseInt($scope.data.test) || 0;
        $scope.data.units += parseInt($scope.data.exam) || 0;
    };

    /**
     * addCourse
     * Adds a new course with the provided details.
     */
    $scope.addCourse = () => {
        return $scope.api(
            "/app/admin/courses/create",
            $scope.data,
            (response) => {
                $scope.courses = [response];
                $scope.display_course(response);
            }
        );
    };

    /**
     * clear
     * Clears the edit data.
     */

    $scope.clear = () => {
        $scope.editData = null;
    };
});
