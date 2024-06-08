import axios, { AxiosError } from "axios";

const session_name = "auth_token";

window.csrfToken = document
    .querySelector('meta[name="csrf_token"]')
    .getAttribute("content");
window.bearerToken =
    sessionStorage.getItem(session_name) || localStorage.getItem(session_name);

if (bearerToken) {
    axios.defaults.headers.common["Authorization"] = "Bearer " + bearerToken;
}
axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken;

const getHeaders = (session, customHeaders) => {
    const headers = {
        "Content-Type": "application/json",
        Accept: "application/json",
        ...(session && { Authorization: `Bearer ${session}` }),
        ...customHeaders,
    };
    return headers;
};

const handleResponse = (response, success, init) => {
    response = parseResponse(response);

    ENV.error(response);

    if (typeof response === "string") {
        throw response;
    }
    if (success) success(response);
    if (!init.silent && response.success) toastr.success(response.success);
    if (response.alert)
        $.confirm(response.alert, {
            type: "alert",
            style: "success",
        });
    if (response.redirect)
        setTimeout(() => (window.location.href = response.redirect), 2000);

    return response;
};

const handleError = (err, error, init, args) => {
    const errorData = parseResponse(err);

    if (error) error(errorData, err);
    ENV.error(errorData);

    
        if ("error" in errorData || "errors" in errorData) {
            if (
                "errors" in errorData &&
                "password_required" in errorData.errors
            ) {
                $.confirm("Enter your password to proceed", {
                    type: "password",
                    style: "info",
                    accept: function () {
                        args[1]["password_required"] = this.value;
                        return api(...args);
                    },
                });
            }
            toastr.error(
                errorData.errors
                    ? Object.values(errorData.errors)[0]
                    : errorData.error
            );
        } else if ("alert" in errorData) {
            $.confirm(errorData.alert, {
                type: "alert",
                style: "danger",
            });
        } else if ("password_required" in errorData) {
            $.confirm("Enter your password to proceed", {
                type: "password",
                style: "info",
                accept: function () {
                    args[1]["password_required"] = this.value;
                    return api(...args);
                },
            });
        }
        if (errorData.redirect)
            setTimeout(() => (window.location.href = errorData.redirect), 2000);
    

    throw errorData;
};
const parseResponse = (axios) => {
    if (typeof axios.response === "undefined") {
        let data = axios.data || axios;
        if (typeof data == "string") {
            return { response: data };
        }
        return data;
    } else if (
        typeof axios.response.data === "object" &&
        axios.response.data !== null
    ) {
        return axios.response.data;
    } else if (typeof axios.response === "object" && axios.response !== null) {
        return axios.response;
    } else if (axios instanceof AxiosError) {
        return { message: "An error occurred" };
    } else if (typeof axios === "object" && axios !== null) {
        return axios;
    }

    return { message: axios.toString() };
};

const api = async (url, data, success, error, init = {}) => {
    
    if (typeof url === "object" && url !== null) {
        ({ url, data, success, error, ...init } = url);
    } else if (typeof data === "function") {
        [data, success, error, init] = [undefined, data, success, error || {}];
    } else if (typeof success === "object") {
        init = success;
        [success, error] = [undefined, error];
    }

    if (url.indexOf("/api") === -1) {
        url = `/api/${url.replace(/^\//, "")}`;
    }

    if (!init.silent) {
        $("#isLoading").addClass("show");
    }

    const args = [url, data, success, error, init];

    const session =
        localStorage.getItem(session_name) ||
        sessionStorage.getItem(session_name);

    const headers = getHeaders(session, init.headers);
    delete init.headers;
    init = {
        silent: false,
        ...init,
        headers: headers,
    };

    try {
        const response = await Promise.race([
            axios.post(url, data, init),
            new Promise((_, reject) =>
                setTimeout(
                    () => init.timeout && reject(new Error("Request Timeout")),
                    init.timeout || 5000
                )
            ),
        ]);

        return handleResponse(response, success, init);
    } catch (err) {
        return handleError(err, error, init, args);
    }
    finally {
        $("#isLoading").removeClass("show");
    }
};

window.parseResponse = parseResponse;
window.api = api;
