app.directive("confirm", function () {
    return {
        restrict: "E",
        transclude: true,
        // replace: true,
        scope: {
            deny: "&",
            confirm: "&",
            show: '=',
            title: '@',
            denyText: '@',
            confirmText: '@',
            title: '@'
        },
        link: function (scope, element, att) {
          scope.denyText = scope.denyText || 'Deny';
          scope.confirmText = scope.confirmText || 'Confirm';
          scope.visible = scope.show || false;

          scope.denyFunction = () => {
            scope.visible = false;
            scope.deny.call(scope);
          }

          scope.close = (event) => {
            
            if ($(event.target).is('.confirm-backdrop') || $(event.target).is('.confirm-close') || $(event.target).is('.confirm-cancel')) {
              scope.visible = false;
            }
          };

          scope.confirmFunction = () => {
            scope.visible = false;
            scope.confirm.call(scope);
          }

          scope.$watch('show', (newValue, oldValue) => {
            
            scope.visible = scope.show;
            // scope.$apply();
        });

        },
        templateUrl: "/components/confirm.html",
    };
});
