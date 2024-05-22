/**
 * AdvisorController
 * Controller responsible for handling advisor-related functionalities.
 *
 * @param {Object} $scope AngularJS scope object.
 */
app.controller("AdvisorController", function ($scope) {
    /**
     * makeClassAdvisor
     * Sets the advisor ID and name and opens the select advisor class popup.
     *
     * @param {Object} staff The staff object containing the advisor information.
     */
    $scope.makeClassAdvisor = (staff) => {
        $scope.make_advisor_id = staff.id;
        $scope.name = staff.user.name;
        $scope.popUp("select_advisor_class");
    };

    /**
     * makeStaffAdvicer
     * Makes the selected staff an advisor for the specified session.
     *
     * @param {number} staff_id The ID of the staff to be made an advisor.
     * @param {string} session The session for which the staff is to be made an advisor.
     */
    $scope.makeStaffAdvicer = (staff_id, session) => {
        $scope.setButtonState("add_class", "sending");

        $scope.api(
            "/make-staff-advisor",
            {
                staff_id,
                session,
            },
            (res) => ($scope.show_staff_advisor = [res])
        );
    };
});
