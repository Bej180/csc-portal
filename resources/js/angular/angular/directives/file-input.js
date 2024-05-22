app.directive('input', function() {
  return {
    restrict: 'E',
    scope: {
      previewAt: '@',
      ngModel: '='
    },
    link: function(scope, element, attr) {
      //preview-at="#profile-pic-container"
      if (!element.is(':file')) {
        return;
      }
      const preview = angular.element(scope.previewAt);

      if (preview.length === 0) {
        return;
      }

      element.on('change', function(event) {
        scope.ngModel = event.target.files[0];
        scope.$apply();
      });


    }
  }
})