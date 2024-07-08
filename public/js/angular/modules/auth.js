import "../services/students.js";
// import '../services/auth_services.js';

const session_name = "access_token";

/**
 * AuthController
 * Controller responsible for handling user authentication and registration.
 * @param {Object} $scope - AngularJS scope object for data binding.
 */
app.controller("AuthController", [
    "$scope",
    "$timeout",
    "StudentService",
    "AuthService",
    function ($scope, $timeout, StudentService, AuthService) {
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
        };

        $scope.storeTokenFromResponse = (response) => {
            response = parseResponse(response);
            const token =
                response.temporary_session || response.persistent_session;

            if (typeof response.persistent_session == "string") {
                localStorage.setItem(session_name, token);
            }

            sessionStorage.setItem(session_name, token);
        };

        /**
         * login
         * Logs in the user with provided credentials and password.
         * @param {Event} event - Event triggered by login action.
         */
        $scope.login = (callbackUrl) => {
            $scope.loginData.callbackUrl = callbackUrl;
            return AuthService.login($scope.loginData);
        };

        $scope.verifyOTP = (callbackUrl) => {
            return $scope.api(
                "/app/auth/verify_otp",
                {
                    tokens: $scope.otp,
                    callbackUrl: callbackUrl,
                    email: $scope.otp_user_email,
                },
                (res) => {
                    $scope.storeTokenFromResponse(res);
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
        $scope.register = () => {
            return StudentService.register($scope.registerData, (res) => {
                alert(res);
            });
        };

        /**
         * requestActivationLink
         * Sends a request for account activation link to the provided email.
         * @param {string} email - Email address to send the activation link.
         */
        $scope.requestActivationLink = (email) => {
            // Perform request for activation link API request
            $scope.api(
                "/request_activation_link",
                {
                    email,
                },
                (res) => {
                    console.log(res);
                    $scope.$apply();
                    toastr.success(res.message); // Display success message
                }
            );
        };
    },
]);

app.controller('QrCodeController', ['$scope', '$interval', 'QrCodeService', function($scope, $interval, QrCodeService) {
    $scope.token = '';
    $scope.qrCode = '';

    $scope.generateQrCode = function() {
        QrCodeService.generateQrCode().then(function(response) {
            $scope.qrCode = response.data.qrCode;
            $scope.token = response.data.token;
            $scope.startTokenRegeneration();
            $scope.checkScanStatus();
        });
    };

    $scope.checkScanStatus = function() {
        QrCodeService.checkScanStatus($scope.token).then(function(response) {
            if (response.data.status === 'scanned') {
                window.location.href = '/home';
            } else {
                setTimeout($scope.checkScanStatus, 2000);
            }
        });
    };

    $scope.regenerateQrCode = function() {
        QrCodeService.regenerateQrCode($scope.token).then(function(response) {
            $scope.qrCode = response.data.qrCode;
            $scope.token = response.data.token;
        });
    };

    $scope.startTokenRegeneration = function() {
        $interval($scope.regenerateQrCode, 300000); // Regenerate every 5 minutes
    };

    $scope.generateQrCode(); // Initial QR code generation
}]);

app.controller('AuthQrScanController', ['$scope', 'AuthQrCodeService', function($scope, QrCodeService) {
    document.addEventListener('DOMContentLoaded', () => {
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        scanner.addListener('scan', function (content) {
            const token = new URL(content).searchParams.get('token');

            if (token) {
                QrCodeService.scanQrCode(token).then(function(response) {
                    alert('QR code scanned successfully.');
                }).catch(function(error) {
                    console.error('Error:', error);
                    alert('An error occurred while processing the scan request.');
                });
            } else {
                alert('Invalid QR code.');
            }
        });

        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
            }
        }).catch(function (e) {
            console.error(e);
        });
    });
}]);


app.directive("otpInputs", function () {
    return {
        restrict: "A",
        link: function (scope, element) {
            const inputs = element.find("input"); // Find all OTP input fields

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
                if (value.length > 1 && $(this).is("input:last")) {
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
                        if (index === inputs.length - 1) {
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
