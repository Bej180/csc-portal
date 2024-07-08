// Student COntrollers
app.controller("StudentCourseRegistrationController", function ($scope) {
    $scope.reg_courses = [];

    $scope.regData = {
        level: Location.get("level", ""),
        session: Location.get("session", ""),
        semester: Location.get("semester", ""),
    };
    $scope.loaded = false;
    $scope.selection = [];
    $scope.enrollments = [];
    
    $scope.registeredCourseDetails = {};
    $scope.borrowingCourses = [];
    $scope.selectedUnits = 0;
    $scope.courses_to_be_registered = [];

    $scope.canBorrowCourses = ({ semester, level }) => {
        if ($scope.selectedUnits >= $scope.maxUnits) {
            return false;
        }
        return !(level == 100 || (level == 400 && semester == "RAIN"));
    };

    $scope.showCourseRegistrationDetails = (enrollment) => {
        $scope.enrollment_details = enrollment;
        $scope.route('enrollment_details');
    };

    
    $scope.viewEnrollmentDetails = (enrollments) => {
        $scope.route("enrollment_details");
        $scope.course_reg = enrollments;
    };

    $scope.loadEnrollments = () => {
        $scope
            .api({
                url: "/app/student/enrollments/index",
                success: function (response, xhr) {
                    
                    $scope.enrollments = Object.values(
                        response.enrollments
                    );
                    $scope.account = response.account;

                    console.log({
                        enrollments_: $scope.enrollments
                    })
                    
                },
            })
            .finally(() => {
                $scope.loaded = true;
                $scope.$apply();
            });
    };

    $scope.displayCourses = () => {
        const level = $scope.regData.level;
        const semester = $scope.regData.semester;
        const session = $scope.regData.session;

        if (level && session && session) {
            return http({
                url: "/app/student/course_registration/courses",
                data: $scope.regData,
                success({ courses, maxUnits, minUnits }) {
                    $scope.reg_courses = courses;
                    $scope.maxUnits = maxUnits;
                    $scope.minUnits = minUnits;
                    $scope.route("reg_courses");
                },
            });
        }
    };

    $scope.gotoIndex = () => {
        $scope.route("index", "Enrollments");
    };

    $scope.displayCourseRegistrationForm = () => {
        $scope.route("register_form", "Register Courses");
    };

    /**
     * @method registerCourse
     *
     */
    $scope.registerCourses = () => {
        let data = $scope.regData;
        data.courses = [];
        // console.log($scope.selection);return;

        $scope.selection.forEach((course) => {
            if (course && course.checked) {
                data.courses.push(course.id);
            }
        });

        return $scope.api(
            "/app/student/courses/enroll",
            data,
            ({ data }) => {
                $scope.enrollments = $scope.enrollments.concat([data]);
                $scope.showCourseRegistrationDetails(data);
                $scope.regData = {};
            }
        );
    };

    $scope.canBorrow = () =>
        $scope.regData.level != 100 &&
        $scope.regData.semester !== "RAIN" &&
        $scope.regData.level !== 400;

    $scope.canRegister = () =>
        $scope.selectedUnits < $scope.minUnits ||
        $scope.selectedUnits > $scope.maxUnits;
    $scope.openBorrowPanel = () => {
        $scope.popUp("open_borror_panel");
    };

    $scope.recalculate_units = () => {
        // const total = $scope.reg_courses.reduce((carry, item) =>
        const selectedCourses = $scope.reg_courses.filter(
            (course) => course.checked === true
        );
        let units = 0;
        for (var i = 0; i < selectedCourses.length; i++) {
            units += selectedCourses[i].units;
        }
        $scope.selectedUnits = units;
    };

    $scope.toggleSelect = (event, course) => {
        const id = course.id;
        const units = parseInt(course.units);
        const sum = units + $scope.selectedUnits;

        const index = $scope.findIndex(
            $scope.reg_courses,
            (item) => item.id === id
        );

        if (id in $scope.selection) {
            // $scope.selectedUnits -= units;
            $scope.selection.splice(id, 1);
            if (index >= 0) {
                $scope.reg_courses[index].checked = false;
            }
        } else {
            if (sum > $scope.maxUnits && event.target.checked) {
                event.preventDefault();
                event.stopPropagation();
                event.target.checked = false;
                toastr.error(
                    "You cannot have more than " +
                        $scope.maxUnits +
                        " units workloads"
                );
            } else if (event.target.checked) {
                // $scope.selectedUnits += units;
                $scope.selection[id] = course;
                $scope.reg_courses[index].checked = true;
            } else {
                $scope.selection.splice(id, 1);

                if (index >= 0) {
                    $scope.reg_courses[index].checked = false;
                }
            }
        }

        $scope.recalculate_units();
    };

    $scope.toggleBorrow = (event, course) => {
        const id = course.id;
        const units = parseInt(course.units);
        const sum = units + $scope.selectedUnits;

        // check if borrowing course has been added to course registration list
        if (id in $scope.selection) {
            $scope.selectedUnits -= units;
            $scope.selection.splice(id, 1);
            $scope.reg_courses = $scope.reg_courses.filter(
                (item) => item.id !== id
            );
        } else {
            if (sum > $scope.maxUnits && event.target.checked) {
                toastr.error(
                    "You cannot have more than " +
                        $scope.maxUnits +
                        " units workloads"
                );
                event.preventDefault();
                event.stopPropagation();
                event.target.checked = false;
                return;
            } else if (event.target.checked) {
                // $scope.selectedUnits += units;
                const currentCourse = { checked: true, ...course };
                $scope.selection[id] = currentCourse;
                $scope.reg_courses.push(currentCourse);
            }
        }
        $scope.recalculate_units();
    };

    $scope.initiate_courses = () => {
        $scope.displayCourses();
    };

    $scope.startBorrowing = () => {
        $scope.borrow_course = true;
    };

    $scope.toggleBorrowing = () => {
        $scope.borrow_course = !$scope.borrow_course;
    };

    $scope.stopBorrowing = () => {
        $scope.borrow_course = false;
    };

    $scope.saveBorrowedCourses = (event) => {
        const button = $(event.target);
        const form = button.closest("form");

        const ids = [];
        for (var i in $scope.borrowedCourses) {
            let input = $("<input>").attr({
                type: "hidden",
                name: "courses[]",
            });
            let value = $scope.borrowedCourses[i].id;
            if (/^\d+$/.test(value)) {
                input.val(value);
                form.append(input);
            }
        }
        $("#course-registeration-prepend input[type=checkbox]:checked").each(
            function () {
                let input = $("<input>").attr({
                    type: "hidden",
                    name: "courses[]",
                });
                input.val($(this).val());
                form.append(input);
            }
        );
        const course_input = $("#courses", form);
        course_input.val(JSON.stringify(ids));
        button.attr("type", "submit");
        form.submit();
    };

    $scope.reload = (units) => {
        $scope.units = units;
    };

    $scope.SearchCourse = (borrowQuery) => {
        $scope.api(
            "/search/courses",
            {
                code: borrowQuery,
                semester: $scope.regData.semester,
            },
            (response) => {
                const res = response.filter(
                    (re) => re.level < $scope.regData.level
                );
                if (res.length === 0) {
                    return toastr.error(
                        "You are not allowed to borrow this course"
                    );
                }
                $scope.borrowingCourses = [
                    ...$scope.borrowingCourses,
                    ...response,
                ];
                $scope.$apply();
            },
            (error) => {
                $scope.courses = $scope.cache("courses");
            }
        );
    };

    $scope.borrow = (event, sum) => {
        const checked = event.target.checked;
        if (sum > $scope.maxUnits && checked) {
            event.target.checked = false;
        }
        const row = event.target.closest("tr");
        //row.remove();

        const id = row.getAttribute("data-id");
        const code = row.getAttribute("data-code");
        const name = row.getAttribute("data-name");
        const units = parseInt(row.getAttribute("data-units")) || 0;

        const course = { id, code, name, units };

        $scope.borrowedCourses = CourseService._toggle(course);
        if (event.target.checked === false) {
            CourseService._removeCourse(id);
        }

        const diff = {};
        let u = 0;

        const selected = [];
        for (const r in $scope.borrowedCourses) {
            u += parseInt($scope.borrowedCourses[r].units);
        }
        $scope.units = u;
        $scope.borrowingUnits = u;
    };
});

