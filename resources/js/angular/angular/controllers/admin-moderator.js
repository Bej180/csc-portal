app.controller("AdminModeratorsController", function ($scope) {
  $scope.new_hod = null;

  $scope.makeHOD = (staff) => {
      return $scope.api(
          "/app/admin/moderators/hod/makeHOD",
          {
              staff,
          },
          (res) => ($scope.current_hod = res.hod)
      );
  };

  $scope.addDean = (new_dean) => {
      return $scope.api("/app/admin/moderators/dean/add", new_dean, (res) => {
          $scope.current_dean = res.dean;
          $scope.collapsed = true;
      });
  };

  $scope.loadModeratorDashboard = () => {
      $scope.api(
          "/app/admin/moderators/index",
          {},
          (response) => {
              $scope.current_hod = response.hod;
              $scope.current_dean = response.dean;
          },
          (err) => console.error(err)
      );
  };
});