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
        const file = event.target.files[0];
        scope.ngModel = file;
        
        scope.$apply();
        const previewAt = angular.element(scope.previewAt);

        if (previewAt.length > 0) {

          const reader = new FileReader();

          reader.onload = function (e) {
              const dataURL = e.target.result;
              previewAt[0].src = dataURL;
          };

          reader.readAsDataURL(file);
        }
      });


    }
  }
})