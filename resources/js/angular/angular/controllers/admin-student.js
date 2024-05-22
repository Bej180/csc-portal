/**
 * StudentController
 * Controller responsible for managing student-related data and actions.
 * @param {Object} $scope - AngularJS scope object for data binding.
 */
app.controller("AdminStudentController", function ($scope) {
    // Initialize variables
    $scope.show_student = null; // Selected student to display
    $scope.addStudent = null; // Data for adding a new student
    $scope.academicClass = null; // Selected academic class
    $scope.limit = "10"; // Limit for student records
    $scope.student = {}; // Student data object
    $scope.no_student = false;
    $scope.students = [];
    $scope.searchinput = null;
    $scope.currentPage = 1;
    $scope.sorting = {
        attr: 'cgpa',
        order: 'ASC'
    };
    /**
     * setClass
     * Sets the selected academic class.
     * @param {Object} set - Academic class object to set.
     */
    $scope.setClass = (set) => {
        $scope.academicClass = set;
    };

    /**
     * showStudent
     * Retrieves and displays the details of a student.
     * @param {string} student_id - The ID of the student to display.
     */
    $scope.showStudent = (student_id) => {
        $scope.api("/student", { student_id }, (response) => {
            $scope.show_student = response;
            $scope.popend("show_student");
        });
    };

    /**
     * createStudentAccount
     * Creates a new student account with the provided data and sends a verification email.
     * @param {Object} academicClass - Academic class object associated with the student.
     */
    $scope.createStudentAccount = (academicClass) => {
        return $scope.api(
            "/app/admin/student/create",
            $scope.student
            // (res) => ($scope.students = res.data)
        );
    };

    $scope.boot = () => {
        $scope.api(
            "/app/students/index",
            {},
            (res) => {
                $scope.students = res.data;
            },
            (err) => console.error(err)
        );
    };

    $scope.more = () => {
        $scope.api(
            "/app/students/index",
            {
                search: $scope.searchinput,
                page: $scope.page,
            },
            (res) => {
                $scope.students = $scope.students.concate(res.res);
                $scope.currentPage += 1;
            }
        );
    };

    
    $scope.searchStudent = (search) => {
    
        $scope.searchinput = search;
        $scope.api(
            "/app/students/index",
            {
                search: search,
                page: $scope.page,
            },
            (res) => {
                $scope.students = res.data;
            }
        );
    };

    $scope.ResetPassword = (account, use) => {
       
        return $scope.api(
            '/admin/user/reset_password',
            {
                new_password: use,
                id: account.id,
            }
        );
    }
    $scope.sortStudent = () => {
        if ($scope.sorting.attr) {
            $scope.api(
                '/app/students/index',
                {
                    search: $scope.searchinput,
                    page:$scope.page,
                    sort: [$scope.sorting.attr, $scope.sorting.order]
                },
                res => {
                    $scope.students = res.data;
                }
            )
        }
    }


});
