app.controller("AdminClassController", function ($scope) {
  $scope.classes = [];
  $scope.class_name = null;
  $scope.createData = { advisor_id: null };

  $scope.saveCourseAdvisor = (academicClass, staff_id) => {
      $scope.api(
          "/app/admin/classes/advisor/add",
          {
              id: academicClass.id,
              staff_id: staff_id,
          },
          (res) => console.log(res),
          (err) => console.error(err)
      );
  };

  $scope.loadClasses = function () {
      $scope.api(
          "/app/admin/classes",
          {},
          (res) => {
              $scope.classes = res.classes;
              $scope.initiated = true;
          },
          (err) => {
              $scope.initiated = true;
          }
      );
  };

  /**
   * create Class
   * Creates a new class with the provided details.
   */
  $scope.createClass = () => {
      // Send request to create a new class
      return $scope.api(
          "/app/admin/classes/create",
          $scope.createData,
          (res) => {
              $scope.classes = res.classes;
          }
      );
  };
});