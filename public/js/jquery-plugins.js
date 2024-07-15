$.fn.findScrollableParent = function () {
    var $element = $(this);
    var $parent = $element.parent();

    // If the element has no parent, or we've reached the window, return null
    if ($parent.length === 0 || $parent.is("body,html")) {
        return $(window);
    }

    // Check if the parent is scrollable
    var overflowY = $parent.css("overflow-y");
    if (overflowY === "scroll" || overflowY === "auto") {
        return $parent;
    }

    // Otherwise, continue checking the parent's parent
    return $parent.findScrollableParent();
};

$.fn.scrollToBottomIfNeeded = function () {
    var $element = $(this);
    var elementRect = $element[0].getBoundingClientRect();
    var win = $element.findScrollableParent();
    var pageYOffset = win.get(0).pageYOffset;
    var absoluteElementTop = elementRect.top + pageYOffset;
    var middle = absoluteElementTop - win.innerHeight() / 2;
    middle += 100;

    if (middle > pageYOffset) {
        win.get(0).scrollTo({ top: middle, behavior: "smooth" });
    }
};

$.addEvent = function (event, selector, callback) {
    $(document).on(event, function (e) {
        const elem = $(e.target);

        if (elem.is(selector)) {
            callback.apply(elem, e);
        }
    });
};

$.confirm = async function (content, obj) {
    if (typeof obj !== "object" || obj == null) {
        obj = {};
    }
    obj = {
        accept: () => {},
        style: "info",
        reject: () => {},
        acceptText: "Accept",
        rejectText: "Reject",
        title: "Confirm",
        type: "confirm",
        ...obj,
    };

    const body = $("body");
    const confirmElement = $(
        `<div class="confirm-backdrop reload-dismiss show confirm-${obj.style}"></div>`
    );
    confirmElement.append(`<div class="confirm-container confirm-center"><div aria-labelledby="confirm-title" aria-describedby="confirm-content" class="confirm-popup confirm-modal"
  tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true"><div class="confirm-icon-wrapper"><span class="confirm-icon hidden"><i class="fa-2x faIcon "></i></span></div><div class="confirm-header"><h2 ng-if="title" class="confirm-title"></h2><button type="button" class="confirm-close confirm-dismiss" aria-label="Close this dialog">×</button></div>
  <div class="confirm-content"></div>
  <div class="confirm-actions"><button type="button" class="confirm-confirm btn btn-primary" aria-label="Accept"><i id="btnIcon" class="btn-spinning" style="display:none"></i><label class="button-label flex items-center justify-center gap-1 font-semibold"></label>
  </button><button type="button" class="confirm-deny" aria-label="Deny" style="display: none;">Deny</button><button type="button" class="confirm-cancel confirm-dismiss btn btn-danger ml-1" aria-label="Cancel">Cancel</button></div></div>`);
    // $('.confirm-backdrop', body).remove();

    body.find(".confirm-backdrop").remove();

    body.append(confirmElement);

    const confirmTitle = $(".confirm-title", confirmElement);
    const confirmDismiss = $(".confirm-dismiss", confirmElement);
    const confirmContent = $(".confirm-content", confirmElement);
    const confirmAccept = $(".confirm-confirm", confirmElement);
    const confirmDeny = $(".confirm-deny", confirmElement);
    const label = $("label", confirmAccept);
    const spinner = $("#btnIcon", confirmAccept);

    const htmlContent = $("<div>").text(content);

    confirmTitle.text(obj.title);
    label.text(obj.acceptText).attr("aria-label", obj.acceptText);
    confirmDeny.text(obj.denyText).attr("aria-label", obj.denyText);
    confirmContent.html(
        htmlContent.text().replace(/\*\*([^\*]+)\*\*/g, "<b>$1</b>")
    );

    let accepted = obj.accept.bind(confirmElement);

    if (obj.type === "alert") {
        $(".confirm-cancel", confirmElement).remove();
        label.text("OK").attr("aria-label", "OK");
        $(".confirm-actions", confirmElement).addClass("justify-end");
    } else if (obj.type === "password") {
        confirmTitle.text("Verify Password");
        label.text("Verify Password").attr("aria-label", "Verify Password");
        confirmContent.empty();
        const passwordWrapper = $("<form>");

        confirmContent.append(passwordWrapper);
        passwordWrapper.append(
            `<div class="font-semibold block text-left">Password</div>`
        );

        const passwordInput = $("<input>").attr({
            type: "password",
            autocomplete: "off",
            placeholder: "Enter Password",
        });
        passwordInput.addClass("input mt-2");
        passwordWrapper.append(passwordInput);
        accepted = obj.accept.bind(null, passwordInput.text());
        confirmAccept.prop("disabled", true);

        passwordInput.on("keyup", (e) => {
            confirmAccept.prop("disabled", e.target.value.trim().length === 0);
        });
    }

    confirmDismiss.on("click", (e) => {
        confirmElement.remove();
    });

    confirmAccept.on("click", (e) => {
        spinner.attr("class", "btn-spinning").show();
        label.hide();
        $(this).prop("disabled", true);
        accepted = (async () => accepted())();

        if (
            typeof accepted === "function" &&
            accepted &&
            typeof accepted.then === "function"
        ) {
            accepted
                .then((res) => {
                    spinner
                        .attr("class", "sonar_once fa fa-check-circle")
                        .show();
                    spinner.hide();
                    $(this).prop("disabled", false);
                    confirmElement.remove();
                })
                .catch((res) => {
                    spinner
                        .attr("class", "opacity-50 fa fa-exclamation-triangle")
                        .show();
                })
                .finally(() => {
                    setTimeout(() => {}, 2000);
                });
        } else {
            setTimeout(
                () => {
                    confirmElement.remove();
                    spinner.hide();
                    $(this).prop("disabled", false);
                },
                obj.type === "alert" ? 0 : 5000
            );
        }
    });
    confirmDeny.on("click", function () {
        obj.deny.call(confirmElement);
        confirmElement.remove();
    });
    // confirmElement.on('click',function (e) {
    //     e.preventDefault();
    //     e.stopPropagation();

    //     confirmElement.remove();
    // });
};

