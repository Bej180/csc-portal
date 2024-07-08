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
