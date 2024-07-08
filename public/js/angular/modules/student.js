app.controller('StudentController', function($scope) {

    $scope.loadCGPA = () => {
        $scope.method('get')
            .http({
                url: '/app/student/cgpa',
                
            })
    }

    $scope.bootDashboard() {

    }
})