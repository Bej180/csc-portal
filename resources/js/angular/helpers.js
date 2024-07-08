export async function getCSRFToken() {
    const response = await fetch("/api/csrf-end-point");
    const data = await response.json();
    let token = data.csrf_token;
    if (token) {
        console.log("Token", token);
        return token;
    }

    const meta = document.querySelector('meta[name="csrf_token"]');
    if (meta) {
        token = meta.getAttribute("content");
    }

    return token;
}

const is = {
    function: (any) => typeof any === "function",
    assoc: (any) =>
        typeof any === "function" && any !== null && !Array.isArray(any),
    list: (any) => Array.isArray(any),
    number: (any) => typeof any === "number",
    string: (any) => typeof any === "string",
};

const Toast = async (content, options) => {
    if (typeof options !== "object" || options === null) {
        options = {};
    }
    options = {
        timeout: 5000,
        type: "toast",
        position: "top-right",
        callback: () => {},
        ...options,
    };
    let buttons = {};
    let acceptCallback = () => {};

    if (options.action && typeof options.action === 'object') {
        buttons.action = {text:'Okay'};

        if (typeof options.action.text === 'string') {
            buttons.action.text = options.action.text;
        }
        if (typeof options.action.callback === 'function') {
            acceptCallback = options.action.callback;
        }
    }
    buttons.cancel = typeof options.timeout !== 'number' && "×";
    

    const id = uniqueAttr("id");

    swal({
        content: {
            element: "div",
            attributes: {
                id: id,
                innerHTML: content,
            },
        },
        timer: options.timeout,
        buttons: buttons,
        className: "swal-toast",
    }).then((value) => {
        const element = $(`#${id}`);

        if (value) {
            acceptCallback.apply(element);
        }
    });
    const element = $(`#${id}`);
    element.closest(".swal-modal").addClass("toast-" + options.position);

    if (typeof options.callback === 'function') {
        await options.callback(element);
    }
};
window.Toast = Toast;




window.getCSRFToken = getCSRFToken;
