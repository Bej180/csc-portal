/**
 * newSession Directive
 * Directive for managing a new session.
 */
app.directive("newSession", function () {
  return {
      restrict: "E",
      replace: true,
      transclude: true,
      templateUrl: "/components/selectnewsession.html",
      link: function (scope, element) {
          scope.clicked = scope.clicked || false;
          scope.sessions = [];

          scope.handleOnClick = () => {
              scope.clicked = true;

              api("/class/generate_name")
                  .then((res) => {
                      scope.sessions = res;
                      scope.$apply();
                  })
                  .catch((e) => {
                      console.log(e);
                  });
          };

          scope.handleOnChange = () => {
              scope.clicked = false;
          };
      },
  };
});