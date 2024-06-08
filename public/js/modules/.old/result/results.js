app.controller("ResultController", function ($scope) {
    $scope.semester = Location.get("semester");
    $scope.session = Location.get("session");
    $scope.level = Location.get("level");
    $scope.courses = [];
    $scope.class_id = Location.get("class_id");
    $scope.class = null;
    $scope.course = null;
    $scope.sessions = [];

    $scope.selectSemesterAndSuggestCourses = () => {
        if ($scope.semester && $scope.session) {
            api("/enrolledCourses", {
                semester: $scope.semester,
                session: $scope.session,
            }).then((response) => {
                $scope.courses = response;
                console.log(response);
                $scope.$apply();
            });
        }
    };

    $scope.setClass = () => {
        if ($scope.class_id) {
            api("/class", {
                class_id: $scope.class_id,
            })
                .then((res) => {
                    let classes = [];
                    for (
                        let year = res.start_year;
                        year < res.start_year + 5;
                        year++
                    ) {
                        classes.push(`${year}/${year + 1}`);
                    }

                    $scope.sessions = classes;
                    console.log(classes);
                    $scope.$apply();
                })
                .catch((error) => console.error(error));
        }
    };

    $scope.fetchCourse = () => {
        if ($scope.semester && $scope.level) {
            api_get("/courses", {
                semester: $scope.semester,
                level: $scope.level,
            }).then((response) => {
                $scope.courses = response;
                $scope.$apply();
            });
        }
    };
});

app.controller("AddResultController", function ($scope) {
    $scope.course = Location.get("course");
    $scope.session = Location.get("session");
    $scope.semester = Location.get("semester");
    $scope.grades = [];
    $scope.class_id = null;

    $scope.setClass = (event) => {
        console.log(event, $scope.class_id);
    };
    $scope.init = () => {
        // setInterval(() => {
        //     $scope.uploadBtn = $scope.colors[$scope.current];
        //     $scope.current = $scope.current + 1;
        //     if ($scope.current >= $scope.colors.length) {
        //         $scope.current = 0;
        //     }
        // }, 5000);

        setTimeout(() => {
            const dataGrade = $("[ng-data-grade]").length;
            const gradeA = $("[ng-data-grade=A]").length;
            const gradeB = $("[ng-data-grade=B]").length;
            const gradeC = $("[ng-data-grade=C]").length;
            const gradeD = $("[ng-data-grade=D]").length;
            const gradeE = $("[ng-data-grade=E]").length;
            const gradeF = $("[ng-data-grade=F]").length;
            const undecided =
                dataGrade - gradeA - gradeB - gradeC - gradeD - gradeE - gradeF;
            $scope.grades = {};
            $scope.grades["A"] = gradeA;
            $scope.grades["B"] = gradeB;
            $scope.grades["C"] = gradeC;
            $scope.grades["D"] = gradeD;
            $scope.grades["E"] = gradeE;
            $scope.grades["F"] = gradeF;
            $scope.grades["undecided"] = undefined;
        }, 0);
    };

    $scope.updateGrade = (series, grade) => {
        $scope.grades[series] = grade;
    };

    $scope.getGrades = () => {
        if (typeof $scope.grades == "object" && $scope.grades) {
            return $scope.grades;
        }
        // Count occurrences of each grade
        const gradeCounts = $scope.grades.reduce((counts, currentGrade) => {
            counts[currentGrade] = (counts[currentGrade] || 0) + 1;
            return counts;
        }, {});

        // Create newGrade object
        const newGrade = {
            A: 0,
            B: 0,
            C: 0,
            D: 0,
            E: 0,
            F: 0,
        };

        for (const key in gradeCounts) {
            newGrade[key] = gradeCounts[key];
        }

        return newGrade;
    };

    
});


app.controller("ResultSummerController", function ($scope) {
    $scope.success = null;
    $scope.result = {};
    // $scope.test = null;
    // $scope.lab = null;
    // $scope.exam = null;
    // $scope.score = null;
    // $scope.grade = null;

    
   
   

    $scope.updateGrade = function (event, result, course) {
        const has_practical = course.has_practical > 0;
        
        
        if (event && /\D+/.test(event.target.value)) {
            $(event.target).val(event.target.value.replace(/\D+/,''));
        }
        let score = parseInt(result?.test) || 0;
        score += parseInt(result?.lab) || 0;
        score += parseInt(result?.exam) || 0;

        if (score === 0) {
            return '';
        }
   
        let grade = "F";


        switch (true) {
            case score >= 70:
                grade = "A";
                break;
            case score >= 60:
                grade = "B";
                break;
            case score >= 50:
                grade = "C";
                break;
            case score >= 45:
                grade = "D";
                break;
            case score >= 40:
                grade = "E";
                break;
        }
        result.score = score;
        result.grade = grade;
        result.remark = score >= 40 ? "PASSED" : "FAILED";

        if (!result.lab && has_practical) {
            result.grade = "F";
            result.remark = "FAILED";
        }
    };

    $scope.saveResult = (event) => {
        event.preventDefault();

        const parent = event.target.closest("tr");
        const elements = parent.querySelectorAll(
            "#reg_no,#course_id,#level,#semester,#session,#test,#exam,#lab,#score,#grade"
        );
        let data = {};

        elements.forEach((element) => {
            let value;
            if (element.tagName === "INPUT") {
                value = element.value.trim();
            } else {
                value = element.innerText.trim();
            }
            const id = element.getAttribute("id");
            data[id] = value;
        });

        $scope.api("/result/add", data, (response) => {
            $scope.remark = "PENDING";
            $scope.success = !$scope.success;
            $scope.$apply();
            console.log(response);
        });
    };
});
