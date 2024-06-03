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

app.config(['$sceDelegateProvider', function($sceDelegateProvider) {
  // We must add the JSONP endpoint that we are using to the trusted list to show that we trust it
  $sceDelegateProvider.trustedResourceUrlList([
    'self',
    'https://angularjs.org/**'
  ]);
}]);


app.config(['$httpProvider', function($httpProvider) {
  if (bearerToken) {
    $httpProvider.defaults.headers.common['Authorization'] = 'Bearer ' + bearerToken;
  }
  $httpProvider.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}]);
