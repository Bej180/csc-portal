export default class Storage {
    static prefix = "";

    static init() {
        if (this.prefix.length === 0) {
            if (!this.has("session_id")) {
                const timestamp = Date.now();
                const randomPart = Math.floor(Math.random() * 1000000);
                const id = `${timestamp}_${randomPart}`;
                this.set("session_id", id);
            }
            this.prefix = this.get("session_id");
        }
    }

    static get(name) {
        const prefixedName = this.prefix + name;
        try {
            const storedData = localStorage.getItem(prefixedName);

            if (storedData) {
                const { value, expires } = JSON.parse(storedData);
                if (!expires || Date.now() <= new Date(expires)) {
                    return value;
                } else {
                    localStorage.removeItem(prefixedName);
                }
            } 
            return null;
                        
        } catch (error) {
           
        }

        const cookieParts = decodeURIComponent(document.cookie).split(";");
        for (let i = 0; i < cookieParts.length; i++) {
            let cookie = cookieParts[i].trim();
            if (cookie.startsWith(prefixedName + "=")) {
                const { value, expires } = JSON.parse(
                    cookie.substring(prefixedName.length + 1)
                );
                if (!expires || Date.now() <= new Date(expires)) {
                    return value;
                }
            }
        }

        return null;
    }

    static getObj(name, defaultValue = {}) {
        const value = this.get(name);
        if (!value) {
            return defaultValue;
        }
        try {
            return JSON.parse(value);
        } catch(e) {
            return defaultValue;
        }
    }

    static setObj(name, value) {
        this.set(name, JSON.stringify(value));
    }

    static set(name, value, expireDays) {
        const prefixedName = this.prefix + name;
        let expires = null;
        if (expireDays) {
            expires = new Date(Date.now() + expireDays * 24 * 60 * 60 * 1000);
        }

        try {
            localStorage.setItem(
                prefixedName,
                JSON.stringify({ value, expires })
            );
            return;
        } catch (error) {
            console.error("Error setting data in localStorage:", error);
        }

        if (!expireDays) {
            expireDays = 365;
        }

        expires = new Date(Date.now() + expireDays * 24 * 60 * 60 * 1000);

        const cookieValue = JSON.stringify({ value, expires });
        const encodedCookieValue = encodeURIComponent(cookieValue);
        let cookieFlags = `path=/; expires=${expires.toUTCString()}`;
        // Assume secure is always true for simplicity
        cookieFlags += "; Secure";
        document.cookie = `${prefixedName}=${encodedCookieValue}; ${cookieFlags}`;
    }

    static setAll(tokens, expireDays) {
        for (const [key, value] of Object.entries(tokens)) {
            this.set(key, value, expireDays);
        }
    }

    static remove(name) {
        const prefixedName = this.prefix + name;
        localStorage.removeItem(prefixedName);
        const cookieToDelete = `${prefixedName}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/`;
        document.cookie = cookieToDelete;
    }
    static drop(name) {
        this.remove(name);
    }

    static has(name) {
        const prefixedName = this.prefix + name;
        try {
            const storedData = localStorage.getItem(prefixedName);
            if (storedData) {
                const { expires } = JSON.parse(storedData);
                return !expires || Date.now() <= new Date(expires);
            }
        } catch (error) {
            console.error("Error checking data in localStorage:", error);
        }
        return false;
    }

    static clearAll() {
        for (const key in localStorage) {
            if (key.startsWith(this.prefix)) {
                localStorage.removeItem(key);
            }
        }

        const cookies = document.cookie.split(";");
        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].trim();
            if (cookie.startsWith(this.prefix + "=")) {
                document.cookie = `${cookie}; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/`;
            }
        }
    }
}
Storage.init();
window.Storage=Storage;