import axios from "axios";

export async function api(url, dataCallback, callback, fallback, init) {
    url = url.replace(/^\//, "");
    url = "/api/" + url;
    let data = dataCallback;

    if (typeof dataCallback === "function") {
        // url, callback, fallback init
        init = callback;
        if (typeof callback === "function") {
            init = fallback;
            fallback = callback;
        }
    } else if (typeof dataCallback === "object" && dataCallback !== null) {
        data = dataCallback;
    } else if (typeof callback === "object") {
        init = callback;
    }

    const defaultInit = {
        silent: false,
        headers: {
            "Content-Type": "application/json",
        },
    };

    const session = "88|ZgpAXuUQm2hkQc58L6ess0K15x5Kc9oVIdwuLt1d20722092"; //sessionStorage.getItem("auth_token");

    if (session) {
        defaultInit.headers.Authorization = "Bearer " + session;
    }
    if (typeof init !== "object" || !init) {
        init = {};
    }
    init = { ...defaultInit, ...init };

    return axios
        .post(url, data, init)
        .then(async (response) => {
            if (!init.silent) {
                $("#isLoading").removeClass("show");
            }

            if (!response.ok) {
                throw response.data;
            }
            return response.data;
        })
        .then(async (response) => {
            ENV.log(response);
            $("#isLoading").removeClass("show");

            if (typeof callback === "function") {
                callback(response);
            }

            if (init.silent) {
                return response;
            }

            if ("success" in response) {
                toastr.success(response.success);
            }

            if ("redirect" in response) {
                setTimeout(() => {
                    window.location.href = response.redirect; // Redirect user
                }, 2000);
            }

            return resolve(response);
        })
        .catch(async (err) => {
            if (typeof fallback === "function") {
                let data = {};
                let xhr = err;

                if (typeof err === "string") {
                    data = { message: err };
                } else if (
                    typeof err === "object" &&
                    err !== null &&
                    "response" in err &&
                    "data" in err.response
                ) {
                    data = err.response.data;
                }
                fallback(data, err);
            }
            console.log(err);

            if (init.silent) {
                return err;
            }

            ENV.error(err);

            if (
                typeof err === "object" &&
                err !== null &&
                "response" in err &&
                "data" in err.response
            ) {
                err = err.response.data;
                if (
                    "errors" in err &&
                    typeof err.errors === "object" &&
                    err.errors !== null
                ) {
                    const errors = Object.values(err.errors);
                   
                    toastr.error(errors[0]);
                } else if ("error" in err && err.error.length > 0) {
                    toastr.error(err.error);
                }
                if ("redirect" in err) {
                    setTimeout(() => {
                        window.location.href = err.redirect;
                    }, 2000);
                }
            }

            throw err;
        });
}

api(
    "/testtter",
    { john: "Bright" },
    function (res) {
        console.log(res);
    },
    (err) => {
        console.error(err);
    },
    false
);
