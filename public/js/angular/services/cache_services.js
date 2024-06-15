app.service("CacheService", [
    "$cacheFactory",
    function ($cacheFactory) {
        this.keys = [];
        this.cache = $cacheFactory("cacheId");
        
        this.put = (key, value, expires = 0) => {
            if (!this.get(key)) {
                this.keys.push(key);
            }
            this.cache.put(key, angular.isUndefined(value) ? null : value, expires);
        };

        this.has = (key) => {
            return this.get(key);
        };


        this.get = (key, defaultValue = null) => {
            let value = this.cache.get(key);
            if (angular.isUndefined(value)) {
                return defaultValue;
            }
            return value;
        };
    

        this.Cache = (key, callback, expires = 60 * 60 * 24) => {
            let data;

            if (this.has(key)) {
                data = this.get(key);
            }
            else {
                data = callback();
                this.put(key, data, expires);
            }
            return data;
        };
    }
]);