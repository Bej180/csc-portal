app.directive("ngAction", function () {
    return {
        restrict: "A",
        scope: {
            ngAction: "&",
            values: "=",
            reset: "@",
            done: '&',
        },
        controller: "RootController",
        link: function (scope, element, attr) {
            if (!element.is("form")) {
                return;
            }
            scope.reset = scope.reset || true;

            let submit = element.find("[ng-submit]");
            if (submit.length === 0) {
                submit = element.find(":submit");
            }
            if (submit.length === 0) {
                return;
            }

            submit = submit.last();
            submit.removeAttr("ng-submit");
            if (!submit.is("button")) {
                const new_submit = $("<button>");
                Array.from(submit[0].attributes).forEach((attr) => {
                    const name = attr.nodeName;
                    const value = attr.nodeValue;
                    if (name !== "value") {
                        new_submit.attr(name, value);
                    } else {
                        new_submit.text(value);
                    }
                });

                submit.replaceWith(new_submit);
                submit = new_submit;
            }
            const value = submit.text();
            let values = scope.values || {};
            let indicator = $("i.btnIcon", submit);
            let submitText = $("span.btnText", submit);

            if (!submit.hasClass("has-indicators")) {
                indicator = $("<i>").addClass("btnIcon fa fa-paper-plane");
                submitText = $("<span>").addClass("btnText").text(value);
                submit.empty();
                submit.append(indicator, submitText);
                submit.addClass("has-indicators");
            }

            if (!values || typeof values !== "object") {
                values = {
                    sending: value + "...",
                    sent: "Done",
                    error: "Failed",
                    initial: value,
                };
            }

            const promise = scope.ngAction.bind(scope, ["working"]);

            const class_pattern = /\bstate-(sending|sent|error|initial)\b/;
            let buttonIcon = $("i.class", submit);

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
                const class_list = submit.attr("class") || "";
                const match_class = class_list.match(class_pattern);
                scope.setState(state);

                if (match_class) {
                    submit.removeClass(match_class[0]);
                }

                if (states[state]) {
                    indicator.attr("class", states[state].icon);
                    submit.addClass(states[state].class);
                    submitText.text(states[state].text);
                }

                submit.prop(
                    "disabled",
                    ["sending", "error", "sent"].includes(state)
                );
            };

            submit.on("click", function (e) {
                e.preventDefault();

                setState("sending");

                setTimeout(() => {
                    try {
                        promise()
                            .then((res) => {
                                setState("sent");
                                if (scope.reset) {
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

            // element.on('submit', function(e) {
            //     e.preventDefault();
            //     scope.setButtonState('add_class', 'sent');
            //     const fnc = scope.ngAction.bind(scope);
            //     alert(submitButton.length)
            //     fnc()
            //         .then( res => console.log(res) )
            //         .catch( err => console.log(err));
            //     scope.$apply();
            // })
        },
    };
});