/*
$.fn.pin = function(options) {
    if (typeof options !== 'object' || options === null) {
        options = {};
    }
    options = {paste:false, toggle: false, form: null, timeout: 2000, ...options};
    $(this).each(function(){

        const element = $(this);
        const inputs = element.find("input");
        const insertValue = () => {
            let pinArr = [];
            inputs.each(function(){
                pinArr.push($(this).val().replace(/\D+/, ''));
            });
            element.attr('data-value', pinArr.join(''));
        };

        const initialType = inputs.eq(0).attr('type') || 'text';
        insertValue();


        inputs.eq(0).focus();

        // Add event listeners to all OTP input fields for handling input
        inputs.on("input", function (event) {
            const value = $(this).val();
            if (/\D/.test(value)) {
                $(this).val("");
            } else if (value.length === 1) {
                const index = inputs.index(this);
                insertValue();
                if (index < inputs.length - 1) {
                    // Move focus to the next input field if available
                    $(inputs[index + 1]).focus();
                }
            }
        });

        if (options.toggle) {
            let visibility = element.find('.pin-visibility');
            if (visibility.length === 0) {
                visibility = $('<span>');
                visibility.addClass('pin-visibility');
                element.append(visibility);
            }
            const visibilityObj = {
                text: "<svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='currentColor'><path d='M0 0h24v24H0V0z' fill='none'/><path d='M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z'/></svg>",
                number: "<svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='currentColor'><path d='M0 0h24v24H0V0z' fill='none'/><path d='M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z'/></svg>",
                password: "<svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 -960 960 960' width='24px' fill='currentColor'><path d='m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Zm319 93Zm-151 75Z'/></svg>"
            };

            const type = typeof visibilityObj[initialType] === 'string' ?
                initialType :
                'password';

            visibility.html(visibilityObj[type]);

            

            visibility.on('click', function(){
                const type = inputs.eq(0).attr('type');
                const newType = type === 'password' ? initialType : 'password';
               
                
                inputs.attr('type', newType);
                visibility.html(visibilityObj[newType]);

                if (newType === 'text' && options.timeout) {
                    setTimeout(() => {
                        inputs.attr('type', 'password');
                        visibility.html(visibilityObj.password);

                    }, options.timeout);
                }

            });
            
        }

        // Add event listeners to all OTP input fields for handling keyboard navigation
        inputs.on("keydown", function (event) {
            const value = $(this).val();
            if (event.key === "Backspace" && value.length === 0) {
                const index = inputs.index(this);
                if (index > 0) {
                    // Move focus to the previous input field if available
                    $(inputs[index - 1]).focus();
                }
            }
        });

        // Add event listeners to all OTP input fields for handling keyup events
        inputs.on("keyup", function (event) {
            const value = $(this).val();
            if (value.length > 1 && $(this).is("input:last")) {
                $(this).blur(); // Blur the input field if more than one character is entered and it's the last field
            }
        });

        if (options.paste) {
            element.on("paste", function (event) {
                event.preventDefault();
                const pastedData =
                    event.originalEvent.clipboardData.getData("text");
                if (pastedData.length === inputs.length) {
                    // Paste OTP characters into respective input fields
                    inputs.each(function (index) {
                        $(this).val(pastedData[index]);
                        if (index < inputs.length - 1) {
                            $(this).trigger("input");
                        }
                        if (index === inputs.length - 1) {
                            // form.submit(); // Submit the form after pasting all OTP characters
                        }
                    });
                }
            });
        }
    });
}
*/

