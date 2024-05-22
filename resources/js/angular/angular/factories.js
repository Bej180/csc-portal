/**
 * authInterceptor Factory
 * Factory responsible for intercepting HTTP requests and adding authorization headers.
 *
 * @param {Object} $q AngularJS promise service.
 * @param {Object} $injector AngularJS service injector.
 * @returns {Object} The authInterceptor object.
 */
app.factory("authInterceptor", function ($q, $injector) {
    return {
        /**
         * request
         * Intercepts HTTP requests and adds authorization headers with the token retrieved from the authService.
         *
         * @param {Object} config The HTTP request configuration object.
         * @returns {Object} The modified HTTP request configuration object.
         */
        request: function (config) {
            var authService = $injector.get("authService");
            var token = authService.getToken();

            if (token) {
                config.headers["Authorization"] = "Bearer " + token;
            }

            return config;
        },
    };
});


/**
 * authService Factory
 * Factory responsible for managing authentication-related operations.
 * 
 * @returns {Object} The authService object.
 */
app.factory("authService", function () {
    var token = localStorage.getItem("token");

    return {
        /**
         * getToken
         * Retrieves the authentication token stored in the localStorage.
         * 
         * @returns {string|null} The authentication token, or null if not found.
         */
        getToken: function () {
            return token;
        },
    };
});
