// Single Select
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

            // Extract options and selected values
            const options = element.find("option");
            let placeholder = element.attr("placeholder");
            if (!placeholder) {
                placeholder = isMultiple ? "Select Options" : "Select Option";
            }
            const label = element.prev("label:not(.ignore)");
            const ngLabel = options.first();

            // if (ngLabel.is('[ng-label')) {
            //   placeholder = ngLabel.attr('ng-label');
            //   //ngLabel.remove();
            // }
            // else
            if (label.length > 0) {
                placeholder = label.text();
                label.remove();
            }
            const selections = element.find("option[selected]");
            let selectedValues = []; // Handle initial selected values
            let selectedOptions = [];
            const ngModel = element.attr("ng-model") || "";
            let optionObj = {};
            let selectedValue = "";

            if (selections.length > 0) {
                if (isMultiple) {
                    selectedValues = [];

                    selections.each(function () {
                        const value = $(this).val();
                        const text = $(this).text();
                        optionObj[value] = text;

                        if (selectedValues.indexOf(value) === -1) {
                            selectedValues.push(value);
                        }
                    });
                } else {
                    selectedValue = selections.eq(0).val();
                }
            }

            let display = placeholder;
            if (isMultiple && selectedValues.length > 0) {
                const mapValues = selectedValues.map((value) => {
                    const label = optionObj[value];
                    if (typeof label !== "string") {
                        return "";
                    }
                    return `<span class="chip" style="max-width:40px;text-overflow:ellipse; overflow:hidden" data-value="${value}">${label}</span>`;
                });
                let text = "";

                let cut = mapValues.slice(0, 2);
                let others = mapValues.length - cut.length;
                if (others.length > 0) {
                    cut.push(
                        `<span class="chip" style="max-width:40px;text-overflow:ellipse; overflow:hidden">+${others}</span>`
                    );
                }
                display = cut.join("");
            }

            // Create custom multi-select container
            const customContainer = angular.element(
                `<span class="dropdown-container"><input type="hidden" class="hidden-input" ng-model="${ngModel}"/><div class="dropdown"><button type="button" class="dropdown-toggle relative input text-sm"><span class="dropdown-toggle-text">${display}</span> <span class="caret"></span></button><ul class="dropdown-menu"></ul></div><div class="dd-backdrop"></div></span>`
            );
            // Handle trigger button click to toggle options list visibility
            const trigger = customContainer.find(".dropdown-toggle");
            const dropdown = customContainer.find(".dropdown");
            const optionsList = dropdown.find(".dropdown-menu");
            const dropdownMenu = dropdown.find(".dropdown-menu");
            const input = customContainer.find(".hidden-input");
            const dropdownItem = optionsList.find(".dropdown-item[data-value]");
            const backdrop = customContainer.find(".dd-backdrop");
            const triggerText = trigger.find(".dropdown-toggle-text");

            if (placeholder) {
                customContainer
                    .find(".dropdown-menu")
                    .append(`<li class="dropdown-header">${placeholder}</li>`);
            }

            scope.click = function () {
                // Update selectedOptions on checkbox changes
                if (isMultiple) {
                    $.addEvent(
                        "click",

                        $('input[type="checkbox"]', customContainer),
                        function (e) {
                            const value = $(this).val();

                            const index = scope.ngModel.indexOf(value);
                            const item = $(this).closest(".dropdown-item");

                            if ($(this).prop("checked")) {
                                if (index === -1) {
                                    scope.ngModel.push(value);
                                }
                            } else {
                                if (index !== -1) {
                                    scope.ngModel.splice(index, 1);
                                }
                            }
                            scope.ngModel = scope.ngModel;

                            let text = placeholder;

                            if (scope.ngModel.length > 0) {
                                text = "";
                                let cut = scope.ngModel.slice(0, 2);

                                let others = scope.ngModel.length - cut.length;
                                for (let i = 0; i < cut.length; i++) {
                                    const label = scope.items[cut[i]];

                                    if (typeof label === "string") {
                                        text += `<span class="chip" style="max-width:40px;text-overflow:ellipse; overflow:hidden" data-value="${cut[i]}">${label}</span>`;
                                    }
                                }
                                if (others > 0) {
                                    text += `<span class="chip" style="max-width:40px;text-overflow:ellipse; overflow:hidden">+${others}</span>`;
                                }
                            }
                            triggerText.html(text);
                            trigger.attr(
                                "aria-expanded",
                                customContainer.hasClass("show")
                            );

                            scope.$apply();
                            input.val(scope.ngModel)
                            scope.ngChange.call(scope);
                        }
                    );
                } else {
                    customContainer
                        .find(".dropdown-item[data-value]")
                        .click(function (e) {
                            const value = $(this).data("value");

                            // return;
                            const label = $(this).text();

                            let text;
                            if (value === scope.ngModel) {
                                text = label;
                                // scope.ngModel = "";
                                optionsList
                                    .find(".dropdown-item.selected")
                                    .removeClass("selected");
                                $(this).addClass("selected");
                            } else {
                                scope.ngModel = value;
                                text = placeholder;
                                optionsList
                                    .find(".dropdown-item.selected")
                                    .removeClass("selected");
                                // $(this).addClass("selected");
                            }

                            triggerText.html(text);

                            customContainer.removeClass("show");
                            trigger.attr(
                                "aria-expanded",
                                customContainer.hasClass("show")
                            );
                            scope.adjustDropdownPosition();
                            
                            input.val(scope.ngModel)
                            scope.$apply();
                            scope.ngChange.call(scope);
                        });
                }

                input.val(scope.ngModel);
            };
            const prepareItems = () => {
                let items = {};

                dropdownMenu.empty();

                if (
                    typeof scope.options === "object" &&
                    scope.options !== null
                ) {
                    //options = scope.options;
                } else if (Array.isArray(scope.options)) {
                    const newOption = {};
                    for (var i in scope.options) {
                        newOption[scope.options[i]] = scope.options[i];
                    }
                    items = newOption;
                } else {
                    return;
                }

                console.log("Setting", items);

                const array_keys = Object.keys(scope.items);

                array_keys.forEach((key) => {
                    const value = key;
                    const text = scope.items[key];

                    if (value && text) {
                        let list = `<li class="dropdown-item" data-value="${value}">${text}</li>`;

                        if (isMultiple) {
                            list = `<li class="dropdown-item">
                                  <label class="flex items-center gap-1.5">
                                      <span style="width:20px">
                                        <input type="checkbox" class="checkbox" value="${value}" ng-model="ngModel.indexOf(${value}) !== -1">
                                       </span>
                                       <span>${text}</span>
                                   </label>
                                  </li>`;
                        }

                        const listItem = angular.element(list);
                        dropdownMenu.append(listItem);
                        scope.click();
                    }
                });
            };

            // Build list items with checkboxes and labels
            if (scope.options) {
                prepareItems();
            } else {
                scope.items = {};
                options.each(function (index, option) {
                    const selected = option.getAttribute("selected");
                    let value = option.value;

                    scope.items[value] = option.text;

                    let isSelected = selectedValue === value;
                    if (isMultiple) {
                        isSelected = selectedValues.includes(value); // Check if initially selected
                    } else {
                        if (isSelected) {
                            customContainer
                                .find(".dropdown-toggle-text")
                                .text(option.text);
                        }
                    }

                    if (
                        $(option).is(":selected") &&
                        $(option).attr("selected")
                    ) {
                        isSelected = true;
                    }

                    let item = `<li class="dropdown-item ${
                        !value ? "disabled" : ""
                    } ${
                        value && isSelected ? "selected" : "unselected"
                    }" data-value="${value}">${option.text}</li>`;

                    if (isMultiple) {
                        item = `<li class="dropdown-item ${
                            !value && "disabled"
                        }"><label class="flex items-center gap-1.5"><span style="width:20px"><input type="checkbox" class="checkbox" value="${value}" ng-model="ngModel.indexOf(${value}) !== -1" ${
                            value && isSelected ? "checked" : ""
                        }></span><span>${option.text}</span></label></li>`;
                    }

                    const listItem = angular.element(item);
                    customContainer.find(".dropdown-menu").append(listItem);
                    scope.click();
                });
            }

            scope.ngModel = isMultiple ? selectedValues : selectedValue; // Initialize with initial selected values

            // Replace original select element with the custom container
            element.replaceWith(customContainer);

            dropdown.addClass("drop-" + scope.drop);
            trigger.on("click", function () {
                customContainer.toggleClass("show");

                $(this).attr("aria-expanded", customContainer.hasClass("show"));

                const setCordinates = dropdownMenu.attr("set-cordinates");

                if (!setCordinates && customContainer.hasClass("show")) {
                    const cordinates = dropdownMenu[0].getBoundingClientRect();
                    const buttonCordinates = trigger[0].getBoundingClientRect();
                    const gapY = cordinates.top - buttonCordinates.bottom;
                    const gapX = cordinates.left - buttonCordinates.left;

                    // dropdownMenu.css({
                    //     height: cordinates.height,
                    //     left: cordinates.left,
                    //     width: cordinates.width,
                    //     position: "fixed",
                    //     top: cordinates.top,
                    // });

                    // dropdownMenu.attr("set-cordinates", `${gapX}:${gapY}`);
                }
            });

            optionsList.attr("tabindex", 0);

            if (placeholder) {
                trigger.attr("aria-label", placeholder);
            }
            trigger.attr("aria-disabled", trigger.is(":disabled"));

            backdrop.on("click", function () {
                customContainer.removeClass("show");
            });

            scope.click();

            scope.$watch("ngModel", function (newValue, oldValue) {
                setTimeout(() => {
                    if (newValue) {
                        if (scope.options) {
                            let items = {};
                            if (Array.isArray(scope.options)) {
                                scope.options.forEach((item) => {
                                    items[item] = item;
                                });
                                scope.items = items;
                            }

                            prepareItems();
                        } else {
                            const items =
                                customContainer.find(".dropdown-item");

                            items.each(function (item) {
                                //$(this).toggleClass("selected", $(this).is(`[data-value="${newValue}"]`));

                                if ($(this).is(`[data-value="${newValue}"]`)) {
                                    triggerText.text($(this).text());
                                    $(this).addClass("selected");
                                } else {
                                    // $(this).removeClass("selected");
                                }
                            });
                        }
                    }

                    scope.$apply();
                }, 50);

                if (
                    typeof scope.ngModel === "object" &&
                    scope.ngModel !== null &&
                    scope.ngModel.length > 0
                ) {
                 
                    customContainer
                        .find(".dropdown-item")
                        .removeClass("selected");

                    Object.keys(scope.ngModel).map((key) => {
                        customContainer
                            .find(`.dropdown-item[data-value="${key}"]`)
                            .addClass("selected");
                    });
                } else if (typeof scope.ngModel === "string") {
                    customContainer
                        .find(`.dropdown-item[data-value="${scope.ngModel}"]`)
                        .addClass("selected");
                }
            });

            scope.$watch("options", function () {
                if (scope.options) {
                    setTimeout(() => {
                        prepareItems();
                        scope.$apply();
                    });
                }
            });

            scope.adjustDropdownPosition = function () {
                if (!customContainer.hasClass("show")) return; // Don't adjust if not open

                const containerRect = trigger
                    .parent()[0]
                    .getBoundingClientRect();
                const listRect = optionsList[0].getBoundingClientRect();

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
                scope.adjustDropdownPosition();
            });
            window.addEventListener("resize", scope.adjustDropdownPosition);

            // Cleanup on directive destruction
            element.on("$destroy", function () {
                window.removeEventListener(
                    "resize",
                    scope.adjustDropdownPosition
                );
            });
        },
    };
});
