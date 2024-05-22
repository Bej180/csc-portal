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

          scope.denyFunction = () => {
            scope.show = false;
            scope.deny.call(scope);
          }

          scope.close = (event) => {
            if ($(event.target).is('.confirm-backdrop') || $(event.target).is('.confirm-close')) {
              scope.show = false;
            }
          };

          scope.confirmFunction = () => {
            scope.show = false;
            scope.confirm.call(scope);
          }

        },
        templateUrl: "/components/confirm.html",
    };
});
