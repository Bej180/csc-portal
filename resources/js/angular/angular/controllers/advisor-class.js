app.controller("AdvisorClassController", function ($scope) {
    $scope.totalGradePoints = 1;
    $scope.totalUnits = 0;
    $scope.displayStudent = (student_id) => {
        $scope.api("/app/student/show", { student_id }, (response) => {
            $scope.show_student = response;
            $scope.popUp("show_student");
        });
    };

    $scope.calculateCGPA = (session) => {
      return Math.round((session.totalGradePoints / session.totalUnits) * 100) / 100;
    }

    $scope.generateTranscript = (reg_no) => {
        return $scope.api(
            "/app/advisor/student/generate_transcript",
            {
                reg_no,
            },
            (res) => {
                $scope.route("transcript");
                $scope.transcriptStudent = res.student;
                $scope.transcriptResults = res.results;

                console.log(res);
            },
            (error) => console.error()
        );
    };

    $scope.getTotalGradePoints = () => {
      return $scope.totalGradePoints;
    }
});

app.controller("CalculateGradeController", function ($scope) {
    $scope.gradePoints = 0;
    $scope.units = 0;

    $scope.getGrade = (score) => {
        switch (true) {
            case score > 69:
                return 5;
            case score > 59:
                return 4;
            case score > 49:
                return 3;
            case score > 44:
                return 2;
            case score > 39:
                return 1;
            default:
                return 0;
        }
    };

    $scope.gradePoint = (result) => {
      const grade = $scope.getGrade(result.score) * result.units;
      console.log({grade, units:$scope.totalUnits})
      $scope.totalUnits += result.units;

        $scope.totalGradePoints += grade;
        return grade;
    };
    $scope.getGradePoints = () => {
      console.log($scope)
        return 55;
    };
});
