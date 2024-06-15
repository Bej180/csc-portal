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
        accepted = obj.accept.bind(passwordInput[0]);
        confirmAccept.prop('disabled', true);

        passwordInput.on('keyup', (e) => {
            confirmAccept.prop('disabled', e.target.value.trim().length === 0)
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

        if (typeof accepted === "function" && accepted && typeof accepted.then === "function") {
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
                        .attr(
                            "class",
                            "opacity-50 fa fa-exclamation-triangle"
                        )
                        .show();
                })
                .finally(() => {
                    setTimeout(() => {
                        
                    }, 2000);
                });
               
        }
        else {
            setTimeout(() => {
                confirmElement.remove();
                spinner.hide();
                $(this).prop("disabled", false);
            }, 5000);
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
