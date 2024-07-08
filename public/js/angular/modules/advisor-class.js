import '../services/students.js';

app.controller("AdvisorClassController", function ($scope, StudentService) {
    $scope.totalGradePoints = 1;
    $scope.totalUnits = 0;
    $scope.classes = [];
    $scope.displayStudent = (student) => {
        $scope.show_student = student;
        $scope.popUp("show_student");
        return;

        $scope.api("/app/student/show", { student_id }, (response) => {
            $scope.show_student = response;
            $scope.popUp("show_student");
        });
    };

    $scope.addStudents = () => {
        $scope.popUp('add_students');
    }
    $scope.createStudentAccount = (data) => {
        data.set_id = $scope.active_class.id;

        return StudentService.create(data, res => {

            $scope.classes = $scope.classes.map(set => {
                if (set.id === $scope.active_class.id) {
                    set.students = [res.data].concat(set.students);
                }
            });
            $scope.active_class.students =  [res.data].concat($scope.active_class.students);
        });
    }
    /**
     * generateInviteLink
     * Generates an invitation link for students to join class .
     */
    $scope.generateInviteLink = (type) => {
        return $scope.api(
            "/app/class/generateInviteLink",
            {
                class_id: $scope.active_class.id,
                type: type,
            },
            (res) => {
                $scope.invitationLink = res.link;
            }
        );
    };

    /**
     * withdrawInviteLink
     * Withdraws the invitation link for the class.
     */
    $scope.withdrawInviteLink = () => {
        return $scope.api(
            "/app/class/withdrawInviteLink",
            {
                class_id: $scope.active_class.id,
            },
            (res) => {
                $scope.invitationLink = null;
            }
        );
    };

    $scope.calculateCGPA = (session) => {
        return (
            Math.round((session.totalGradePoints / session.totalUnits) * 100) /
            100
        );
    };

    $scope.loadClassOnPanel = (set) => {
        $scope.active_class = set;
    };

    $scope.isActivePanel = (set) => {
        return $scope.active_class.id === set.id;
    };

    $scope.initPage = () => {
        $scope.api(
            "/app/advisor/classes",
            {},
            (res) => {
                $scope.classes = res.classes;
                $scope.active_class = res.active_class;
                $scope.invitationLink = res.invitationLink;
            }
        );
    };

    $scope.generateTranscript = ({reg_no}) => {
        return $scope.api({
            url: "/app/advisor/student/generate_transcript",
            loadingText: 'Generating Transcript',
            data: {
                reg_no,
            },
            success: (res) => {
                $scope.route("transcript");
                $scope.transcriptStudent = res.student;
                $scope.transcriptResults = res.results;
            }
        });
    };

    $scope.getTotalGradePoints = () => {
        return $scope.totalGradePoints;
    };
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
        console.log({ grade, units: $scope.totalUnits });
        $scope.totalUnits += result.units;

        $scope.totalGradePoints += grade;
        return grade;
    };
    $scope.getGradePoints = () => {
        console.log($scope);
        return 55;
    };
});
