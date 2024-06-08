/**
 * RootController
 * Controller responsible for managing global application state and utility functions.
 *
 * @param {Object} $scope AngularJS scope object.
 */
app.controller("RootController", [
    "$scope",
    "$window",
    function ($scope, $window) {
        // Initialize scope variables
        $scope.open = false;
        $scope.alert = false;
        $scope.opensidebar = false;
        $scope.visible_canvas = null;
        $scope.visible_popend = "";
        $scope.popends = [];
        $scope.buttonStates = {};
        $scope.currentState = "initial";
        $scope.data = null;
        $scope.userRole = "";
        $scope.errors = {};
        $scope.cache_memory = {};
        $scope.active_route = "index";
        $scope.token = null;
        $scope.colorScheme = null;

        $scope.logout = () => {
            $.confirm('Are you sure you want to log out?', {
                accept: () => {
                    sessionStorage.removeItem("access_token");
                    localStorage.removeItem("access_token");
                    window.location.href = "/logout";
                },
                acceptText: 'Log Me out',
            });
        }

        $scope.loadAnnouncements = () => {
            api(
                '/app/annoucement/stream',
                res => {
                    
                    if (res.length > 0) {
                        for(let i = 0; i < res.length; i++) {
                            const current = res[i];
                            let options = {type:'alert', title: 'Notice', denyText: 'Close', accept:async()=>{}};

                            if (current.id) {
                                options = {
                                    type: 'confirm',
                                    acceptText: 'Mark as Seen',
                                    title: 'Announcement',
                                    accept: async () => {
                                        return api(
                                            '/app/annoucement/markAsSeen'
                                        )
                                    }
                                }
                            }
                            if (current.message) {
                                $.confirm(current.message, options)
                            }
                        }
                    }
                }
            );
        }

        $scope.get_index = (items, value, holder = 'id') => {
            for (var i = 0; i < items.length; i++) {
                if (items[i][holder] === value) {
                    return i;
                }
            }
            return -1;
        };

        $scope.Response = (response, element) => {
            response = parseResponse(response);
            if (!element) {
                return response;
            }

            return response[element];
        }

        $scope.range = (counter) => {
            let arr = [];
            for(var i = 0; i < counter; i++) {
                arr.push(i);
            }
            return arr;
        }


        /**
     * Generate academic sessions.
     *
     * @param int|null $from The starting year.
     * @param int|null $to The ending year.
     * @param string $separator The separator between years.
     * @return array The generated academic sessions.
     */
    $scope.generateSessions = (gap, from, to) => {
        let sessions = [];

        const date = new Date();
        if (typeof to != 'number') {
            to = date.getFullYear();
        }
        if (typeof gap != 'number') {
            gap = 1;
        }

        // Generate sessions for the last ten years
        if (typeof from != 'number') {
            to = date.getFullYear();
            from = to - 5;
        }

        from = Math.min(from, to);
        to = Math.max(from, to);

        if (to == from) {
            to += 1;
        }

        const diff = to - from;
        for (let i = 0; i < diff; i++) {
            const startSemester = from + i;
            const endSemester = from + i + gap;

            sessions.push(startSemester + '/' + endSemester);
        }

        return sessions;
    }

        $scope.countDown = (callback, time) => {
            const padZero = (num) => (num < 10 ? "0" + num : num);
            const prepareTime = (time) => {
                let remaining;
                const hours = Math.floor(time / 3600);
                remaining = time % 3600;
                const minutes = Math.floor(remaining / 60);
                const seconds = remaining % 60;
                let column = "";
                let text = null;
                if (hours > 0) {
                    column += `:${padZero(hours)}`;
                    text = hours === 1 ? "hr" : "hrs";
                } else if (minutes > 0) {
                    text = minutes === 1 ? "min" : "mins";
                } else if (seconds > 0) {
                    text = seconds === 1 ? "sec" : "secs";
                }

                column += `:${padZero(minutes)}`;
                const completeColumn = `${padZero(hours)}:${padZero(
                    minutes
                )}:${padZero(seconds)}`;

                column += `:${padZero(seconds)}`;
                column = column.replace(/^:/, "");

                return {
                    minutes,
                    hours,
                    seconds,
                    column,
                    text,
                    completeColumn,
                };

                return column.replace(/^:/, "");
            };

            callback(prepareTime(time));

            let timer = 1000;

            if (time > 59) {
                timer = 60000;
            }

            let interval = setInterval(() => {
                time -= 1;
                timer = time <= 59 ? 1000 : 60000;

                callback(prepareTime(time));
                if (time < 1) {
                    clearInterval(interval);
                    interval = null;
                }
                $scope.$apply();
            }, 1000);
        };

        $scope.refreshToken = (token, persistent = false) => {
            $scope.api(
                "/app/auth/refresh_token",
                { token: token },
                (response) => {
                    if (persistent) {
                        localStorage.setItem(
                            "access_token",
                            response.access_token
                        );
                    }
                    sessionStorage.setItem(
                        "access_token",
                        response.access_token
                    );
                    window.location.href = "/home";
                },
                (err) => {
                    localStorage.removeItem("access_token");
                    localStorage.removeItem("access_token");
                }
            );
        };

        // $scope.route = (route = "index") => {
        //     $scope.active_route = route;
        //     Location.set({ view: route });
        // };
        $scope.route = function (route) {
            $scope.active_route = route;
            $window.history.pushState({ route: route }, "", "");
        };
        $window.onpopstate = function (event) {
            $scope.$apply(function () {
                if ($scope.active_route !== "index") {
                    $scope.active_route = "index";
                }
            });
        };

        $scope.is_active_route = (route) => {
            if (route === "index" && !$scope.active_route) {
                return true;
            }
            return $scope.active_route === route;
        };

        $scope.confirmLogout = () => {
            sessionStorage.removeItem("access_token");
            localStorage.removeItem("access_token");
            window.location.href = "/logout";
        };

        $scope.cache = (name, value) => {
            if (!name) {
                return null;
            }
            if (typeof value === "undefined") {
                return $scope.cache_memory[name];
            }
            $scope.cache_memory[name] = value;
        };

        $scope.registerErrors = (error) => {
            $scope.errors = { ...$scope.errors, ...error };
        };
        $scope.formatDate = (date) => {
            const dateObj = new Date(date);
            const dd = dateObj.getDate();
            const mm = dateObj.getMonth();
            const yyyy = dateObj.getFullYear();

            return `${dd}/${mm}/${yyyy}`;
        };

        $scope.api = async (page, data, callbackOrInit, errorCallback) => {
            let init = {};
            let callback = () => {};
            $("#isLoading").addClass("show");

            if (typeof callbackOrInit === "function") {
                callback = callbackOrInit;
            } else if (typeof callbackOrInit === "string") {
                init = callbackOrInit;
            }

            if ("success" in init && typeof init.success === "function") {
                callback = init.success;
            }
            if ("error" in init && typeof init.error === "function") {
                errorCallback = init.error;
            }
            if (typeof errorCallback !== "function") {
                errorCallback = () => {};
            }

            init = {
                method: "POST",
                cache: "no-cache",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "applicatin/json",
                },
                ...init,
            };

            let url = `/api${page}`;

            if ("type" in init) {
                init.method = init.type;
                delete init.type;
            }

            const csrfToken = await getCSRFToken();
            if (csrfToken && init.method === "POST") {
                init.headers["X-CSRF-TOKEN"] = csrfToken;
            }

            if (
                typeof localStorage !== "undefined" &&
                localStorage !== null &&
                localStorage.getItem("access_token")
            ) {
                init.headers.Authorization = `Bearer ${localStorage.getItem(
                    "access_token"
                )}`;
            }

            if (["GET", "HEAD"].includes(init.method) && data) {
                const newUrl = new URL(url, fetchApi.baseURL);
                const params = newUrl.searchParams;

                for (const query in data) {
                    params.append(query, data[query]);
                }
                url = newUrl.toString();
            } else {
                init.body = JSON.stringify(data);
            }

            const processData = (response) => {
                return new Promise(async (resolve, reject) => {
                    var data, process;
                    data = process = await response.text();
                    try {
                        return resolve(JSON.parse(process));
                    } catch (e) {
                        return reject(data);
                    }
                });
            };
            return new Promise((resolve, reject) => {
                try {
                    fetch(url, init)
                        .then(async (response) => {
                            const data = processData(response);
                            $("#isLoading").removeClass("show");

                            if (!response.ok) {
                                try {
                                    const res = await data;
                                    return await Promise.reject(res);
                                } catch (err) {
                                    throw err;
                                }
                            }

                            try {
                                return await Promise.resolve(await data);
                            } catch (error) {
                                return await Promise.reject(error);
                            }
                        })
                        .then((response) => {
                            ENV.log(response);
                            $("#isLoading").removeClass("show");

                            if (typeof callback === "function") {
                                callback(response);
                            }
                            if ("success" in response) {
                                toastr.success(response.success);
                            }

                            $scope.$apply();

                            if ("redirect" in response) {
                                setTimeout(() => {
                                    window.location.href = response.redirect; // Redirect user
                                }, 2000);
                            }

                            // return Promise.resolve(response);
                            return resolve(response);
                        })
                        .catch(async (err) => {
                            errorCallback(err);

                            ENV.error(err);

                            if (typeof err === "object" && err !== null) {
                                if (
                                    "errors" in err &&
                                    typeof err.errors === "object" &&
                                    err.errors !== null
                                ) {
                                    const errors = Object.values(err.errors);
                                    toastr.error(errors[0]);
                                    $scope.registerErrors(errors);
                                } else if (
                                    "error" in err &&
                                    err.error.length > 0
                                ) {
                                    toastr.error(err.error);
                                }
                                if ("redirect" in err) {
                                    setTimeout(() => {
                                        window.location.href = err.redirect;
                                    }, 2000);
                                }
                            }
                            $scope.$apply();

                            return reject(err);
                        });
                } catch (e) {}
            });
        };

        /**
         * popUp
         * Opens a popup with the specified name.
         *
         * @param {string} name The name of the popup.
         */
        $scope.popUp = (name) => {
            $scope.popends.push(name);
            $scope.visible_popend = name;
        };

        /**
         * popDown
         * Closes the popup with the specified name.
         *
         * @param {string} name The name of the popup.
         */
        $scope.popDown = (name) => {
            const index = $scope.popends.indexOf(name);
            if (index !== -1) {
                $scope.popends.splice(index, 1);
                if ($scope.popends.length > 0) {
                    $scope.visible_popend =
                        $scope.popends[$scope.popends.length - 1];
                } else {
                    $scope.visible_popend = "";
                }
            }
        };

        /**
         * popClear
         * Clears all active popups.
         */
        $scope.popClear = () => {
            $scope.visible_popend = "";
            $scope.popends = [];
        };

        /**
         * popend
         * Opens a popup with the specified name.
         *
         * @param {string} name The name of the popup.
         */
        $scope.popend = (name) => {
            $scope.popUp(name);
            //$scope.visible_popend = name;
        };

        /**
         * is_popend
         * Checks if a popup with the specified name is active.
         *
         * @param {string} name The name of the popup.
         * @returns {boolean} True if the popup is active, otherwise false.
         */
        $scope.is_popend = (name) => {
            return $scope.popends.includes(name);
        };

        $scope.close_popend = (name) => {
            if ($scope.visible_popend == name) {
                $scope.visible_popend = null;
            }
        };

        /**
         * Function to display off-canvas content
         *
         * displayOffCanvas
         * Sets the visible canvas to the specified name.
         *
         * @param {string} name The name of the canvas to display.
         */

        $scope.displayOffCanvas = (name) => {
            $scope.visible_canvas = name;
        };

        /**
         * Function to check if a canvas is open
         *
         * canvasOpen
         * Checks if the specified canvas is currently open.
         *
         * @param {string} name The name of the canvas to check.
         * @returns {boolean} True if the canvas is open, otherwise false.
         */
        $scope.canvasOpen = (name) => {
            return $scope.visible_canvas === name;
        };

        $scope.popup_wrapper = "";

        /**
         * Function to toggle the profile card
         *
         * toggleProfileCard
         * Toggles the visibility of the profile card.
         */
        $scope.toggleProfileCard = function () {
            $scope.open = !$scope.open;
        };

        $scope.setState = (state) => {
            $scope.currentState = state;
            $scope.$apply();
        };

        /**
         * Function to set the state of a button
         *
         * setButtonState
         * Sets the state of a button.
         *
         * @param {string} name The name of the button.
         * @param {string|number} state The state to set for the button.
         */
        $scope.setButtonState = (name, state) => {
            $scope.currentState = state;
            if (!name) {
                return;
            }
            if (typeof state === "string") {
                const states = {
                    error: 0,
                    initial: 1,
                    sending: 2,
                    sent: 3,
                };
                let normalValue = state.toLowerCase();
                state = 1;
                if (normalValue in states) {
                    state = states[normalValue];
                }
            }

            $scope.buttonStates[name] = state;
            $scope.currentState = state;
        };

        /**
         * Function to handle output messages
         *
         * output
         * Handles output messages and executes callback functions.
         *
         * @param {Object} obj The output object containing message and type.
         * @param {Function} callback The callback function to execute.
         * @param {string} type The type of message (success, error, warning, info).
         */
        $scope.output = (obj, callback, type) => {
            let timeout = type === "info" ? 4000 : 1000;
            let types = ["success", "warn", "error", "info"];

            if (typeof obj === "object" && obj !== null) {
                const timer = setTimeout(() => {
                    if ("success" in obj) {
                        callback(obj.success);
                        toastr.success(obj.success);
                    } else if ("error" in obj) {
                        callback(obj.error);
                        toastr.error(obj.error);
                    } else {
                        callback();
                    }

                    if ("redirect" in obj) {
                        setTimeout(() => {
                            //    window.location.href = obj.redirect;
                        }, 5000);
                    }
                    clearInterval(timer);
                }, timeout);
            }
        };

        $scope.validateInput = (event) => {
            if ($scope.pattern && $scope.valid && $scope.pattern.length > 0) {
                try {
                    const regexp = new RegExp("^" + $scope.pattern + "$");
                    $scope.valid = regexp.test(event.target.value);
                } catch (err) {}
            }
        };

        $scope.print = () => {
            window.print(document.body);
        };

        $scope.maxLength = (event, max) => {
            let value = event.target.value;
            if (max) {
                value = value.slice(0, max - 1);
                event.target.value = value;
            }
        };

        /**
         * Function to close the sidebar
         *
         * closeSidebar
         * Closes the sidebar.
         */
        $scope.closeSidebar = () => {
            $scope.opensidebar = false;
        };

        /**
         * Function to open the sidebar
         *
         * openSidebar
         * Opens the sidebar.
         */
        $scope.openSidebar = () => {
            $scope.opensidebar = true;
        };

        $scope.enterSidebar = (event) => {
            const target = angular.element(event.target)
            const sidebar = angular.element(".sidebar");

            if(!target.is('.sidebar-footer') && target.closest('.sidebar-footer').length === 0) {
                sidebar.addClass("sidebar-hovered");
                $scope.openSidebar();
            }
        };

        $scope.leaveSidebar = (event) => {
            const sidebar = angular.element(".sidebar");

            if (
                sidebar.hasClass("sidebar-hovered") &&
                !sidebar.is(".sidebar-maximized")
            ) {
                sidebar.removeClass("sidebar-hovered");
                $scope.closeSidebar();
            }
        };

        /**
         * Function to toggle the sidebar
         *
         * toggleSidebar
         * Toggles the visibility of the sidebar.
         */
        $scope.toggleSidebar = () => {
            $scope.opensidebar = !$scope.opensidebar;
            angular
                .element(".sidebar")
                .toggleClass("sidebar-minimized", !$scope.opensidebar);
            angular
                .element(".sidebar")
                .toggleClass("sidebar-maximized", $scope.opensidebar);
        };

        /**
         * Function to check a radio input
         *
         * @method checkRadio
         * Checks the specified radio input and unchecks others with the same name.
         *
         * @param {Event} event The event object containing the radio input.
         */
        $scope.checkRadio = (event) => {
            return;
            let element, parent;
            parent = element = $(event.target);
            if (!element.is(".custom-radio")) {
                parent = element.closest(".custom-radio");
            }

            let radio = parent.find(`input[type=radio]`);
            const name = radio.attr("name");

            let selector = `input[name='${name}'][type=radio]`;

            let find = $(selector);
            let form = parent.closest("form");

            if (form.length > 0) {
                find = form.find(selector);
            }

            if (find.length > 0) {
                find.each(function () {
                    if (!$(this).is(radio)) {
                        $(this).prop("checked", false);
                    }
                });
            }

            radio.attr("checked", true);
        };

        /**
         * Function to toggle the application theme
         *
         * @method toggleTheme
         * Toggles the application theme between light and dark mode.
         */
        $scope.toggleTheme = () => {
            $scope.theme = $scope.theme === 'dark' ? 'light':'dark';
            localStorage.setItem("colorScheme", $scope.theme);
        };
        

        $scope.loadTheme = () => {

        
            $scope.colorScheme = localStorage.getItem('colorScheme') || 'system';
            
            let mode = 'light';
            if ($scope.colorScheme === 'dark' || ($scope.colorScheme === 'system' && "function"==typeof window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)"))) {
                mode = 'dark';
            }
            $scope.system_detect = "function"==typeof window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)") ? 'dark':'light';
            $scope.theme = mode;
        }

        $scope.saveTheme = (theme) => {
            if (theme && ['dark', 'light', 'system'].includes(theme)) {  
                localStorage.setItem('colorScheme', theme);
                $scope.loadTheme();
            }
        }

       

        /**
         * Function to get the index of object item
         *
         * @method findIndex
         * @param {Object} object  Object to search for index in
         * @param {Function} callback function to test for conditionality
         * @return {Integer} Index of object item in index
         */
        $scope.findIndex = (obj, callback) => {
            for (let i = 0; i < obj.length; i++) {
                if (callback(obj[i])) {
                    return i;
                }
            }
            return -1;
        };

        $scope.autoLogin = () => {
            // alert(localStorage.getItem('access_token'));
            const session = sessionStorage.getItem("access_token");
            const local = localStorage.getItem("access_token");
            let token = null;
            let persist = false;

            if (session) {
                token = session;
            } else if (!session && local) {
                token = local;
                persist = true;
            }

            if (token) {
                // $scope.refreshToken(token, persist);
            }
        };

        $scope.viewCourse = (course) => {
            console.log(course);
            $scope.view_course = course;
            $scope.popUp('view_course');
        };

        $scope.loadConfigurations = () => {
            $scope.api(
                "/app/admin/session/show",
                {},
                (res) => ($scope.config = res)
            );
        };

        $scope.loadAssignableClassNames = () => {
            api(
                "/class/generate_name",
                (res) => {
                    $scope.assignable_class_names = res;
                }
            );
        }

        /**
         * Function to initialize the application
         *
         * @method init
         * Initializes the application by setting the dark mode theme based on the user's preference.
         */

        $scope.init = (userRole, token) => {
            $scope.token = token;
            $scope.userRole = userRole;

            $scope.loadTheme();
            $scope.loadAssignableClassNames();
            $scope.autoLogin();

            $scope.loadConfigurations();
            $scope.loadAnnouncements();
        };
    },
]);

