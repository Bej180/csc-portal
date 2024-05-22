/**
 * toggle Directive
 * Directive for creating a toggle component.
 */

app.directive("toggle", function () {
  return {
      restrict: "E",
      // require: 'options',
      scope: {
          options: "=",
          ngModel: "=",
          ngChange: "&",
          class: "@",
      },
      link: function (scope, element, attr) {
          let options = scope.options;
          let icons = {};

          

          const trigger = angular.element(
              `<button data-ui="shuffle" type="button" class="flex items-center gap-1" active="${
                  scope.ngModel
              }"><i class="${
                  !scope.ngModel ? "btn-spinning" : (scope.options[scope.ngModel]||'')
              }"></i><label>${scope.ngModel || "Loading"}</label></button>`
          );
          const triggerText = trigger.find("label");
          const triggerIcon = trigger.find("i");

          if (scope.class) {
              trigger.addClass(scope.class);
          }

          element.replaceWith(trigger);

          let currentOptionIndex = 0;
          if (typeof scope.options === "object" && scope.options !== null) {
              options = Object.keys(scope.options);
              icons = Object.values(scope.options);
          }

          if (options.indexOf(scope.ngModel) >= 0) {
              options = Object.keys(scope.options);
              currentOptionIndex = options.indexOf(scope.ngModel);
          }

          if (currentOptionIndex >= options.length) {
              currentOptionIndex = 0;
          }

          trigger.on("click", function () {
              let currentOptionIndex = 0;
              if (
                  scope.ngModel &&
                  typeof scope.options === "object" &&
                  scope.options !== null &&
                  scope.ngModel in scope.options
              ) {
                  options = Object.keys(scope.options);
                  currentOptionIndex = options.indexOf(scope.ngModel);
              } else if (
                  Array.isArray(scope.options) &&
                  options.indexOf(scope.ngModel) >= 0
              ) {
                  options = Object.keys(scope.options);
                  currentOptionIndex = options.indexOf(scope.ngModel);
              }

              if (currentOptionIndex >= options.length) {
                  currentOptionIndex = 0;
              }
              currentOptionIndex = (currentOptionIndex + 1) % options.length;
              scope.ngModel = options[currentOptionIndex];

              scope.$apply();
              scope.ngChange.call(scope);
              // return currentOptionIndex;
          });

          scope.$watch("ngModel", function (newValue, oldValue) {
              if (newValue !== oldValue) {
                  console.log(icons, scope.ngModel);
                  if (scope.ngModel in scope.options) {
                      triggerIcon.attr("class", scope.options[scope.ngModel]);
                  }
                  triggerText.text(scope.ngModel);
              } else if (!scope.ngModel) {
                  const first = options[0];
                  if (first in scope.options) {
                      triggerIcon.attr("class", scope.options[first]);
                  }

                  triggerText.text(first);
              }
          });
      },
  };
});