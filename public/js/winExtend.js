const winExtend = (obj) => {
  if (typeof obj === 'object' && obj !== null) {
    // Object entries [key, value]
    Object.entries(obj).forEach(([key, fnc]) => {
      if (typeof fnc === 'function') {
        window[key] = fnc;
      }
    });
  }
}
window.winExtend = winExtend;
export default winExtend;