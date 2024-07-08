import axios, { AxiosError } from "axios";

export default class Http {
    constructor() {
        this._session_name = "access_token";
        this._debug = true;
        this._throwError = false;
        this._quiet = false;
        this._silence = this._quiet;
        this._bearerToken = null;
        this._csrfToken = null;
        this._method = null;
        this.event = null;
        this._queue = {}
    }

    /**
     * Set the event handler.
     * @param {Function} event - The event handler function.
     */


    setEvent(event) {
        this.event = event;
    }

    /**
     * Load the CSRF token and bearer token.
     */

    loadToken() {
        if (this._csrfToken === null) {
            this._bearerToken =
                sessionStorage.getItem(this._session_name) ||
                localStorage.getItem(this._session_name);

            fetch("/api/csrf-end-point", {
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    this._csrfToken = data.token;
                });
        }
    }


    /**
     * Enable or disable silent mode.
     * @param {boolean} [silent=true] - Whether to enable silent mode.
     */
    silent(silent = true) {
        this._quiet = silent;
    }


    /**
     * Enable or disable error throwing.
     * @param {boolean} [throwErr=true] - Whether to enable error throwing.
     */
    throwError(throwErr = true) {
        this._throwError = throwErr;
    }


    /**
     * Parse and merge default and provided options.
     * @param {Object} options - The options provided by the user.
     * @returns {Object} - The merged options.
     */

    parseArgs(options) {
        const opt = {
            data: {},
            success: () => {},
            quiet: this._quiet,
            trigger: null,
            type: "post",
            files: null,
            error: () => {},
            contentType: "application/json",
            timeout: 30000,
            retryLimit: 3,
            loadingText: "Loading",
            askPassword: false,
            cacheKey: null,
            retries: 0,
            retryDelay: 10000,
            alertTimer: 6000,
            throwError: this._throwError,
            headers: {},
            debug: this._debug,
            ...options,
        };
        this.throwError(false);
        opt.headers["Content-Type"] = opt.contentType;

        return opt;
    }


    /**
     * Set headers for the request.
     * @param {Object} [customHeaders={}] - Custom headers to be added to the request.
     * @returns {Object} - The complete headers for the request.
     */
    setHeaders(customHeaders) {
        const session =
            localStorage.getItem(this._session_name) ||
            sessionStorage.getItem(this._session_name);

        const headers = {
            "Content-Type": "application/json",
            Accept: "application/json",
            ...(this._bearerToken && {
                Authorization: `Bearer ${this._bearerToken}`,
            }),
            ...(customHeaders && customHeaders),
        };

        if (this._csrfToken) {
            headers["X-CSRF-TOKEN"] = this._csrfToken;
        }

        return headers;
    }


    /**
     * Call the appropriate function (success or error) based on the response.
     * @param {Object} xhr - The XMLHttpRequest object or AxiosError.
     * @param {Object} response - The response data.
     * @param {Object} args - The arguments/options for the request.
     */
    callFn = (xhr, response, args) => {
        let fn = args.success;
        this.xhr = xhr;
        let logger = "log";

        if (xhr instanceof AxiosError) {
            fn = args.error;
            logger = "error";
        }
        this.log(response, logger, args);

        if (typeof fn === "function") {
            fn.call(this, response);

            
        }
    };

    /**
     * Show or hide loading text.
     * @param {Object} args - The arguments/options for the request.
     * @param {string} text - The loading text to be displayed.
     * @param {boolean} [show=true] - Whether to show the loading text.
     */
    loadingText(args, text, show = true) {
        if (!args.quiet) {
            const loading = $("#isLoading");
            text = text || args.loadingText;
           

            $("#loadingText", loading).text(text);

            loading.toggleClass("show", !(!show));
            
        }
        
    }

    /**
     * Handle the response from the server.
     * @param {Object} res - The response from the server.
     * @param {Object} args - The arguments/options for the request.
     * @returns {Object} - The parsed response.
     */

