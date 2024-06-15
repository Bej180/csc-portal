app.controller("AdminControlPanelController", function ($scope) {
    $scope.new_session = {};
    $scope.course_registration_status = "OPEN";
    $scope.editors = [];
    $scope.sessions = [];
    $scope.open_semesters = [];

    $scope.createSession = () => {
        return $scope.api(
            "/app/admin/session/store",
            $scope.new_session,
            (res) => {
                $scope.active_session = res.active_session;
                $scope.sessions = res.sessions;
                $scope.new_session = {};
                $scope.course_registration_status =
                    res[
                        (
                            res.active_session.active_semester +
                            "_course_registration_status"
                        ).toLowerCase()
                    ];
            }
        );
    };

    $scope.toggleEditing = (name) => {
        const index = $scope.editors.indexOf(name);

        if (index === -1) {
            $scope.editors.push(name);
        } else {
            $scope.editors.splice(index, 1);
        }
    };

    $scope.isEditing = (name) => {
        return $scope.editors.indexOf(name) >= 0;
    };

    $scope.closeSemester = (event, open_semester, index) => {
        let button = $(event.target).closest("button");

        button.slideUp(function () {
            $scope.api(
                "/app/admin/session/close",
                open_semester,
                (res) => {
                    $scope.open_semesters.splice(index, 1);
                },
                (err) => {
                    button.slideDown();
                }
            );
        });
    };

    $scope.changeCourseRegistrationStatus = (status) => {
        return $scope.api(
            "/api/admin/sessions/",
            {
                status: status,
                ...$scope.reopen,
            },

            (res) => {
                $scope.open_semesters = res.open_semesters;
            }
        );
    };

    $scope.processResponse = (res) => {
        $scope.active_session = res.active_session;
        $scope.open_semester = res.active_session.active_semester;
        $scope.open_semesters = res.open_semesters;
        $scope.sessions = res.sessions;

        // semester status saved as
        // {semester}_course_registration
        const semesterSavedAs = (
            $scope.active_session.active_semester +
            "_course_registration_status"
        ).toLowerCase();

        $scope.course_registration_status =
            $scope.active_session[semesterSavedAs];
    };

    $scope.initConfiguration = () => {
        return $scope.api(
            "/app/admin/session/show",
            {},
            (res) => {
                $scope.processResponse(res);
            }
        );
    };

    $scope.updateActiveSemester = () => {
        return $scope.api(
            "/app/admin/session/update",
            {
                id: $scope.active_session.id,
                active_semester: $scope.active_session.active_semester,
            },
            (res) => $scope.processResponse(res)
        );
    };

    $scope.saveCourseRegistrationState = (obj, success, error) => {
        return $scope.api(
            "/app/admin/session/course_registration_status/update",
            obj,
            success,
            error
        );
    };

    $scope.reOpenCourseRegistration = () => {
        let data = $scope.reopen;
        data.status = "OPEN";

        return $scope.saveCourseRegistrationState(data, (res) => {
            
            $scope.open_semesters = $scope.open_semesters.concat(res.semester);
        });
    };

    $scope.updateCourseRegistrationStatus = () => {
        return $scope.saveCourseRegistrationState(
            {
                status: $scope.course_registration_status,
                id: $scope.active_session.id,
                semester: $scope.active_session.active_semester,
            },
            (res) => {
                console.log(res);
            },
            (err) => console.log(err)
        );
    };
});
