/**
 * AngularJS Import and Module Definition
 * Imports the AngularJS library and defines the "cscPortal" module.
 */
import './angular.min.js';  // Import AngularJS library
window.angular = angular;   // Expose AngularJS globally
// Export the AngularJS module as "app" for use in other parts of the application
export const app = angular.module("cscPortal", []);
window.ngApp = app;         // Expose the module as "ngApp" globally
window.app = app;           // Expose the module as "app" globally
