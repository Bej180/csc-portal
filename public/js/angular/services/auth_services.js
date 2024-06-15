app.service("AuthService", function () {
    this.session_name = "access_token";
    this.logged = false;

    this.storeTokenFromResponse = (response) => {
        response = parseResponse(response);
        const token = response.temporary_session || response.persistent_session;

        if (typeof response.persistent_session == "string") {
            localStorage.setItem(this.session_name, token);
        }

        sessionStorage.setItem(this.session_name, token);
    };

    this.autoLog = function (logged) {
        this.logged = logged;
        const tokenInSession = sessionStorage.getItem(this.session_name);
        const tokenInLocal = localStorage.getItem(this.session_name);
        const callbackUrl = Location.get('callbackUrl', '/home');

        const token = tokenInLocal || tokenInSession;

        if (tokenInSession && !tokenInLocal) {
            localStorage.setItem(this.session_name, tokenInSession);
        }
    
        if (!logged && token) {
            http({
                url: "/authenticate",
                data: {
                    token,
                    callbackUrl
                },
                success: (res) => {
                    this.storeTokenFromResponse(res);

                    this.logged = true;
                    // window.location = callbackUrl;
                },
            });
        }
    };

    this.clearToken = () => {
        localStorage.removeItem(this.session_name);
        sessionStorage.removeItem(this.session_name);
    };

    this.logout = () => {
        $.confirm("Are you sure you want to log out?", {
            accept: () => {
                this.clearToken();
                window.location.href = '/logout';
            },
            acceptText: "Log Me out",
        });
    };

    this.login = (data) => {
        return http({
            url: "/dologin",
            data: data,
            success: (res) => this.storeTokenFromResponse(res),
            error: (err) => {
                if (typeof err.cause === "string" && err.cause === "2fa") {
                    $scope.otp_user_email = err.user_email;
                    $scope.route("2fa");
                    $scope.$apply();
                }
            }
        });
    };
});