const uniqueAttr = (attr, value) => {
    value = value || Math.random().toString(36).substring(2, 9);
    while($(`[${attr}='${value}']`).length > 0) {

        value = `${value}-${Math.random().toString(36).substring(2, 9)}`;
    }
    
    return value;
}

window.uniqueAttr = uniqueAttr;

$.fn.typingEffect = (options) => {
    if (typeof options !== 'object' || options === null) {
        options = {};
    }
    options = {
        retype: true,
        ...options
    };

    $(this).each(function(){
        let text = '';
        let index = 0;
        let isDeleting = false;
        const element = $(this);
        let fullText = element.attr('data-fulltext');
        if (!fullText) {
            fullText = element.text();
            element.attr('data-fulltext', fullText);
        }

        const type = () => {

            if (!isDeleting && index < fullText.length) {
                text += fullText.charAt(index);
                index++;
                element.text(text);
                setTimeout(type, 100); // Adjust typing speed here
            } else if (isDeleting && index > 0) {
                text = text.substring(0, text.length - 1);
                index--;
                element.text(text);
                setTimeout(type, 50); // Adjust deleting speed here
            } else if (index === fullText.length) {
                if (options.retype) {
                    isDeleting = true;
                    setTimeout(type, 500); // Pause before starting to delete
                }
            } else if (index === 0 && isDeleting) {
                isDeleting = false;
                setTimeout(type, 500); // Pause before starting to type again
            }
        }

        // Initial typing effect
        type();
    });

}

$.fn.setAttr = (attr, value) => {
    value = uniqueAttr(attr, value)
    $(this).attr(attr, value);
    return value;
}

