/**
 * Configuration Block
 * Configuration block for customizing AngularJS behavior.
 */
app.config(function ($httpProvider, $interpolateProvider) {
  // Change AngularJS interpolation symbols
  $interpolateProvider.startSymbol("{%");
  $interpolateProvider.endSymbol("%}");

  // Register authInterceptor as an HTTP interceptor
  $httpProvider.interceptors.push("authInterceptor");
});
