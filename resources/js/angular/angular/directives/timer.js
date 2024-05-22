app.controller('TimeoutController', function($scope, $element) {
  $scope.difference = 0;
  $scope.text = '';
  console.log($element.attr('timer'));

  $scope.timer = (callback) => {
    callback();
  }

alert('timeout');

});

// app.directive('ngTimer', function(){

//   return {
//     restrict: 'A',
//     scope: {
//       ngTimer: '&',
//     },
//     controller: 'TimeoutController',
//     link: function(scope, element) {
//       scope.init = function() {
//        console.log(scope.ngTimer.apply(scope));
//       };
//       scope.init();


//     }
//   }
// });