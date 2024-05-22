app.controller("StaffResultsController", function ($scope) {
    $scope.results = {};
    $scope.uploader = { student: [], data: {} };

   
    $scope.addResults = () => {
        return $scope.api(
            "/app/staff/courses/students",
            $scope.results,
            (res) => {
                $scope.enrollments = $scope.results;
                $scope.enrollments.data = res;
                $scope.route('add_result');
            },
            (err) => {
                console.log(err);
                if ($.isPlainObject(err) && "confirm" in err) {
                    $.confirm(err.confirm, {
                        type: "confirm",
                        accept: () => {
                            alert(1);
                        },
                    });
                }
            }
        );
    };

    $scope.ViewCourseResult = ({ course_id, semester, session }) => {
        return $scope.api(
            "/app/staff/course/results",
            {
                course_id,
                semester,
                session,
            },
            (res) => {
                $scope.view_course_results = res;
                $scope.route("course_results");
            },
            (err) => console.error(err)
        );
    };

    $scope.uploadResults = (results, data) => {
        
       
        $scope.api(
            "/app/staff/results/add",
            $scope.processData(results, data),
            (res) => {
                $scope.loadResults();
                $scope.route('index');
            },
            (err) => {
                console.log(err);
                if ($.isPlainObject(err) && "confirm" in err) {
                    $.confirm(err.confirm, {
                        type: "confirm",
                        accept: () => {
                            $scope.uploadResults({ confirmed: true, ...data });
                        },
                    });
                }
            }
        );
  
    };
    $scope.processData = (results, {session, course_id}) => {
        let records = {
            session,
            course_id,
        };
       
        records.students = results.map((d) => {
            return {
                lab: d.results?.lab,
                test: d.results?.test,
                exam: d.results?.exam,
                score: d.results?.score,
                reg_no: d.reg_no,
            };
        });
        return records;
    }

    $scope.saveResultsAsDraft = (results, data) => {

        return $scope.api("/app/staff/results/save_draft", $scope.processData(results, data), res => {
            $scope.loadResults();
        });
    };

    $scope.loadResults = () => {
        return $scope.api("/app/staff/results/index", {}, (res) => {
            $scope.all_results = res;
        });
    };
});
