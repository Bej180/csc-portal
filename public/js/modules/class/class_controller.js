/**
 * ClassController
 * Controller responsible for managing class-related data and actions.
 * @param {Object} $scope - AngularJS scope object for data binding.
 */
app.controller("ClassController", function ($scope) {
  // Initialize variables
  $scope.invitationLink = null; // Invitation link for the class
  $scope.set_id = null; // ID of the class

  /**
   * generateInviteLink
   * Generates an invitation link for the class and displays success message.
   * @param {string} success_message - Success message to display upon link generation.
   */
  $scope.generateInviteLink = (success_message) => {
      api("/generateInviteLink", {
          class_id: $scope.set_id,
      })
          .then((res) => {
              $scope.invitationLink = res.link; // Set invitation link
              $scope.$apply();
              toastr.success(success_message); // Display success message
          })
          .catch((error) => {
              toastr.error("Failed to generate invite link"); // Display error message
          });
  };

  /**
   * withdrawInviteLink
   * Withdraws the invitation link for the class.
   */
  $scope.withdrawInviteLink = () => {
      api("/withdrawInviteLink", {
          class_id: $scope.set_id,
      })
          .then((res) => {
              toastr.info(res.message); // Display information message
              $scope.invitationLink = null; // Clear invitation link
              $scope.$apply();
          })
          .catch((err) => toastr.error(err.message)); // Display error message
  };

  /**
   * initiate
   * Initializes the class controller with the provided set ID and invitation link.
   * @param {string} set_id - ID of the class to initiate.
   * @param {string} link - Invitation link for the class.
   */
  $scope.initiate = (set_id, link) => {
      $scope.set_id = set_id; // Set class ID
      $scope.invitationLink = link; // Set invitation link
  };
});
