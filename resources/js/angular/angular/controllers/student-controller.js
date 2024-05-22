// Student COntrollers
app.controller("StudentCourseRegistrationController", function ($scope) {
    $scope.reg_courses = [];
    $scope.regData = {
        level: Location.get("level", ""),
        session: Location.get("session", ""),
        semester: Location.get("semester", ""),
    };
    $scope.selection = [];
    $scope.registeredCourseDetails = {};
    $scope.borrowingCourses = [];

    $scope.viewCourseRegistrationDetails = (level, semester, session) => {
        $scope.api(
            "/app/student/course_registration_details",
            { level, semester, session },
            (res) => {
                console.log(res);
                $scope.route("course_registration_details");
                $scope.course_reg = res;
            }
        );
    };

    $scope.loadEnrollments = () => {
        $scope.api("/app/student/enrollments/index", {}, (res) => {
            $scope.enrolled = res;
            $scope.route("index");
        });
    };

    $scope.displayCourses = () => {
        const level = $scope.regData.level;
        const semester = $scope.regData.semester;
        const session = $scope.regData.session;

        if (level && session && session) {
            Location.set({
                level: $scope.regData.level,
                semester: $scope.regData.semester,
                session: $scope.regData.session,
            });

            return $scope.api(
                "/app/student/course_registration/courses",
                $scope.regData,
                (response) => {
                    $scope.reg_courses = response;
                    $scope.route("reg_courses");
                }
            );
        }
    };

    /**
     * @method registerCourse
     *
     */
    $scope.registerCourses = () => {
        let data = $scope.regData;
        data.courses = [];

        $scope.selection.forEach((course) => {
            if (course.checked) {
                data.courses.push(course.id);
            }
        });
        
        return $scope.api(
            "/app/student/courses/register",
            data,
            ({ level, semester, session }) => {
                $scope.loadEnrollments();
                $scope.viewCourseRegistrationDetails(level, semester, session);
                $scope.regData = {};
            },
            (err) => console.error(err)
        );
    };

    $scope.openBorrorPanel = () => {
        $scope.popUp("open_borror_panel");
    };

    $scope.reloadUnits = () => {
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
        console.log($scope.selection);

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
            } else {
                // $scope.selectedUnits += units;
                $scope.selection[id] = course;
                $scope.reg_courses[index].checked = true;
            }
        }

        $scope.reloadUnits();
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
            } else {
                // $scope.selectedUnits += units;
                const currentCourse = { checked: true, ...course };
                $scope.selection[id] = currentCourse;
                $scope.reg_courses.push(currentCourse);
            }
        }
        $scope.reloadUnits();
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

    $scope.SearchCourse = () => {
        $scope.api(
            "/search/courses",
            {
                code: $scope.borrowQuery,
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

app.controller("StudentResultsController", function ($scope) {
    $scope.results = [];
    $scope.awaitingResults = [];
    $scope.unsettledResults = [];
    $scope.totalEnrollments = 0;
    $scope.cgpa = 0.0;

    // {results: Array(1),
    $scope.totalUnits = 0;
    $scope.totalGradePoints = 0;

    $scope.calculateGPA = (totalGradePoints, totalUnits) => {
        const gpa = totalGradePoints / totalUnits;
        return gpa.toFixed(2);
    };

    $scope.getColor = (session_i, semester_i) => {
        const colors = [
            ["#abc9fb", "#f7b0d3"],
            ["#9ae0d9", "#fcc39b"],
            ["#dab6fc", "$ffaca7"],
            ["#98e1c9", "#f6de95"],
            ["#a0e6ba", "#94e0ed"],
            ["#f7b0d3", "#bcbdf9"],
        ];
        if (typeof colors[session_i][semester_i] !== "string") {
            return colors[0][0];
        }
        return colors[session_i][semester_i];
    };

    $scope.init = () => {
        $scope.api("/app/student/results/index", {}, (res) => {
            //$scope.results = res.results;
            $scope.prepareResults(res.results);
            $scope.awaitingResults = Object.values(res.awaitingResults);
            $scope.unsettledResults = res.unsettledResults;
            $scope.totalEnrollments = res.totalEnrollments;
        });
    };

    $scope.prepareResults = (data) => {
        for (const session in data) {
            const session_results = data[session];

            for (const semester in session_results) {
                const semester_results = session_results[semester];
                if (["RAIN", "HARMATTAN"].includes(semester)) {
                    $scope.results.push({
                        session: session,
                        semester: semester,
                        carryover: semester_results.results.filter(
                            (result) => result.remark === "FAILED"
                        ),
                        results: semester_results.results,
                        gpa: (
                            semester_results.totalGradePoints /
                            semester_results.totalUnits
                        ).toFixed(2),
                    });
                } else {
                    $scope.totalUnits += semester_results.totalUnits;
                    $scope.totalGradePoints +=
                        semester_results.totalGradePoints;
                }
                // $scope.gpa = (semester_results.totalGradePoints / semester_results.totalUnits).toFixed(2);
            }
        }
        let cgpa = $scope.totalGradePoints / $scope.totalUnits;
        cgpa = cgpa.toFixed(2);
        $scope.cgpa = isNaN(cgpa) ? 0.0 : cgpa;
    };

    $scope.displayResults = (result) => {
        $scope.display_results = result;
        $scope.route("display_results");
        console.log({ result });
    };
});