    handleResponse = (res, args) => {
        this.loadingText(args, 'Processing');
        const response = this.parseResponse(res);
        args = this.dequeue(args);

        if (typeof response === "string") {
            throw response;
        }
        if (args.isCacheable) {
            CacheService.put(args.cacheKey, response, 60 * 60 * 24);
        }

        this.callFn(res, response, args);

        if (response.success)
            swal({
                content: {
                    element: "div",
                    attributes: {
                        innerText: response.success,
                    },
                },
                icon: "success",
                buttons: {
                    cancel: false,
                    confirm: {
                        text: "OKAY",
                    },
                },
            });
        if (response.alert) {
            swal({
                text: response.alert,
                buttons: {
                    cancel: false,
                    confirm: {
                        text: "OKAY",
                    },
                },
            });
        }

        if (response.redirect)
            setTimeout(() => (window.location.href = response.redirect), 2000);

        return response;
    };

    /**
     * Log debug messages.
     * @param {string} message - The message to be logged.
     * @param {string} type - The type of log (error, log, warn, info).
     * @param {Object} args - The arguments/options for the request.
     */
    log(message, type, args) {
        if (args.debug && ["error", "log", "warn", "info"].includes(type)) {
            console[type](">>>>debug<<<<", message);
        }
    }

    /**
     * Show alert prompt.
     * @param {Object} data - The data for the alert.
     */
    alertPrompt = async (data) => {
        let type = null;
        let message = null;
        if ('alert.error' in data) {
            type = 'error';
            message = data.alert.error;
        }  
        else if ('alert.success' in data) {
            type = 'success';
            message = data.alert.success;
        }  
        else if ('alert.warning' in data) {
            type = 'warning';
            message = data.alert.warning;
        }  
        else if ('alert.info' in data) {
            type = 'info';
            message = data.alert.info;
        }  
        else if ('alert' in data) {
            type = 'info';
            message = data.alert;
        }

        if (message) {
            return swal({
                content: {
                    element: "div",
                    attributes: {
                        innerText: message,
                    },
                },
                icon: type,
                buttons: {
                    cancel: false,
                    confirm: {
                        text: "OKAY",
                    },
                },
            });
        }
        
        return null;
    }


    /**
     * Generate a unique queue ID.
     * @returns {string} - A unique queue ID.
     */
    queueId = () => {
        let value = Math.random().toString(36).substring(2, 9);
        while(value in this._queue) {
            value = Math.random().toString(36).substring(2, 9);
        }
        return value;
    }

    /**
     * Add a request to the queue.
     * @param {Object} args - The arguments/options for the request.
     * @returns {Object} - The arguments/options with the queue ID added.
     */
    enqueue = (args) => {
        if (!args.__queue_id) {
            args.__queue_id = this.queueId();
            this._queue[args.__queue_id] = args;
        }
        return args;
    }

    /**
     * Remove a request from the queue.
     * @param {Object} args - The arguments/options for the request.
     * @returns {Object} - The arguments/options without the queue ID.
     */
    dequeue = (args) => {
        if (args.__queue_id && args.__queue_id in this._queue) {
            delete this._queue[args.__queue_id];
            delete args.__queue_id;
        }

        return args;
    }

    /**
     * Show a toast message for a timeout.
     * @param {Object} args - The arguments/options for the request.
     * @param
     */

    toastTimeout = (args, actionText = 'Refresh', message) => {
        message = message || 'This request is taking time';
        
            args = this.enqueue(args);

            Toast(message, {
                timeout:false,
                position: 'bottom-right',
                action: {
                    text: 'Refresh',
                    callback: () =>  {
                        const queue = Object.values(this._queue);
                        
                        queue.map(options => {
                        options.loadingText = actionText;
                        options.timeout += 3000;
                        return this.http(options);
                        })

                    }
                }
            })
            

    }

