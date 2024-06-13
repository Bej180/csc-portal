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
            api(
                "/authenticate",
                {
                    token,
                    callbackUrl
                },
                (res) => {
                    this.storeTokenFromResponse(res);

                    this.logged = true;
                    // window.location = callbackUrl;
                },
                (err) => {
                    // this.clearToken();
                }, {
                    headers: {
                        Authoritzation: 'Bearer '+token
                    }
                }
            );
        }
    };

    this.clearToken = () => {
        localStorage.removeItem(this.session_name);
        sessionStorage.removeItem(this.session_name);
    };

    this.logout = () => {
        $.confirm("Are you sure you want to log out?", {
            accept: () => {
                return api(
                    '/auth/logout', 
                    res => {
                        this.clearToken();
                    }, 
                );
            },
            acceptText: "Log Me out",
        });
    };

    this.login = (data) => {
        return api(
            "/dologin",
            data,
            (res) => this.storeTokenFromResponse(res),
            (err) => {
                if (typeof err.cause === "string" && err.cause === "2fa") {
                    $scope.otp_user_email = err.user_email;
                    $scope.route("2fa");
                    $scope.$apply();
                }
            }
        );
    };
});
