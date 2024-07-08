app.service("RouteService", function () {
    this.page_routes = [];
    this.key = window.location.href.replace(/([^a-zA-Z0-9_-]+)/, "");
    this.active_route = "index";
    this._scope = {};

    this.scope = (scope) => (this._scope = scope);

    this.parseKeep = (keep) => {
        if (Array.isArray(keep)) {
            if (keep.length < 1) {
                return {};
            }

            let obj = {};

            for (let i = 0; i < keep.length; i++) {
                const current = keep[i];

                obj[current] = this._scope[current];
            }
            return obj;
        } else if (typeof keep === "object" && keep !== null) {
            return keep;
        }
        return {};
    };

    this.route = (route, keep) => {
        keep = this.parseKeep(keep);

        if (!this.has("index") && route !== "index") {
            this.page_routes.push({
                key: "index",
                data: {
                    previous: null,
                    kept: {},
                },
            });
        }

        if (!this.has(route)) {
            const active = this.active();
            let route_obj = {
                key: route,
                data: {
                    previous: active,
                    kept: {},
                },
            };
            this.active_route = route;

            if (typeof keep === "object" && keep !== null) {
                if (keep.title) document.title = keep.title;

                route_obj.data.previous.kept = keep;
                // route_obj.data.kept = keep;
            }

            this.page_routes.push(route_obj);
        } else {
            this.active_route = route;
        }

        this.store();
    };

    this.index = (route_key) => {
        for (var i = 0; i < this.page_routes.length; i++) {
            if (this.page_routes[i].key === route_key) {
                return i;
            }
        }
        return -1;
    };

    this.back = (route_key) => {
        this.restorePrevious();
        if (route_key) {
            const index = this.index(route_key);

            if (index >= 0) {
                current = this.page_routes[index];
                this.page_routes.splice(index, 1);

                this.page_routes.push(current);
            }
        } else {
            const pagers = this.pagers();

            if (pagers.prev) {
                this.route(pagers.prev.key, pagers.prev);
            }
        }

        const active = this.active();

        if (active && active.kept && active.kept.scope) {
            console.log("kept", active.kept);
            const scope = active.kept.scope;
            delete active.kept.scope;

            Object.entries(active.kept).forEach((key, value) => {
                console.log({ value });
            });
        }
    };

    this.forward = () => {
        const pagers = this.pagers();
        if (pagers.next) {
            this.route(pagers.next.key, pagers.next);
        }
    };

    this.pagers = () => {
        const routes = this.page_routes;
        let currentIndex = routes.length - 1;
        if (this.active_route) {
            currentIndex = this.index(this.active_route);
        }
        console.log({ active: this.active_route });
        const previousIndex = currentIndex - 1;
        const nextIndex = currentIndex + 1;

        let obj = {
            next: null,
            prev: null,
        };
        console.log({
            previousIndex,
            currentIndex,
            items: this.page_routes,
            active: this.active_route,
        });
        if (previousIndex >= 0) {
            obj.prev = routes[previousIndex];
        }
        if (nextIndex < routes.length) {
            obj.next = routes[nextIndex];
        }
        return obj;
    };

    this.has = (route_key) => {
        return this.index(route_key) >= 0;
    };

    this.store = () => {
        return;
        let storage = Storage.getObj("page_routes");

        storage[this.key] = {
            routes: this.page_routes,
            active: this.active_route,
        };

        Storage.setObj("page_routes", storage);
    };

    this.clear = () => {
        Storage.drop("page_routes");

        this.route("index");
    };

    this.initiate = function (callback) {
        return this;
        let storage = Storage.getObj("page_routes", null);

        if (storage && this.key in storage) {
            this.page_routes = storage[this.key].routes;
            this.active_route = storage[this.key].active;
        }
        let routes = this.page_routes.filter(
            (route) => route.key === this.active_route
        );
        callback.call(this, routes.length > 0 ? routes[0] : {});

        return this;
    };

    this.restore = function (scope, callback) {
        // let storage = Storage.getObj('page_routes', null);

        // if (storage && this.key in storage) {
        //     this.page_routes = storage[this.key].routes;
        //     this.active_route = storage[this.key].active;
        // }
        let routes = this.page_routes.filter(
            (route) => route.key === this.active_route
        );
        callback.call(this, routes.length > 0 ? routes[0] : {});

        return this;
    };

    this.restorePrevious = function () {
        let routes = this.page_routes.filter(
            (route) => route.key === this.active_route
        );

        
        return this;
    };

    this.isActive = (route_key) => {
        return this.active_route === route_key;
    };

    this.active = () => {
        let routes = this.page_routes.filter(
            (route) => route.key === this.active_route
        );
        return routes.length > 0 ? routes[0] : nul;
    };
});