    async handleError(err, args) {
        const errorData = parseResponse(err);

        if (/^timeout/i.test(err.message)) {
            return this.toastTimeout(args);
        }

        this.callFn(err, errorData, args);

        if (typeof errorData.confirm === "string") {
            return this.confirmPrompt(errorData.confirm, args);
            
        } 
        else if ("error" in errorData || "errors" in errorData) {
            let errorText = errorData.error;

            
            if ('errors' in errorData) {
                errorText = Object.values(errorData.errors)[0];

                 if (Array.isArray(errorData.errors.passcode) && errorData.errors.passcode.includes("validation.pin")) {
                    toastr.error(
                        "Could not verify your PIN",
                        "Failed Pin Verification"
                    );

                    return this.pinPrompt(args);
                } else if (Array.isArray(errorData.errors.passkey) && errorData.errors.passkey.includes("validation.password")) {
                    toastr.error(
                        "Could not verify your password",
                        "Password not verified"
                    );

                    return this.passwordPrompt(args);
                }
            }
            
            if (Array.isArray(errorText)) {
                errorText = errorText[0];
            }

            swal({
                content: {
                    element: "div",
                    attributes: {
                        innerText: errorText,
                    },
                },
                icon: "error",
                buttons: {
                    cancel: false,
                    confirm: {
                        text: "OKAY",
                    },
                },
            });
        } 
        else if ("alert" in errorData) {
            return swal({
                text: errorData.alert,
                buttons: {
                    cancel: false,
                    confirm: {
                        text: "OKAY",
                    },
                },
            });
        }
        




        if (errorData.redirect) {
            setTimeout(() => (window.location.href = errorData.redirect), 2000);
        } else if (
            errorData instanceof Error &&
            errorData.message === "Timeout"
        ) {
            if (args.retryLimit > 0 && args.retries < args.retryLimit) {
                return this.reConnect(args);
            } else {
                toastr.error("Timeout", {
                    positionClass: "toast-bottom-right",
                });
            }
        } else if (
            /(CSRF token mismatch|Maximum execution)/.test(errorData.message)
        ) {
            return this.toastTimeout(args, 'Retrying', 'Request has met a bottleneck');
        }

        if (args.throwError) throw errorData;
    }

    reConnect(args) {
        if (args.retryLimit > 0 && args.retries < args.retryLimit) {
            args.retries++;

            if (!args.quiet) {
                toastr.error(
                    `Retrying ${args.retries}/${args.retryLimit}`,
                    "Failed Connection",
                    { positionClass: "toast-bottom-right" }
                );
            }

            return setTimeout(() => this.http(args), args.retryDelay);
        }
    }

    parseResponse(axios) {
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
        } else if (
            typeof axios.response === "object" &&
            axios.response !== null
        ) {
            return axios.response;
        } else if (axios instanceof AxiosError) {
            return { message: "An error occurred" };
        } else if (typeof axios === "object" && axios !== null) {
            return axios;
        }

