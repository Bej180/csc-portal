/**
 * submit Directive
 * Directive for managing a submit button.
 */
app.directive("submit", function () {
  return {
      restrict: "E",
      replace: true,
      transclude: true,
      scope: {
          value: "@",
          submit: "@",
          class: "@",
          state: "=",
          ngClick: "&",
          onClick: "&",
          values: "=",
      },
      controller: "RootController",
      link: function (scope, element, transclude) {
          scope.btn_class = "";
          scope.display = scope.value || scope.initial || transclude.value;
          let values = {};
          if (typeof scope.values === "object" && scope.values !== null) {
              values = scope.values;
          }

          scope.type =
              typeof scope.ngClick === "undefined" &&
              typeof scope.onClick === "undefined"
                  ? "submit"
                  : "button";

          scope.$watch("state", function (newValue, oldValue) {
              scope.icon = null;
              scope.color = null;

              switch (scope.state) {
                  case 0:
                      scope.icon = "opacity-50 fa fa-exclamation-triangle";
                      scope.display = values.error || scope.value;
                      scope.color = "!bg-red-500";
                      break; // error
                  case 1:
                      scope.display = values.initial || scope.value;
                      break; // initial
                  case 2:
                      scope.icon = "btn-spinning";
                      scope.display = values.sending || scope.value + "...";
                      // scope.ngDisabled = true;
                      break; // sending
                  case 3:
                      scope.icon = "sonar_once fa fa-check-circle";
                      scope.display = values.sent || scope.value;
                      scope.color = "!bg-green-600";
                      break; // sent
              }
          });
      },
      templateUrl: "/components/send.html",
  };
});