app.directive("scroller", function () {
  return {
      restrict: "E",
      templateUrl: "../components/scrollable.html",
      transclude: true,
      replace: true,
      scope: {
          top: "@",
      },
      link: function (scope) {
          scope.height = "";
          scope.scrollableHeight = "--scrollable-height:100%;";

          if (scope.top) {
              scope.height = `height: calc(-${scope.top} + 100dvh);`;
              scope.scrollableHeight = `--scrollable-height:calc(-${scope.top} + 100dvh);`;
          }
      },
  };
});