import { app } from '../myApp.js';

app.controller('ResultController', function($scope) {
  $scope.result = null;
  $scope.level = null;
  $scope.semester = null;
  $scope.course = null;

  


  $scope.loadCourses = () => {
      api('/courses', {
        level: $scope.level,
        semester: $scope.semester
      })
      .then(response => {
        $scope.courses = response;
        $scope.$apply();
      })
      .catch(error =>{})
  }
})