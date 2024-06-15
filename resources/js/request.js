import axios, { AxiosError } from "axios";

const session_name = "access_token";

window.csrfToken = document
    .querySelector('meta[name="csrf_token"]')
    .getAttribute("content");
window.bearerToken =
    sessionStorage.getItem(session_name) || localStorage.getItem(session_name);

if (bearerToken) {
    axios.defaults.headers.common["Authorization"] = "Bearer " + bearerToken;
}
axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken;

const getHeaders = (customHeaders) => {
    const session =
        localStorage.getItem(session_name) ||
        sessionStorage.getItem(session_name);

    const headers = {
        "Content-Type": "application/json",
        Accept: "application/json",
        ...(session && { Authorization: `Bearer ${session}` }),
        ...customHeaders,
    };

    return headers;
};

const handleResponse = (response, args) => {
    response = parseResponse(response);

    ENV.log(response);
    $("#isLoading").removeClass("show");

    if (typeof response === "string") {
        throw response;
    }
    if (args.isCacheable) {
        CacheService.put(args.cacheKey, response, 60 * 60 * 24);
    }
    if (args.success) args.success(response);
    if (!args.silent && response.success) toastr.success(response.success);
    if (response.alert)
        $.confirm(response.alert, {
            type: "alert",
            style: "success",
        });
    if (response.redirect)
        setTimeout(() => (window.location.href = response.redirect), 2000);

    return response;
};

const handleError = (err, args) => {
    const errorData = parseResponse(err);

    if (args.error) args.error(errorData, err);
    ENV.error(errorData);

    $("#isLoading").removeClass("show");

    if ("error" in errorData || "errors" in errorData) {
        if (
            "errors" in errorData &&
            Array.isArray(errorData.errors.passkey) &&
            errorData.errors.passkey.includes("validation.password")
        ) {
            toastr.error(
                "Could not verify your password",
                "Password not verified"
            );

            return $.confirm("Enter your password to proceed", {
                type: "password",
                style: "info",
                accept: function () {
                    args.data = appendData(args.data, {
                        passkey: this.value,
                    });

                    return http(args);
                },
            });
        }

        toastr.error(
            errorData.errors
                ? Object.values(errorData.errors)[0]
                : errorData.error
        );
    } else if ("alert" in errorData) {
        return $.confirm(errorData.alert, {
            type: "alert",
            style: "danger",
        });
    }
    if (errorData.redirect) {
        setTimeout(() => (window.location.href = errorData.redirect), 2000);
    } else if (
        errorData instanceof Error &&
        errorData.message === "Timeout") {
        
        if (args.retryLimit > 0 && args.retries < args.retryLimit) {
        args.retries++;
        toastr.error(
            `Retrying ${args.retries}/${args.retryLimit}`,
            "Failed Connection",
            { positionClass: "toast-bottom-right" }
        );

        return setTimeout(() => http(args), args.retryDelay);
    }
    else {
        toastr.error("Timeout",{ positionClass: "toast-bottom-right" });
    }
}

    if (args.throwError) throw errorData;
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

const appendData = (obj, data) => {
    if (obj instanceof FormData) {
        for (var key in data) {
            obj.append(key, data[key]);
        }
    } else if (typeof obj === "object" && obj !== null) {
        obj = { ...obj, ...data };
    } else {
        obj = {};
    }
    return obj;
};

const loadingIndicator = (indicatorText = 'Loading') => {
    let existing, wrapper, loading, dotPulse, indicator;
    existing = $('.isLoading');

    if (existing.length > 0) {
        return existing; 
    }

    loading = $('<div>');
    wrapper = $('<div>');
    dotPulse = $('<div>');
    indicator = $('<div>');

    loading.addClass('isLoading show');
    wrapper.addClass('flex items-center gap-1');
    dotPulse.addClass('dot-pulse');
    indicator.addClass('text-2xl ml-[20px]');
    indicator.text(indicatorText);

    wrapper.append(dotPulse, indicator);
    loading.append(wrapper);
    return loading;
}

const http = async ({
    url,
    data = {},
    success = () => {},
    silent = true,
    type = "post",
    files = null,
    error = () => {},
    contentType = "application/json",
    timeout = 0,
    retryLimit = 0,
    withPassword = false,
    cacheKey = null,
    retries = 0,
    retryDelay = 5000,
    throwError = false,
    headers = {},
}) => {
    if (typeof files === "object" && files !== null) {
        data = appendData(new FormData(), { ...files, ...data });
        contentType = "multipart/form-data";
    }

    success = typeof success === "function" ? success : () => {};
    error = typeof error === "function" ? error : () => {};
    type = ["post", "get", "delete"].includes(type) ? type : "post";

    const isCacheable =
        cacheKey &&
        typeof data === "object" &&
        !(data instanceof FormData) &&
        obj !== null;

    if (isCacheable) {
        if (cacheKey === true) {
            cacheKey = url.replace(/([a-zA-Z0-9])/, "");
            cacheKey += "-" + Object.values(data).join("_");
        }
        if (CacheService.has(cacheKey)) {
            const cached_data = CacheService.get(cacheKey);
            if (typeof success === "function") {
                success(cached_data);
            }
            return cached_data;
        }
    }

    if (url.indexOf("/api") === -1) {
        url = `/api/${url.replace(/^\//, "")}`;
    }
    const args = {
        url,
        data,
        success,
        silent,
        type,
        files,
        error,
        contentType,
        timeout,
        retryLimit,
        retries,
        retryDelay,
        headers,
        cacheKey,
        isCacheable,
        throwError
    };

    headers = getHeaders(headers);

    if (withPassword) {
        toastr.info(
            "Enter your password for verification",
            "Identity Verification"
        );

        return $.confirm("Enter your password to proceed", {
            type: "password",
            style: "info",
            accept: function () {
                args.data = appendData(args.data, {
                    passkey: this.value,
                });

                return http(args);
            },
        });
    }

    let loader = null;
    if (!silent) {
        loader = loadingIndicator();

        // $("#isLoading").addClass("show");
    }

    try {
        const response = await Promise.race([
            axios.post(url, data, headers),
            new Promise((_, reject) =>
                setTimeout(
                    () => timeout && reject(new Error("Timeout")),
                    timeout || 0
                )
            ),
        ]);

        return handleResponse(response, args);
    } catch (err) {
        return handleError(err, args);
    } finally {
        if (loader) {
            loader.remove();
        }
        // $("#isLoading").removeClass("show");
    }
};

window.parseResponse = parseResponse;
window.http = http;