$.fn.pin = function (options) {
    // Validate options
    if (typeof options !== "object" || options === null) {
        options = {};
    }
   
    // Set default options and merge with user options
    options = {
        paste: false,
        toggle: false,
        hide: true,
        count: 4,
        form: null,
        timeout: 2000,
        ...options,
    };

    

    

    // Iterate over each element in the jQuery object
    $(this).each(function () {
        const element = $(this);
        const overlay = element.closest('.swal-overlay')
        const inputType = options.hide ? "password" : "number";
        const buttons = $('button', overlay);

        const completeFnc = options.onComplete;

        options.onComplete = function(value) {
            if (typeof completeFnc === 'function') {
                const fncCall = completeFnc(value);
                if (typeof fncCall.finally === 'function') {
    
                    return fncCall
                        .finally(() => {
                            buttons.prop('disabled', false);
                        });
                }
                
            }
            buttons.prop('disabled', false);
        };
        
        
        let inputs = element.find("input");
        if (inputs.length === 0) {
            for (var i = 0; i < count; i++) {
                const input = $("<input>");
                element.append(input);
            }
            inputs = $("input", element);
        }
        inputs.attr('type', inputType);
        
        // Function to insert the value into data-value attribute
        const insertValue = () => {
            let pinArr = [];
            inputs.each(function () {
                pinArr.push($(this).val().replace(/\D+/g, ""));
            });
            const value = pinArr.join("");
            element.attr("data-value", value);
            return value;
        };

        // Insert initial value and focus on the first input
        insertValue();
        inputs.eq(0).focus();
        // Handle input events
        inputs.on("input", function () {
            const value = $(this).val();
            const index = inputs.index(this);
console.log({value, index});
            // Ensure only numeric input and shift focus to the next input
            if (/\D/.test(value)) {
                $(this).val("");
            } else if (value.length === 1 && index < inputs.length - 1) {
                insertValue();
                $(inputs[index + 1]).focus();
            } else {
                const insertedValue = insertValue();
                
                options.onComplete(insertedValue);
            }

            // Trigger custom event
            element.trigger("pin:input", [element.attr("data-value")]);
        });
        
        // Handle toggle visibility
        if (options.hide) {
           
            let visibility = element.find(".pin-visibility");
            if (visibility.length === 0) {
                visibility = $("<span>", {
                    class: "pin-visibility",
                    tabindex: "0",
                    role: "button",
                    "aria-label": "Toggle visibility",
                });
                element.append(visibility);
            }

            const visibilityObj = {
                number: "<svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='currentColor'><path d='M0 0h24v24H0V0z' fill='none'/><path d='M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z'/></svg>",
                password:
                    "<svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 -960 960 960' width='24px' fill='currentColor'><path d='m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Zm319 93Zm-151 75Z'/></svg>",
            };

            visibility.html(visibilityObj.password);

            // Toggle input type on click
            visibility.on("click keydown", function (event) {
                if (
                    event.type === "click" ||
                    (event.type === "keydown" &&
                        (event.key === "Enter" || event.key === " "))
                ) {
                    const type = inputs.eq(0).attr("type");
                    const newType = type === inputType ? "number" : inputType;
                    inputs.attr("type", newType);
                    visibility.html(visibilityObj[newType]);

                    // Revert to password type after a timeout if set
                    if (newType === "text" && options.timeout) {
                        setTimeout(() => {
                            inputs.attr("type", inputType);
                            visibility.html(visibilityObj.password);
                        }, options.timeout);
                    }
                }
            });
        }

        // Handle backspace and navigation
        inputs.on("keydown", function (event) {
            const index = inputs.index(this);
            if (
                event.key === "Backspace" &&
                $(this).val().length === 0 &&
                index > 0
            ) {
                $(inputs[index - 1]).focus();
            }
        });

        // Handle keyup events
        inputs.on("keyup", function (event) {
            if ($(this).val().length > 1 && $(this).is(inputs.last())) {
               
                if (options.onComplete) {
                    const fncCall = options.onComplete.call(element, element.data("value"));

                    buttons.prop('disabled', true);
                    if (fncCall && typeof fncCall.finally === 'function') {
                        fncCall
                            .finally(() => {
                                buttons.prop('disabled', false);
                            })
                    }
                }
                $(this).blur();
            }
        });

        // Handle paste events
        if (options.paste) {
            element.on("paste", function (event) {
                event.preventDefault();
                const pastedData =
                    event.originalEvent.clipboardData.getData("text");
                if (pastedData.length === inputs.length) {
                    inputs.each(function (index) {
                        $(this).val(pastedData[index]).trigger("input");
                        if (index < inputs.length - 1) {
                            $(inputs[index + 1]).focus();
                        }
                    });
                    if (options.onComplete) {
                        options.onComplete.call(element, pastedData);
                    }
                    // Optionally submit the form if provided
                    if (options.form) {
                        $(options.form).submit();
                    }
                }
            });
        }
    });
};
