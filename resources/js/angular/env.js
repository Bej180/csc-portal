const ENV = {
    debugger: true,
    log(){
        if (this.debugger) {
            console.log(...arguments);
        }
    },
    error(message) {
        if (this.debugger) {
            console.error(message);
        }
    },
};

window.ENV = ENV;
