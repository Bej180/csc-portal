/**
 * submit Directive
 * Directive for managing a submit button.
 */
app.directive("card", function () {
  return {
      restrict: "E",
      required: "title",
      // replace: true,
      transclude: true,
      scope: {
          title: "@",
      },
      // controller: "CardController",
      link: function (scope, element) {
          scope.toggleWindow = () => {
              scope.minimize = !scope.minimize;
          };

          scope.closeWindow = () => {
              scope.close = true;
          };
      },
      templateUrl: "/components/card.html",
  };
});