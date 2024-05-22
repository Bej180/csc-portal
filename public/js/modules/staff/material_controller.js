app.controller('MaterialController', function($scope) {
  $scope.course = '';
  $scope.material = null;


  $scope.uploadMaterial = () => {
    console.log($scope.course, $scope.material);

  };

  $scope.ShareMaterials = (course) => {
    $scope.popend('upload_materials');
    $scope.course = course;
  };


});