app.directive("controller", function () {
    return {
        restrict: "A",
        scope: {
            controller: "&",
            values: "=",
            form: "=",
        },
        controller: "RootController",
        link: function (scope, element, attr) {
            if (!element.is("button") && !element.is(":submit")) {
                return;
            }
            let form = scope.form
                ? angular.element(scope.form)
                : element.closest("form");

            scope.reset = scope.reset || true;

            let value = element.text();

            let values = scope.values || {};
            let indicator = $("i", element);
            let elementText = $("label", element);

            if (elementText.length > 0) {
                value = elementText.text();
            }

            if (indicator.length === 0) {
                indicator = $("<i>").addClass("fa fa-paper-plane");
                element.prepend(indicator);
            }
            if (elementText.length === 0) {
                elementText = $("<label>").text(value);
                element.empty();
                element.append(indicator, elementText);
            }

            if (!element.hasClass("has-indicators")) {
                element.addClass("has-indicators");
            }

            if (!values || typeof values !== "object") {
                values = {
                    sending: value + "...",
                    sent: "Done",
                    error: "Failed",
                    initial: value,
                };
            }

            const promise = scope.controller.bind(scope);

            const class_pattern = /\bstate-(sending|sent|error|initial)\b/;
            let buttonIcon = $("i.class", element);

            const states = {
                error: {
                    icon: "opacity-50 fa fa-exclamation-triangle",
                    class: "state-error",
                    text: values.error || "Failed",
                },
                initial: {
                    icon:
                        buttonIcon.length > 0
                            ? buttonIcon.attr("class")
                            : "fa fa-paper-plane",
                    class: "state-initial",
                    text: values.initial || value,
                },
                sending: {
                    icon: "btn-spinning",
                    class: "state-sending",
                    text: values.sending || value + "...",
                },
                sent: {
                    icon: "sonar_once fa fa-check-circle",
                    class: "state-sent",
                    text: values.sent || value,
                },
            };

            const setState = (state) => {
                const class_list = element.attr("class") || "";
                const match_class = class_list.match(class_pattern);
                scope.setState(state);

                if (match_class) {
                    element.removeClass(match_class[0]);
                }

                if (states[state]) {
                    indicator.attr("class", states[state].icon);
                    element.addClass(states[state].class);
                    elementText.text(states[state].text);
                }
                scope.currentState = state;

                element.prop(
                    "disabled",
                    ["sending", "error", "sent"].includes(state)
                );
            };

            element.on("click", function (e) {
                e.preventDefault();
                setState("sending");

                let customPromise = async () => {
                    return promise();
                };

                setTimeout(() => {
                    try {
                        let prom = customPromise();

                        prom.then((res) => {
                            setState("sent");
                            if (scope.reset && form.length > 0) {
                                element[0].reset();
                            }
                            if (scope.done) {
                                scope.done.call(scope);
                            }
                        })
                            .catch((err) => {
                                setState("error");
                            })
                            .finally(() => {
                                setTimeout(() => {
                                    setState("initial");
                                }, 2000);
                            });
                    } catch (e) {
                        ENV.log(e);
                        setState("initial");
                    }
                }, 2000);
            });
        },
    };
});

// app.directive("customcheckbox", function () {
//     return {
//         restrict: "A",
//         scope: {
//             check: '@',
//             ngModel: "=",
//         },
//         link: function (scope, element, attr) {
//             // scope.check = "";
//             // scope.id = scope.id || "checkbox";
//             element.replaceWith(angular.element('<b></b>'));

//             console.log(attr);

//             // if (scope.labelClass) {
//             //     scope.check = scope.labelClass
//             //         .split(/\s+/)
//             //         .join(" peer-checked:");
//             // }
//             // const customCheckbox = angular.element(`
//             // <div class="custom-checkbox">
//             //     <div class="text-black inline-flex items-center align-middle">
//             //         <div class="checkbox-area">
//             //             <div class="checkbox-touch"></div>
//             //             <input type="checkbox" id="${scope.id}" tabindex="0">
//             //             <div class="checkbox-ripple"></div>
//             //             <div class="checkbox-bg">
//             //                 <svg focusable="false" viewBox="0 0 24 24" aria-hidden="true" class="mdc-checkbox__checkmark">
//             //                     <path fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59" >
//             //                     </path>
//             //                 </svg>
//             //                 <div class="checkbox-mixedmark"></div>
//             //             </div>
//             //             <div class="focus-indicator group-focus:opacity-100"></div>
//             //         </div>
                   
//             //     </div>
//             // </div>`);

//             // element.replaceWith(customCheckbox);

//             // const checkboxArea = $(".checkbox-area", customCheckboxes);
//             // const checkbox = $("input[type=checkbox]", checkboxArea);
//             // let label = $("label.checkbox-label", checkboxArea);
//             // if (scope.label) {
//             //     if (label.length === 0) {
//             //         label = angular.element(`<label for="${scope.id}" class="checkbox-label" class="${scope.check}"></label>`);
//             //         checkboxArea.append(label);
//             //     }
//             //     label.text(scope.label);
//             // }

//             // checkbox.on('change', function(event) {
//             //     scope.ngChecked = event.target.checked;
//             // })
//         }
//     };
// });
