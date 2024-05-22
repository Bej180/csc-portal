import { at } from "lodash";

app.controller("TechnologistController", function ($scope) {
    $scope.results = {};

    $scope.addResults = () => {
        console.log($scope);
        return $scope.api(
            "/app/staff/courses/students",
            $scope.results,
            (res) => {
                $scope.enrollments = $scope.results;
                $scope.enrollments.data = res;
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

    $scope.uploadLabScores = (data) => {
        data.results = $scope.results.data;
        data.level = $scope.results.data[0].level;
        console.log(data);

        $scope.api(
            "/app/technologist/lab_score/add",
            data,
            (res) => route("index"),
            (err) => {
                console.log({ err });
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

    $scope.initialized = false;

    $scope.initializePage = () => {
        $scope.api(
            "/app/technologist/lab_scores/index",
            {},
            (res) => {
                $scope.results = res;
                $scope.initialized = true;
            },
            (error) => {
                $scope.initialized = true;
            }
        );
    };

    $scope.formatDate = (date) => {
        const dateObj = new Date(date);
        const dd = dateObj.getDate();
        const mm = dateObj.getMonth();
        const yyyy = dateObj.getFullYear();

        return `${dd}/${mm}/${yyyy}`;
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
});

app.controller("TechnologistAttendanceController", function ($scope) {
    $scope.attendance = {};
    $scope.mark_attendance_for = null;
    $scope.attendance_lists = [];

    $scope.initializeAttendance = () => {
        $scope.api(
            "/app/technologist/attendance/index",
            {},
            (res) => {
                $scope.attendance_lists = res.map((list) => {
                  


                  list.total = list.students.length;
                  list.present = list.students.filter((student) => student.status === 'PRESENT').length;
                  list.absent = list.students.filter((student) => student.status === 'ABSENT').length;
                  list.unmarked = list.total - (list.present + list.absent);

                  return list;

                });
                console.log('students',$scope.attendance_lists);
            },
            (err) => console.error(err)
        );
    };

    $scope.markAttendance = (attendance) => {
        $scope.active_attendance_list = attendance;
        $scope.route("mark_attendance");
    };

    $scope.createAttendanceList = () => {
        return $scope.api(
            "/app/technologist/attendance/create",
            $scope.attendance,
            (res) => {
                $scope.markAttendance(res.data);

                $scope.attendance_lists = res.attendance_lists;
                $scope.route("mark_attendance");
            }
        );
    };


    $scope.mark = (event, status, attendance_id, student) => {
      const reg_no = student.reg_no;
      return $scope.api(
        '/app/technologist/attendance/mark',
        {
          attendance_id, reg_no, status
        }, 
        res => {
          $(event.target).prop('disabled', true);
          $(event.target).siblings().prop('disabled', false);
        }
      );
    };


    $scope.attendanceStatus = (students) => {
      const total = students.length;
      const present = students.filter((student) => student.status === 'PRESENT');
      const absent = students.filter((student) => student.status === 'ABSENT');

      return {
        present: present.length,
        total: total,
        absent: absent.length,
        unmarked: total - (this.present + this.absent)
      };
    }


});
