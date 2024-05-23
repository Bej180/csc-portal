const session_name = "authToken";

app.service("AuthService", [
    "$http",
    function ($http) {
        this.login = function (credentials) {
            return api("dologin", credentials)
                .then(async (res) => {
                    this.storeTokenFromResponse(res);
                    return res;
                })
                .catch((err) => {
                    throw parseResponse(err);
                });
        };

        this.storeTokenFromResponse = (response) => {
            response = parseResponse(response);
            if (typeof response.temporary_session == "string") {
                this.storeToken(response.temporary_session, false);
            } else if (typeof response.persistent_session == "string") {
                this.storeToken(response.persistent_session, true);
            }
        };
        this.storeToken = function (token, stayLoggedIn) {
            if (stayLoggedIn) {
                localStorage.setItem(session_name, token);
            }
            sessionStorage.setItem(session_name, token);
        };

        this.is_logged = function checkLoggedIn() {
            const tokenInSession = sessionStorage.getItem(session_name);
            const tokenInLocal = localStorage.getItem(session_name);

            if (tokenInSession) {
                // If token found in sessionStorage, move it to localStorage
                localStorage.setItem(session_name, tokenInSession);
                sessionStorage.removeItem(session_name);
                return tokenInSession;
            } else if (tokenInLocal) {
                return tokenInLocal;
            } else {
                return false;
            }
        };

        this.autoLog = function (logged) {
            const tokenInSession = sessionStorage.getItem(session_name);
            const tokenInLocal = localStorage.getItem(session_name);

            const token = tokenInLocal || tokenInLocal;
            if (tokenInSession && tokenInLocal && !logged) {
                localStorage.removeItem(session_name);
                sessionStorage.removeItem(session_name);
                return;
            }

            if (tokenInSession && !tokenInLocal) {
                localStorage.setItem(session_name, tokenInSession);
            }

            if (!tokenInSession && tokenInLocal && token) {
                api("/authenticate", { token: token })
                    .then(function (res) {
                        this.storeToken(res.token, true);
                    })
                    .catch(function (error) {
                        console.error(error);
                        localStorage.removeItem(session_name);
                        sessionStorage.removeItem(session_name);
                    });
            }
        };
    },
]);
/**
 * AuthController
 * Controller responsible for handling user authentication and registration.
 * @param {Object} $scope - AngularJS scope object for data binding.
 */
