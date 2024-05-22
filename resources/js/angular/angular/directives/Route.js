app.controller("Router", function ($scope) {
    $scope.active_route = "index";


    $scope.is_active_route = (route) => {
        return $scope.active_route === route;
    };

    $scope.route = (route = "index") => {
        $scope.active_route = route;
    };
});
