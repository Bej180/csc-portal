app.directive("select", function () {
  return {
      restrict: "E",
      priority: 1000,
      scope: {
          ngModel: "=",
          ngChange: "&",
          drop: "@",
          options: "=",
      },
      link: function (scope, element, attrs) {
          const isMultiple = element.attr("multiple");
          if (element[0].tagName !== "SELECT" || element.is(".ignore")) {
              return;
          }
          scope.drop = scope.drop || "down";

          const dropdownTemplate = `
              <span class="dropdown-container">
                  <div class="dropdown">
                      <button type="button" class="dropdown-toggle relative input text-sm">
                          <span class="dropdown-toggle-text">Select Option</span>
                          <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu"></ul>
                  </div>
                  <div class="dd-backdrop"></div>
              </span>
          `;

          const customContainer = angular.element(dropdownTemplate);
          const trigger = customContainer.find(".dropdown-toggle");
          const optionsList = customContainer.find(".dropdown-menu");

          // Populate options
          if (Array.isArray(scope.options)) {
              scope.options.forEach(option => {
                  const listItem = angular.element(`<li class="dropdown-item" data-value="${option}">${option}</li>`);
                  optionsList.append(listItem);
              });
          }

          // Handle click event
          trigger.on("click", function () {
              customContainer.toggleClass("show");
              $(this).attr("aria-expanded", customContainer.hasClass("show"));
          });

          // Handle item selection
          optionsList.on("click", ".dropdown-item", function () {
              const value = $(this).data("value");
              scope.ngModel = isMultiple ? (scope.ngModel || []).concat([value]) : value;
              scope.ngChange();
              scope.$apply();
              customContainer.removeClass("show");
          });

          // Replace original select element with custom container
          element.replaceWith(customContainer);

          // Cleanup on directive destruction
          scope.$on("$destroy", function () {
              customContainer.remove();
          });
      }
  };
});
