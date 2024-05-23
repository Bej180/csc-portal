import axios, { AxiosError } from "axios";

const getHeaders = (session, customHeaders) => {
    const headers = {
        "Content-Type": "application/json",
        "Accept": "application/json",
        ...(session && { Authorization: `Bearer ${session}` }),
        ...customHeaders,
    };
    return headers;
};

const handleResponse = (response, callback, init) => {
    response = parseResponse(response);
    
    if (!init.silent)
        document.getElementById("isLoading").classList.remove("show");

    if (typeof response === "string") {
      throw response;
    }
    if (callback) callback(response);
    if (!init.silent && response.success)
        toastr.success(response.success);
    if (response.redirect)
        setTimeout(() => (window.location.href = response.redirect), 2000);

    return response;
};

const handleError = (err, fallback, init) => {
    const errorData = parseResponse(err);
    

    if (fallback) fallback(errorData, err);
    if (!init.silent) {
        ENV.error(err);

        if ('error' in errorData || 'errors' in errorData) {
          toastr.error(
              errorData.errors
                  ? Object.values(errorData.errors)[0]
                  : errorData.error
          );

        }
        if (errorData.redirect)
            setTimeout(() => (window.location.href = errorData.redirect), 2000);
    }


    throw errorData;
};
const parseResponse = (axios) => {
    
    if (typeof axios.response === 'undefined') {
        let data = axios.data||axios;
        if (typeof data == 'string') {
            return {response: data};
        }
        return data;
    }
    else if (typeof axios.response.data === 'object' && axios.response.data !== null) {
        return axios.response.data;
    }
    else if (typeof axios.response === 'object' && axios.response !== null) {
        return axios.response;
    }
    else if (axios instanceof AxiosError) {
        return {message: 'An error occurred'};
    }
    else if (typeof axios === 'object' && axios !== null) {
        return axios;
    }

    return {message: axios.toString()};
}

const api = async (url, data, callback, fallback, init = {}) => {
    url = `/api/${url.replace(/^\//, "")}`;

    if (typeof data === "function") {
        [data, callback, fallback, init] = [
            undefined,
            data,
            callback,
            fallback || {},
        ];
    } else if (typeof callback === "object") {
        init = callback;
        [callback, fallback] = [undefined, fallback];
    }

    const session =
        localStorage.getItem("authToken") ||
        sessionStorage.getItem("authToken");

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
                    () => reject(new Error("Request Timeout")),
                    init.timeout || 5000
                )
            ),
        ]);


        return handleResponse(response, callback, init);
    } catch (err) {
        
        return handleError(err, fallback, init);
    }
}





window.parseResponse = parseResponse;
window.api = api;


// import axios from "axios";

// export async function api(url, dataCallback, callback, fallback, init) {
//     url = url.replace(/^\//, "");
//     url = "/api/" + url;
//     let data = dataCallback;

//     if (typeof dataCallback === "function") {
//         // url, callback, fallback init
//         init = callback;
//         if (typeof callback === "function") {
//             init = fallback;
//             fallback = callback;
//         }
//     } else if (typeof dataCallback === "object" && dataCallback !== null) {
//         data = dataCallback;
//     } else if (typeof callback === "object") {
//         init = callback;
//     }

//     const defaultInit = {
//         silent: false,
//         headers: {
//             "Content-Type": "application/json",
//         },
//     };

//     const session = "88|ZgpAXuUQm2hkQc58L6ess0K15x5Kc9oVIdwuLt1d20722092"; //sessionStorage.getItem("auth_token");

//     if (session) {
//         defaultInit.headers.Authorization = "Bearer " + session;
//     }
//     if (typeof init !== "object" || !init) {
//         init = {};
//     }
//     init = { ...defaultInit, ...init };

//     return axios
//         .post(url, data, init)
//         .then(async (response) => {
//             if (!init.silent) {
//                 $("#isLoading").removeClass("show");
//             }

//             if (!response.ok) {
//                 throw response.data;
//             }
//             return response.data;
//         })
//         .then(async (response) => {
//             ENV.log(response);
//             $("#isLoading").removeClass("show");

//             if (typeof callback === "function") {
//                 callback(response);
//             }

//             if (init.silent) {
//                 return response;
//             }

//             if ("success" in response) {
//                 toastr.success(response.success);
//             }

//             if ("redirect" in response) {
//                 setTimeout(() => {
//                     window.location.href = response.redirect; // Redirect user
//                 }, 2000);
//             }

//             return resolve(response);
//         })
//         .catch(async (err) => {
//             if (typeof fallback === "function") {
//                 let data = {};
//                 let xhr = err;

//                 if (typeof err === "string") {
//                     data = { message: err };
//                 } else if (
//                     typeof err === "object" &&
//                     err !== null &&
//                     "response" in err &&
//                     "data" in err.response
//                 ) {
//                     data = err.response.data;
//                 }
//                 fallback(data, err);
//             }
//             console.log(err);

//             if (init.silent) {
//                 return err;
//             }

//             ENV.error(err);

//             if (
//                 typeof err === "object" &&
//                 err !== null &&
//                 "response" in err &&
//                 "data" in err.response
//             ) {
//                 err = err.response.data;
//                 if (
//                     "errors" in err &&
//                     typeof err.errors === "object" &&
//                     err.errors !== null
//                 ) {
//                     const errors = Object.values(err.errors);

//                     toastr.error(errors[0]);
//                 } else if ("error" in err && err.error.length > 0) {
//                     toastr.error(err.error);
//                 }
//                 if ("redirect" in err) {
//                     setTimeout(() => {
//                         window.location.href = err.redirect;
//                     }, 2000);
//                 }
//             }

//             throw err;
//         });
// }

// api(
//     "/testtter",
//     { john: "Bright" },
//     function (res) {
//         console.log(res);
//     },
//     (err) => {
//         console.error(err);
//     },
//     false
// );
