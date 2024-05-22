export default class Http {
    static error = null;
    static config = {
        baseUrl: window.location.protocol + "//" + window.location.host,
        XMLHttpRequest: "xRequest",
        credentials: "same-origin", // Credential mode for requests (defaults to "include")
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
        },
        timeout: 5000, // Default timeout in milliseconds
        cache: "no-cache", // Default cache behavior
        mode: "cors", // Default mode for cross-origin requests
        debug: true,
        redirect: "follow", // Default behavior for redirects
        referrer: "no-referrer", // Default referrer behavior
        referrerPolicy: "no-referrer-when-downgrade", // Default referrer policy
        signal: null, // Optional AbortSignal for request cancellation
        integrity: "", // Optional subresource integrity hash
        body: null, // Optional pre-defined request body
        contentType: null, // Optional
        dataType: null,
        method: "GET", // Default request method (can be overridden)
        parser: JSON.parse, // Default JSON parsing function
        onUploadProgress: null, // Optional progress callback for uploads
        onDownloadProgress: null, // Optional progress callback for downloads
        // **Extensible Configurations:**
        // Add other configurations here as needed
        transformRequest: null, // Optional function to transform request data
        transformResponse: null, // Optional function to transform response data
        validateResponse: null, // Optional function to validate response
        maxRetry: 0, // Default number of retries for failed requests
        retryDelay: 5000, // Default delay (ms) between retries
        // Error Handling:
        // error: () => {}, // Optional error handling function
        // Authentication and Authorization (examples):
        // success: () => {}, // Optional
        auth: {
            type: "", // Authentication type (e.g., 'basic', 'bearer')
            username: "",
            password: "",
            token: "",
        },
        // **Validation and Adjustment Options:**
        validationOptions: {
            baseUrlStartsWithProtocol: true, // Allow relative paths (optional)
            contentTypeJsonRequiresAuth: true, // Require auth for JSON content (optional)
            timeoutPositiveNumber: true, // Enforce timeout as a positive number
        },
    };

    static async configure(config = {}) {
        // Merge user-provided config with defaults
        this.config = Object.assign({}, this.config, config);

        // **Validation and Adjustments:**
        const { baseUrl, timeout, headers } = this.config;
        const { validationOptions } = this.config;

        // Adjust headers based on content type (configurable logic)
        if (headers["Content-Type"]?.toLowerCase() === "application/json") {
            const token = this.config.authToken || Storage.get("auth_token");

            if (token) {
                this.config.headers.Authorization = `Bearer ${token}`;
            }
        }

        return this.config;
    }

    static URL(url, options) {
        if (window.URL && window.URL.constructor) {
            return new URL(url, options.baseUrl).toString();
        }

        const baseUrl = options.baseUrl || "";
        const parsedUrl = {};

        // Extract protocol (optional)
        const protocolMatch = url.match(/^([a-z]+):\/\//i);
        if (protocolMatch) {
            parsedUrl.protocol = protocolMatch[1] + ":";
            url = url.slice(protocolMatch[0].length); // Remove protocol from remaining url
        }

        // Extract hostname and port (optional)
        const hostPortMatch = url.match(/^([^/?#]+)/);
        if (hostPortMatch) {
            const hostParts = hostPortMatch[1].split(":");
            parsedUrl.hostname = hostParts[0];
            if (hostParts.length > 1) {
                parsedUrl.port = hostParts[1];
            }
            url = url.slice(hostPortMatch[0].length); // Remove hostname and port from remaining url
        }

        // Extract pathname
        const pathMatch = url.match(/^\/(.*?)(?:\?|\#|#?\?)/);
        if (pathMatch) {
            parsedUrl.pathname = "/" + pathMatch[1];
            url = url.slice(pathMatch[0].length); // Remove pathname from remaining url
        } else {
            parsedUrl.pathname = baseUrl ? "/" + url : url; // Handle cases without path
        }

        // Extract search (optional)
        const searchMatch = url.match(/\?(.*?)(?:#|#?\?)/);
        if (searchMatch) {
            parsedUrl.search = "?" + searchMatch[1];
            url = url.slice(searchMatch[0].length); // Remove search from remaining url
        }

        // Extract hash (optional)
        const hashMatch = url.match(/#(.*)/);
        if (hashMatch) {
            parsedUrl.hash = "#" + hashMatch[1];
        }

        // Combine URL parts
        return (
            (parsedUrl.protocol || "") +
            (parsedUrl.hostname
                ? (parsedUrl.protocol ? "//" : "") + parsedUrl.hostname
                : "") +
            (parsedUrl.port ? ":" + parsedUrl.port : "") +
            parsedUrl.pathname +
            (parsedUrl.search || "") +
            (parsedUrl.hash || "")
        );
    }

    static setAuth({ token }) {
        if (token) {
            Storage.set("auth_token", token);
        }
    }

    static log(message, text) {
        if ('debug' in this.config && this.config.debug) {
            console.log(message, text);
        }
    }

    static parseContentType(contentType) {
        switch(contentType) {
            case 'json': return 'application/json'; break;
            case 'text': return 'text/plain'; break;
            case 'html': return 'text/html'; break;
            case 'xml': return 'application/xml'; break;
            case 'jsonp': return 'application/json'; break;
            case 'form': return 'application/form-data'; break;
            default: return 'application/x-www-form-urlencoded';
        }
    }

    static expectedDataType(contentType) {
        switch(contentType) {
            case 'json': return 'application/json'; break;
            case 'text': return 'text/plain'; break;
            case 'html': return 'text/html'; break;
            case 'xml': return 'application/xml'; break;
            case 'jsonp': return 'application/json'; break;
            case 'form': return 'application/form-data'; break;
            default: return 'application/x-www-form-urlencoded';
        }
    }
    

    static parseDataType(text, dataType) {
        switch(dataType) {
            case 'json': return JSON.parse(text); break;
            case 'text': return text; break;
            case 'jsonp': return JSON.parse(text); break;
            default: return text; break;
        }
    }


    static async api(url, data, init = {}) {
        return new Promise((resolve, reject) => {

            this.ajax('/api'+url, {
                data: data,
                contentType: 'json',
                dataType: 'json',
                type: 'POST',
                success: (e) => resolve,
                error: (e) => reject,
                ...init
                
            });
        });
    }

    static async fetch(url, { success, error, maxRetry, retryDelay, dataType, ...config }, currentRetry = 0) {
        const reportError = (errorMessage, text) => {
            if (error && typeof error === "function") {
                error(errorMessage);
            } else {
                this.log(errorMessage, text);
            }
            throw new Error(errorMessage);
        };


        try {
            // Get CSRF token if needed
            const csrfToken = await getCSRFToken();
            if (csrfToken && config.method && config.method === "POST") {
                this.config.headers["X-CSRF-TOKEN"] = csrfToken;
            }

            if (config.contentType) {
                config.headers['Content-Type'] = this.parseContentType(config.contentType);
                config.headers['Accept'] = this.expectedDataType(config.contentType);
            }
            
            //config.headers['Accept'] = this.expectedDataType(dataType);
            
            // Make the HTTP request
            const response = await fetch(url, config);
            const text = await response.text();
            
        
            let returnData = null;
            try { returnData = this.parseDataType(text, config.headers['Content-Type']) } catch (e) { this.log("Error parsing  response:"+e.message, text); }
            let errorValue = {status:response.status};
            
            errorValue.text = text;
           
            errorValue.data = returnData;
            errorValue.error = "Failed to fetch data";
            if (typeof errorValue.data === 'object') {
                if (('errors' in errorValue.data) && typeof errorValue.data.errors === 'object' && errorValue.data.errors !== null) {
                    errorValue.error = Object.values(errorValue.data['errors'])[0];
                }
                else if ('error' in errorValue.data) {
                    errorValue.error = errorValue.data['error'];
                }
                else if ('message' in errorValue.data) {
                    errorValue.error = errorValue.data['message'];
                }
                if (Array.isArray(errorValue.error)) {
                    errorValue.error = errorValue.error[0];
                }
            }
            
            // Handle network-related errors
            const networkErrorCodes = [0, 503, 504,500];

            if (networkErrorCodes.includes(errorValue.status)) {
               
                if (currentRetry < maxRetry) {
                    errorValue.error = `Retrying (${currentRetry + 1}/${maxRetry})...`;
                    this.log(`Retrying (${currentRetry + 1}/${maxRetry})...`);
                    
                    // Retry the request after a delay
                    await new Promise(resolve => setTimeout(resolve, retryDelay));
                    
                    return this.fetch(url, { success, error, maxRetry, retryDelay, ...config }, currentRetry + 1);
                } else {
                    if (maxRetry === 0) {
                        return reportError( 'error' in errorValue ? errorValue.error : 'Failed to fetch data', text);
                    }
                    errorValue.error = `Maximum number of retries (${maxRetry}) reached.`;
                    
                    reportError(maxRetry === 0? errorValue : `Maximum number of retries (${maxRetry}) reached.`, text);
                }
            }
            else if (!response.ok) {
                
                reportError(errorValue, text);
            }
            
            
            // Call success callback or resolve the promise
            if (success && typeof success === "function") {
                success(returnData);
            } else { return returnData; }

        } catch (error) {
            reportError(error.message || "Failed to fetch response");
        }
    }
    



    /**
     * @method get
     * @description Performs a GET request to the specified URL.
     * @param {string} url The URL for the request.
     * @param {object} [options] Optional additional options for the request.
     * @returns {Promise<any>} Promise that resolves with the parsed response data or rejects with an error.
     */
    static async get(url, config = {}) {
        config = Object.assign({}, this.config, config);

        // Build the request URL
        const requestUrl = this.URL(url, config);
        

        try {
            const data = await this.fetch(requestUrl, config);

            return data;
        } catch (error) {
            // Handle errors (optional custom error handling can be implemented here)
            if (typeof config.error === 'function') {
                config.error(error);
            } else {
                throw error; // Re-throw the error for further handling
            }
        }
    }

    /**
     * @method post
     * @description Performs a POST request to the specified URL.
     * @param {string} url The URL for the request.
     * @param {object} data The data to send in the request body.
     * @param {object} [options] Optional additional options for the request.
     * @returns {Promise<any>} Promise that resolves with the parsed response data or rejects with an error.
     */
    static async post(url, data, config = {}) {
        config = Object.assign({}, this.config, config);
        config.method = "POST";

        // Build the request URL
        const requestUrl = this.URL(url, config);

        try {
            // Stringify data if it's an object (assuming JSON data)
            if (data) {
                config.body =
                    typeof data === "object" ? JSON.stringify(data) : data;
            }

            const response = await this.fetch(requestUrl, config);

            return response;
        } catch (error) {
            // Handle errors (similar logic to get method)
            if (config.error) {
                config.error(error);
            } else {
                throw error;
            }
        }
    }

    /**
     * @method put
     * @description Performs a PUT request to the specified URL.
     * @param {string} url The URL for the request.
     * @param {object} data The data to send in the request body.
     * @param {object} [options] Optional additional options for the request.
     * @returns {Promise<any>} Promise that resolves with the parsed response data or rejects with an error.
     */
    static async put(url, data, config = {}) {
        config = Object.assign({}, this.config, config);

        // Build the request URL
        const requestUrl = this.URL(url, config);

        try {
            // Stringify data if it's an object (assuming JSON data)
            const body = typeof data === "object" ? JSON.stringify(data) : data;
            config.body = body;
            config.method = "PUT"; // Set request method explicitly

            const response = await this.fetch(requestUrl, config);

            return response;
        } catch (error) {
            // Handle errors (similar logic to get method)
            if (config.error) {
                config.error(error);
            } else {
                throw error;
            }
        }
    }

    /**
     * @method delete
     * @description Performs a DELETE request to the specified URL.
     * @param {string} url The URL for the request.
     * @param {object} [options] Optional additional options for the request.
     * @returns {Promise<void>} Promise that resolves without a value on success or rejects with an error.
     */
    static async delete(url, config = {}) {
        config = Object.assign({}, this.config, config);

        // Build the request URL
        const requestUrl = this.URL(url, config);

        try {
            // Set request method explicitly
            config.method = "DELETE";

            const response = await this.fetch(requestUrl, config);
            return;
        } catch (error) {
            // Handle errors (similar logic to get method)
            if (config.error) {
                config.error(error);
            } else {
                throw error;
            }
        }
    }

    static async options(url, config = {}) {
        config = Object.assign({}, this.config, config);

        // Build the request URL
        const requestUrl = this.URL(url, config);

        try {
            // Set request method explicitly
            config.method = "OPTIONS";

            const response = await this.fetch(requestUrl, config);
            return response;
        } catch (error) {
            // Handle errors (similar logic to get method)
            if (config.error) {
                config.error(error);
            } else {
                throw error;
            }
        }
    }

    /**
     * @method ajax
     * @description Performs an HTTP request with configurable options.
     * @param {string} url The URL for the request.
     * @param {string} method The HTTP method (GET, POST, PUT, DELETE, etc.).
     * @param {object} [data] The data to send in the request body (optional).
     * @param {object} [options] Optional additional options for the request.
     * @returns {Promise<any>} Promise that resolves with the parsed response data or rejects with an error.
     */
    static async ajax(url, { type, data, ...config }) {
        config = Object.assign({}, this.config, config);

        if (type) {
            config.method = type.toUpperCase();
        }

        if (
            ["POST", "PUT"].includes(config.method) &&
            typeof data === "object"
        ) {
            config.body = JSON.stringify(data);
        }

        return this.fetch(this.URL(url, config), config);
    }
}

Http.configure({
    baseUrl: "http://127.0.0.1:8000/api/",
});

window.Http = Http;