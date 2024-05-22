app.directive("menu", function () {
    return {
        restrict: "E",

        scope: {
            change: "&",
            class: "@",
            placeholder: "@",
            drop: "@",
            ngModel: "=",
        },

        controller: "RootController",

        link: function (scope, element, attr) {
            const items = element.find("item");

            scope.placeholder = scope.placeholder || "Select option";

            const customContainer = angular.element(
                `<span class="dropdown-container"><div class="dropdown drop-${
                    scope.drop || "left"
                }"><button type="button" class="dropdown-toggle relative ${
                    scope.class
                } text-sm"><span class="shrink-0 opacity-50 hover:opacity-100 px-2 fa fa-ellipsis-v"></span></button><ul class="dropdown-menu"></ul></div><div class="dd-backdrop"></div></span>`
            );

            const trigger = customContainer.find(".dropdown-toggle");
            const hasModel = element.attr("ng-model");
            const dropdown = customContainer.find(".dropdown");
            const optionsList = dropdown.find(".dropdown-menu");
            const dropdownItem = optionsList.find(".dropdown-item[data-value]");
            const backdrop = customContainer.find(".dd-backdrop");
            const triggerText = trigger.find(".dropdown-toggle-text");

            if (scope.placeholder) {
                customContainer
                    .find(".dropdown-menu")
                    .append(
                        `<li class="dropdown-header">${scope.placeholder}</li>`
                    );
            }

            items.each(function (index, item) {
                const option = $(item);
                const selected = option.attr("selected");
                let value = option.attr("value");
                let text = option.text();

                if (selected) {
                    customContainer.find(".dropdown-toggle-text").text(text);
                }

                let ditem = `<li class="dropdown-item ${
                    !value ? "disabled" : ""
                } ${
                    value && selected ? "selected" : "unselected"
                }" data-value="${value}">${text}</li>`;

                const listItem = angular.element(ditem);
                customContainer.find(".dropdown-menu").append(listItem);
                // scope.click();
            });

            element.replaceWith(customContainer);

            trigger.on("click", function () {
                customContainer.toggleClass("show");

                $(this).attr("aria-expanded", customContainer.hasClass("show"));

                const setCordinates = optionsList.attr("set-cordinates");

                
                if (!setCordinates && customContainer.hasClass("show")) {
                    const cordinates = optionsList[0].getBoundingClientRect();
                    const buttonCordinates = trigger[0].getBoundingClientRect();
                    const gapY = cordinates.top - buttonCordinates.bottom;
                    let gapX = cordinates.left - buttonCordinates.left;
                    let styles = {
                        height: cordinates.height,
                        width: cordinates.width,
                        top: cordinates.top,
                        position: "fixed",
                        left: Math.abs(gapX)+'px'
                    };
                    styles.right = buttonCordinates.x;
                    

                    console.log({buttonCordinates, cordinates, styles});

                    // optionsList.css(styles);

                    optionsList.attr("set-cordinates", `${gapX}:${gapY}`);
                }
            });

            backdrop.on("click", function () {
                customContainer.removeClass("show");
            });

            customContainer
                .find(".dropdown-item[data-value]:not(.disabled)")
                .click(function () {
                    customContainer.removeClass("show");
                    const value = $(this).attr("data-value");

                    customContainer
                        .find(".dropdown-item.selected")
                        .removeClass("selected");

                    if (hasModel && value == scope.ngModel) {
                        scope.ngModel = "";
                        $(this).removeClass("selected");
                    } else if (hasModel) {
                        scope.ngModel = value;
                        $(this).addClass("selected");
                      }
                      
                      scope.$apply();
                      scope.change.call(scope, [scope]);
                });

            angular.element(window).bind("resize", function () {
                $(".dropdown-container.show").each(function () {
                    const gaps = optionsList.attr("set-cordinates");

                    if (optionsList.attr("set-cordinates")) {
                        const [gapX, gapY] = gaps
                            .split(",")
                            .map((item) => parseFloat(item) || 0);
                        const cordinates = trigger[0].getBoundingClientRect();

                        optionsList.css({
                            top: `${gapY + cordinates.top}px`,
                            left: `${gapX + cordinates.left}px`,
                        });
                    }
                });
            });
        },
    };
});
