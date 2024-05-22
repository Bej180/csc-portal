import { app } from "../app";

/**
 * StaffController
 * Controller responsible for managing staff-related data and actions.
 * @param {Object} $scope - AngularJS scope object for data binding.
 */
app.controller("StaffController", function ($scope) {
    // Initialize variables
    $scope.show_staff = null; // Selected staff to display

    $scope.courses = []; // List of courses
    $scope.staff = null; // Current staff data
    $scope.staff_id = Location.get("staff_id"); // ID of the staff
    $scope.addStaff = null; // Data for adding a new staff
    $scope.edit = false; // Flag to indicate if in edit mode

    $scope.courses = [];

    $scope.viewCourse = (course_id) => {
        $scope.api(
            "/app/course/show",
            {
                course_id,
            },
            (res) => {
                $scope.popUp("view_course");
                $scope.view_course = res;
            }
        );
    };

    /**
     * showStaff
     * Retrieves and displays the details of a staff.
     * @param {string} staff_id - The ID of the staff to display.
     */
    $scope.showStaff = (staff_id) => {
        api("/staff", { staff_id })
            .then((response) => {
                $scope.show_staff = response;
                $scope.popend("show_staff"); // Popend function (assumed to be defined elsewhere)
                $scope.$apply();
            })
            .catch((error) => console.log(error));
    };

    /**
     * init
     * Initializes the controller by fetching data of the staff with the specified ID.
     */
    $scope.init = () => {
        if ($scope.staff_id) {
            api("/staff", { staff_id: $scope.staff_id })
                .then((response) => {
                    $scope.staff = response;
                    // Extract name parts from staff's name
                    const nameParts = response.user.name.split(" ");
                    $scope.image = "/profilepic/" + response.user.id; // Image URL
                    $scope.firstname = nameParts[0]; // First name
                    $scope.lastname = nameParts.length > 1 ? nameParts[1] : ""; // Last name
                    $scope.middlename =
                        nameParts.length > 2 ? nameParts[2] : ""; // Middle name
                    $scope.staff_id = response.id; // Staff ID
                    $scope.$apply();
                })
                .catch((error) => log(error)); // Assuming 'log' function is defined elsewhere
        }
    };

    /**
     * openEditor
     * Sets the edit mode to true, allowing editing of staff details.
     */
    $scope.openEditor = () => {
        $scope.edit = true;
    };

    /**
     * closeEditor
     * Sets the edit mode to false, exiting the editing mode.
     */
    $scope.closeEditor = () => {
        $scope.edit = false;
    };

    /**
     * back
     * Clears the staff ID and data, and drops the staff ID from the location.
     */
    $scope.back = () => {
        $scope.staff_id = null;
        $scope.staff = null;
        Location.drop("staff_id"); // Assuming 'Location' object with a 'drop' method
    };

    /**
     * openAdder
     * Sets the add mode to true, allowing addition of a new staff.
     */
    $scope.openAdder = () => {
        $scope.add = true;
    };

    /**
     * closeAdder
     * Sets the add mode to false, exiting the adding mode.
     */
    $scope.closeAdder = () => {
        $scope.remove = true;
    };
});

app.controller("StaffLabResultsController", function ($scope) {
    $scope.initializePage = () => {
        $scope.api(
            "/app/staff/lab_scores/index",
            {},
            (res) => {
                $scope.pending_results = res.PENDING;
                $scope.approved_results = res.APPROVED;
            }
        );
    };


    $scope.approveLabScore = (result) => {
        return $scope.api(
            "/app/hod/results/approve",
            {
                results_id: result.reference_id,
            },
            (res) => {
                $scope.pending_results = res.data.PENDING;
                $scope.approved_results = res.data.APPROVED;
            }
        );
    };
});
