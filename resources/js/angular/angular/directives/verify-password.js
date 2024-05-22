app.directive('verifyPasswords', function() {
  return {
    restrict: 'A',
    scope: {
      ngModel: '='
    },
    link: function(scope, element, attr) {
      scope.visible=false;
      scope.displayForm = false;
      scope.text = element.textContent;


      scope.toggleVisibility = () => {
        scope.visible=!scope.visible;
      }
      
      element.on('click', (event) => {
        scope.displayForm = true;
        scope.$apply();
      })
      
      scope.verifyPassword = (password) => {
        scope.ngModel = password;

        scope.api(
          '/auth/verify_password',
          {
            password: password
          }
        );
        scope.$apply();
      }
    },
    templateUrl: '/components/verify-password.html',
  }
})