app.controller("AuthController", [
    "$scope",
    "AuthService",
    function ($scope, AuthService) {
        // Initialize variables
        $scope.credential = null; // User credential (email or username)
        $scope.password = null; // User password
        $scope.remember = false; // Remember user login
        $scope.email = ""; // User email
        $scope.registerData = {}; // Data for user registration
        $scope.loginData = { rememberme: null, usermail: null, password: null }; // Data for user

        $scope.initAuth = (is_logged) => {
            $scope.resetPassword = false;
            $scope.activateAccount = false;
            $scope.otp = [];
            AuthService.autoLog(is_logged);
        };

        /**
         * login
         * Logs in the user with provided credentials and password.
         * @param {Event} event - Event triggered by login action.
         */
        $scope.login = (callbackUrl) => {
            $scope.loginData.callbackUrl = callbackUrl;

            return AuthService.login($scope.loginData)

                .then((res) => {
                    AuthService.storeTokenFromResponse(res);
                })
                .catch((err) => {
                    if (typeof err.cause === "string" && err.cause === "2fa") {
                        $scope.otp_user_email = err.user_email;
                        $scope.route("2fa");
                        $scope.$apply();
                    }
                })
                .catch((err) => {
                    console.log(err);
                });

            return api(
                "/dologin",
                $scope.loginData,
                (res) => () => {},
                (err) => {
                    console.log(err);
                    if (
                        typeof err === "object" &&
                        err !== null &&
                        "cause" in err
                    ) {
                        if (err.cause == "2fa") {
                            $scope.otp_user_email = err.user_email;
                            $scope.route("2fa");
                        }
                    }
                }
            );

            return api(
                "/dologin",
                $scope.loginData,
                (response) => {
                    $scope.loginData.rememberme = null;

                    if ("temporary_session" in response) {
                        sessionStorage.setItem(
                            "access_token",
                            response.temporary_session
                        );
                    } else if ("persistent_session" in response) {
                        localStorage.setItem(
                            "access_token",
                            response.persistent_session
                        );

                        sessionStorage.setItem(
                            "access_token",
                            response.persistent_session
                        );
                    } else if ("token" in response) {
                        localStorage.setItem("access_token", response.token);
                    }
                },
                (err) => {
                    if (
                        typeof err === "object" &&
                        err !== null &&
                        "cause" in err
                    ) {
                        if (err.cause == "2fa") {
                            $scope.otp_user_email = err.user_email;
                            $scope.route("2fa");
                        }
                    }
                }
            );
        };

        $scope.verifyOTP = (callbackUrl) => {
            return api(
                "/app/auth/verify_otp",
                {
                    tokens: $scope.otp,
                    callbackUrl: callbackUrl,
                    email: $scope.otp_user_email,
                },
                (res) => {
                    AuthService.storeTokenFromResponse(res);
                }
            );
            return $scope.api("/app/auth/verify_otp", {
                tokens: $scope.otp,
                callbackUrl: callbackUrl,
                email: $scope.otp_user_email,
            });
        };

        /**
         * ResetPassword
         * Sends a request to reset the user's password.
         * @param {string} button_name - Name of the reset password button.
         */
        $scope.ResetPassword = (email) => {
            // Perform reset password API request
            return $scope.api("/app/auth/send_reset_link", {
                email: email,
            });
        };
        $scope.getResetTimeDifference = (token) => {
            const retrieveTimeDifference = () => {
                return $scope.api(
                    "/app/auth/resetpassword/timer",
                    { token },
                    (res) => {
                        let time = res.seconds;

                        const padZero = (num) => (num < 10 ? "0" + num : num);
                        const prepareTime = (time) => {
                            let remaining;
                            const hours = Math.floor(time / 3600);
                            remaining = time % 3600;
                            const minutes = Math.floor(remaining / 60);
                            const seconds = remaining % 60;
                            let text = "";
                            if (hours > 0) {
                                text += `:${padZero(hours)}`;
                            }

                            text += `:${padZero(minutes)}`;

                            text += `:${padZero(seconds)}`;

                            return text.replace(/^:/, "");
                        };

                        $scope.timeText = prepareTime(time);

                        let timer = 1000;

                        if (time > 59) {
                            timer = 60000;
                        }

                        let interval = setInterval(() => {
                            time -= 1;
                            timer = time <= 59 ? 1000 : 60000;

                            $scope.timeText = prepareTime(time);
                            if (time < 1) {
                                clearInterval(interval);
                                interval = null;
                            }
                            $scope.$apply();
                        }, 1000);
                    }
                );
            };
            retrieveTimeDifference();
        };

        /**
         * changePassword
         * Changes the user's password.
         */
        $scope.changePassword = () => {
            // Perform change password API request
            return $scope.api("/app/auth/resetpassword", {
                password: $scope.password,
                password_confirmation: $scope.password_confirmation,
                token: $scope.token,
            });
        };

        /**
         * passwordIsStrong
         * Checks if the provided password is strong.
         * @param {string} password - Password to check.
         * @returns {boolean} - Indicates whether the password is strong or not.
         */
        $scope.passwordIsStrong = (password) => {
            return (
                /\d/.test(password) &&
                /\W/.test(password) &&
                password.length > 6
            );
        };

        /**
         * register
         * Registers a new user with provided details.
         * @param {Event} event - Event triggered by registration action.
         */
        $scope.register = (event) => {
            event.preventDefault();

            // Set user's full name
            $scope.registerData.name = $scope.surname + " " + $scope.othernames;

            // Check if passwords match and meet strength requirements
            if (
                $scope.registerData.password !==
                $scope.registerData.password_confirmation
            ) {
                return toastr.warning("Passwords do not match");
            }
            if (!$scope.passwordIsStrong($scope.registerData.password)) {
                return toastr.warning(
                    "Password is not strong, it must be at least 6 characters long and must contain numbers, special characters and at least an upper case letter"
                );
            }

            // Perform user registration API request
            api("/doRegister", $scope.registerData)
                .then((res) => console.log(res))
                .catch((err) => console.error(err));
        };

        /**
         * requestActivationLink
         * Sends a request for account activation link to the provided email.
         * @param {string} email - Email address to send the activation link.
         */
        $scope.requestActivationLink = (email) => {
            // Perform request for activation link API request
            api("/request_activation_link", {
                email,
            })
                .then((res) => {
                    console.log(res);
                    $scope.$apply();
                    toastr.success(res.message); // Display success message
                })
                .catch((err) => {
                    console.log(err);
                    setTimeout(() => {
                        $scope.$apply();
                        if (typeof err === "object" && err && err.message) {
                            toastr.error(err.message); // Display error message
                        }
                    }, 3000);
                })
                .finally(() => {
                    setTimeout(() => {
                        $scope.$apply();
                    }, 6000);
                });
        };
    },
]);

app.directive("otpInputs", function () {
    return {
        restrict: "A",
        link: function (scope, element) {
            const inputs = element.find("input.otp-input"); // Find all OTP input fields

            // Set focus on the first OTP input field
            inputs.eq(0).focus();

            // Add event listeners to all OTP input fields for handling input
            inputs.on("input", function (event) {
                const value = $(this).val();
                if (/\D/.test(value)) {
                    $(this).val("");
                } else if (value.length === 1) {
                    const index = inputs.index(this);
                    if (index < inputs.length - 1) {
                        // Move focus to the next input field if available
                        $(inputs[index + 1]).focus();
                    }
                }
            });

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
                if (value.length > 1 && $(this).is("input.otp-input:last")) {
                    $(this).blur(); // Blur the input field if more than one character is entered and it's the last field
                }
            });

            // Handle pasting OTP from clipboard
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
                        if (index === 5) {
                            // form.submit(); // Submit the form after pasting all OTP characters
                        }
                    });
                }
            });
        },
    };
});

/**
 * OTP Form Handling
 * This script enhances the functionality of OTP (One-Time Password) input forms.
 * It manages input focus, keyboard navigation, and pasting OTP from clipboard.
 * @listens document.ready - Event triggered when the DOM is fully loaded.
 */
