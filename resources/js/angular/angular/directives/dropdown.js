/**
 * dropdown Directive
 * Directive for creating a dropdown component.
 */

app.directive("dropdown", function () {
  return {
      restrict: "E",
      templateUrl: "../components/dropdown.html",
      required: "options",
      scope: {
          selectedItem: "@",
          options: "=",
          ngChange: "&",
          ngModel: "=",
      },
      link: function (scope, element) {
          let options;
         

          scope.toggleDropdown = function (event) {
              const container = event.target.getBoundingClientRect();
              const width = container.width;
              event.target.style.width = width + "px";

              scope.showDropdown = !scope.showDropdown;
              //scope.adjustDropdownPosition();
          };

          scope.selectOption = function (option) {
              setTimeout(function () {
                  scope.ngModel = option.value;
                  scope.selectedItem = option;

                  scope.showDropdown = false;
                  scope.$apply();

                  if (scope.ngChange) {
                      scope.ngChange.call(scope); // Call optional callback
                  }
              }, 10);
          };

          scope.isSelected = (option) => {
              return scope.selectedItem.value == option.value;
          };

          scope.adjustDropdownPosition = function () {
              if (!scope.showDropdown) return; // Don't adjust if not open

              const trigger = element.find(".dropdown-trigger")[0];
              console.log({ trigger });
              return;
              const optionsList = element.find(".dropdown-menu")[0];
              const containerRect =
                  trigger.parentElement.getBoundingClientRect();
              const listRect = optionsList.getBoundingClientRect();

              // Calculate available space below the trigger
              const availableSpace =
                  containerRect.bottom - trigger.offsetHeight;

              // Check if dropdown exceeds container height
              if (listRect.height > availableSpace) {
                  // Move the dropdown up by the difference
                  optionsList.style.top = `-${
                      listRect.height - availableSpace
                  }px`;
              } else {
                  // Check if dropdown can't fit below without going offscreen (consider offset from top)
                  const offsetTop = trigger.offsetTop + trigger.offsetHeight;
                  if (offsetTop + listRect.height > window.innerHeight) {
                      // Move the dropdown down to fit within the viewport
                      optionsList.style.top = `${
                          containerRect.height - listRect.height
                      }px`;
                  } else {
                      // Reset any previous adjustments
                      optionsList.style.top = null;
                  }
              }
          };

          scope.adjustWidthAndHeight = function () {
              const optionsList = element.find(".dropdown-menu")[0];
              const selectedOption = optionsList.querySelector(".selected");
              if (!selectedOption) return; // No selected option

              const selectedOptionWidth = selectedOption.offsetWidth;
              const padding =
                  parseInt(getComputedStyle(optionsList).paddingLeft, 10) +
                  parseInt(getComputedStyle(optionsList).paddingRight, 10); // Account for padding

              // Set a minimum width to prevent excessive shrinking
              const minWidth = 150; // Adjust minimum width as needed

              // Calculate ideal width based on selected option and add padding
              const idealWidth = Math.max(
                  selectedOptionWidth + padding,
                  minWidth
              );

              // Ensure width doesn't exceed container or viewport
              const containerMaxWidth = containerRect.width;
              optionsList.style.maxWidth =
                  Math.min(idealWidth, containerMaxWidth, window.innerWidth) +
                  "px";
          };

          // Adjust dropdown position on toggle and window resize
          scope.$watch("showDropdown", function () {
              // scope.adjustWidthAndHeight();
              // scope.adjustDropdownPosition();
          });
          //window.addEventListener("resize", scope.adjustDropdownPosition);

          // Cleanup on directive destruction
          /* element.on("$destroy", function () {
              window.removeEventListener(
                  "resize",
                  scope.adjustDropdownPosition
              );
          });*/
      },
  };
});