        return { message: axios.toString() };
    }

    appendData(obj, data) {
        if (obj instanceof FormData) {
            for (var key in data) {
                obj.append(key, data[key]);
            }
            return obj;
        } else if (typeof obj === "object" && obj !== null) {
            return { ...obj, ...data };
        } else if (typeof data === "object" && data !== null) {
            return data;
        }

        return {};
    }

    loadingIndicator(indicatorText = "Loading") {
        let existing, wrapper, loading, dotPulse, indicator;
        existing = $(".isLoading");

        if (existing.length > 0) {
            return existing;
        }

        loading = $("<div>");
        wrapper = $("<div>");
        dotPulse = $("<div>");
        indicator = $("<div>");

        loading.addClass("isLoading show");
        wrapper.addClass("flex items-center gap-1");
        dotPulse.addClass("dot-pulse");
        indicator.addClass("text-2xl ml-[20px]");
        indicator.text(indicatorText);

        wrapper.append(dotPulse, indicator);
        loading.append(wrapper);
        return loading;
    }

    disableTriggers(args, disabled = true) {
        if (args.trigger) {
            args.trigger.prop("disabled", disabled);
        }
    }

    start(args) {
        this.disableTriggers(args);
        args.beforeSend();

        this.loadingText(args);
    }

    end(args) {
        this.disableTriggers(args, false);

        if (args.trigger) {
            args.trigger.prop("disabled", false);
        }

        this.silent(this._silence);
        this.event = null;
        this.loadingText(args, null, false);
    }

    confirmPrompt(message, args) {
        args.confirm = false;

        swal({
            title: 'Confirm',
            content: {
                element: "div",
                attributes: {
                    innerText: message,
                },
            },

            buttons: {
                cancel: true,
                confirm: {
                    text: args.confirmText || "Confirm",
                },
            },
        }).then((confirmed) => {
            if (confirmed) {
                args.confirmed = true;

                return this.http(args);
            }
            console.log({confirmed})
        });
    }

    passwordPrompt(args) {
        if (!args.passwordString) {
            args.passwordString =
                typeof args.askPassword === "string"
                    ? args.askPassword
                    : "Password";
        }
        args.askPassword = false;

        return swal({
            title: "identity Verification",
            text: args.passwordString,
            className: "custom-modal",

            content: {
                element: "input",
                attributes: {
                    placeholder: "Type your password",
                    type: "password",
                    autocomplete: "off",
                    className: "input",
                },
            },
            buttons: {
                cancel: true,
                confirm: {
                    text: "Verify & Proceed",
                    className: "btn-primary",
                },
            },
        }).then((value) => {
            if (value) {
                args.data = this.appendData(args.data, {
                    passkey: value,
                });

                return this.http(args);
            } else if (value === "") {
                toastr.error("Password is required", "Error");
                this.passwordPrompt(args);
            } else {
                this.disableTriggers(args, false);
            }
        });
    }
    pinPrompt(args) {
        args.askPin = false;
        const id = "pin_group";

        const enterPin = () => {
            swal({
                title: "Enter your Pin to proceed",
                text: "PIN",
                className: "custom-modal",

                content: {
                    element: "form",
                    attributes: {
                        innerHTML: `
                            <input type="password" autocomplete="off" maxlength="1" autofocus="true" class="pin-token"/>
                            <input type="password" autocomplete="off" maxlength="1" class="pin-token"/>
                            <input type="password" autocomplete="off" maxlength="1" class="pin-token"/>
                            <input type="password" autocomplete="off" maxlength="1" class="pin-token"/>
                            <span class="pin-visibility"></span>
                            `,
                        id: id,
                        className: "pin-code",
                    },
                },
                buttons: {
                    cancel: true,
                    reset: "Reset Pin",

                    confirm: {
                        attributes: {
                            id: "pinAutoFocus",
                        },
                        text: "Verify",
                    },
                },
            }).then((value) => {
                if (value === "reset") {
                    const resetPinCode = () => {
                        swal({
                            title: "Change Pin",
                            text: "Enter new Pin Below",
                            content: {
                                element: "form",
                                attributes: {
                                    innerHTML: `
                                        <input type="password" autocomplete="off" maxlength="1" autofocus="true" class="pin-token"/>
                                        <input type="password" autocomplete="off" maxlength="1" class="pin-token"/>
                                        <input type="password" autocomplete="off" maxlength="1" class="pin-token"/>
                                        <input type="password" autocomplete="off" maxlength="1" class="pin-token"/>
                                        
                                    `,
                                    id: "change_pin",

                                    className: "pin-code",
                                },
                            },
                            buttons: {
                                cancel: true,
                                reset: {
                                    attributes: {
                                        id: "pinAutoFocus",
                                    },
                                    text: "Reset Pin",
                                },
                            },
                        }).then((value) => {
                            if (value !== null) {
                                if (value === "reset") {
                                    const newPin =
                                        $("#change_pin").data("value");

                                    if (newPin.length === 0) {
                                        toastr.error("New Pin is required");

                                        return resetPinCode();
                                    }

                                    const verifyPassword = () => {
                                        this.http({
                                            askPassword:
                                                "Enter your password to verify your identity",
                                            url: "/app/user/update_profile",
                                            data: {
                                                data: { pin: newPin },
                                            },
                                            success: () => {
                                                args.data = this.appendData(
                                                    args.data,
                                                    {
                                                        passcode: newPin,
                                                    }
                                                );

                                                return this.http(args);
                                            },
                                            error: () => {
                                                return verifyPassword();
                                            },
                                        });
                                    };

                                    return verifyPassword();
                                }
                                return resetPinCode();
                            }
                        });

                        $(".pin-code").pin({ paste: true, toggle: true });
                    };

                    return resetPinCode();
                } else if (value) {
                    const wrapper = $(`#${id}`);
                    const inputs = $("input", wrapper);
                    const pins = $(`#${id}`).data("value");

                    args.data = this.appendData(args.data, {
                        passcode: pins,
                    });

                    return this.http(args);
                } else if (value === null) {
                    return this.disableTriggers(args, false);
                }

                toastr.error("Your 4-digit pin is required", "Error");
                this.pinPrompt(args);
            });
        };

        enterPin();

        return $(".pin-code").pin();
    }

    normalizeUrl = (url) => {
        if (url.indexOf("/api") === -1) {
            url = `/api/${url.replace(/^\//, "")}`;
        }
        return url;
    };
    method = (type) => {
        this._method = type;
    }

    http = async (options) => {
        const args = this.parseArgs(options);

        if (typeof args.files === "object" && args.files !== null) {
            args.data = this.appendData(new FormData(), {
                ...args.files,
                ...args.data,
            });
            args.headers["Content-Type"] = "multipart/form-data";
        }


        args.beforeSend =
            typeof args.beforeSend === "function" ? args.beforeSend : () => {};

        args.success =
            typeof args.success === "function"
                ? args.success
                : (res, xhr) => {};
        args.done =
                typeof args.done === "function"
                    ? args.done
                    : () => {};
        args.error =
            typeof args.error === "function" ? args.error : (err, xhr) => {};
        
        if (!args.__type__) {
            args.__type__ = this._method ? this.method : args.type;
        }
       
        
        if (this._method) {
            args.type = this._method;
            this.method(null);
        }
        args.type = ["post", "get", "delete"].includes(args.type)
            ? args.type
            : "post";

        const isCacheable =
            args.cacheKey &&
            typeof args.data === "object" &&
            !(args.data instanceof FormData) &&
            args.data !== null;

        if (isCacheable) {
            if (args.cacheKey === true) {
                args.cacheKey = url.replace(/([a-zA-Z0-9])/, "");
                args.cacheKey += "-" + Object.values(data).join("_");
            }
            if (CacheService.has(args.cacheKey)) {
                const cached_data = CacheService.get(args.cacheKey);
                if (typeof args.success === "function") {
                    args.success(cached_data);
                }
                return cached_data;
            }
        }
        if (!args.url) {
            return console.error("URL is required");
        }

        args.url = this.normalizeUrl(args.url);
        if (this._debug) {
            console.log("HTTP request args:", args);
        }

        await this.loadToken();
        args.headers = this.setHeaders(args.headers);


        if (args.askPin) {
            return this.pinPrompt(args);
        } 
        else if (args.confirm) {
            return this.confirmPrompt(args.confirm, args);
        }
        else if (args.askPassword) {
            return this.passwordPrompt(args);
        }

        this.start(args);
        args.headers.timeout = args.timeout;
        if (this._debug) {
            console.log("Request headers:", args.headers);
        }

        try {
            const response = await Promise.race([
                axios[args.type](args.url, args.data, args.headers),
                new Promise((_, reject) => {
                    setTimeout(
                        () => args.timeout && reject(new Error("Timeout")),
                        args.timeout || 0
                    );

                    window.addEventListener("keydown",  (e) => {
                        if (e.key === "Escape") {
                            reject(new Error("Aborted"));
                        }
                    });
                }),
            ]);

            return this.handleResponse(response, args);
        } catch (err) {
            if (this._debug) {
                console.error("HTTP request errors:", err);
            }
            return this.handleError(err, args);
        } finally {
            this.end(args);
            args.done();
        }
    };

    /**
     * Make multiple concurrent AJAX requests using axios.
     * @param {Array<Object>} requests - The array of request options.
     * @returns {Promise<Array<Object>>} - The array of responses from the server.
     */
    ajax = async (request) => {
        if (typeof request === 'object' && request !== null && !Array.isArray(request)) {
            request = [request];
        }
        const argsArray = request.map(options => this.parseArgs(options));

        if (this._debug) {
            console.log("HTTP request args:", argsArray);
        }

        await this.loadToken();

        const headers = this.setHeaders();

        if (this._debug) {
            console.log("Request headers:", headers);
        }

        const configArray = argsArray.map(args => ({
            method: args.type,
            url: args.url,
            headers: headers,
            data: args.data,
            timeout: args.timeout,
        }));

        try {
            const responses = await Promise.all(configArray.map(config => axios(config)));
            return responses.map((response, i) => this.handleResponse(response, argsArray[i]));
        } catch (err) {
           

            if (this._debug) {
                console.error("HTTP request errors:", err);
            }
            if (args.throwError) {
                throw err;
            }
            return this.handleError(err, args);
        }
    }
}