app.directive('toggle_mode', function(){
    return {
        restrict: 'A',
        controller: 'RootController',
        templateUrl: '/components/dark_mode_toggler.html',
        link: function(scope, element, attr) {
            
        }
    }
})

app.directive("infiniteScroll", function () {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            element.bind("scroll", function () {
                var raw = element[0];
                if (
                    Math.ceil(raw.scrollTop + raw.offsetHeight) >=
                    raw.scrollHeight
                ) {
                    scope.$apply(attrs.infiniteScroll);
                }
            });
        },
    };
});

app.run(function ($rootScope) {
    $rootScope.model = { id: 2 };
}).directive("convertToNumber", function () {
    return {
        require: "ngModel",
        link: function (scope, element, attrs, ngModel) {
            ngModel.$parsers.push(function (val) {
                return parseInt(val, 10);
            });
            ngModel.$formatters.push(function (val) {
                return "" + val;
            });
        },
    };
});

app.controller("CacheController", [
    "$scope",
    "$cacheFactory",
    function ($scope, $cacheFactory) {
        $scope.keys = [];
        $scope.cache = $cacheFactory("cacheId");
        $scope.put = function (key, value) {
            if (angular.isUndefined($scope.cache.get(key))) {
                $scope.keys.push(key);
            }
            $scope.cache.put(key, angular.isUndefined(value) ? null : value);
        };
    },
]);
