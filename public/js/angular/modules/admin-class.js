app.controller("AdminClassController", function ($scope) {
    $scope.classes = [];
    $scope.class_name = null;
    $scope.display_class = null;
    $scope.createData = { advisor_id: null };
    $scope.add_advisor = false;


    $scope.toggleAdvisorSelector = () => {
        $scope.add_advisor = !$scope.add_advisor;
    }
  
    $scope.saveCourseAdvisor = (academicClass, staff_id) => {
        
        return api(
            "/app/admin/classes/advisor/add",
            {
                id: academicClass.id,
                staff_id: staff_id,
            },
            (res) => {
                $scope.display_class = res.data
            }
        );
    };
  
    $scope.displayClass = (academic_class) => {
      $scope.display_class = academic_class;
      $scope.popUp('display_class');
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