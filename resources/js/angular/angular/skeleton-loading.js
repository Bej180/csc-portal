/**
 * resultTableSkeleton Directive
 * Directive for rendering a result table skeleton.
 */

app.directive('resultTableSkeleton', function(){
  return {
    templateUrl: '/components/results.html'
  }
});



app.directive("profileSkeleton", function () {
  return {
      templateUrl: "/components/skeletons/profile.html",
  };
});

app.directive("viewStudentSkeleton", function () {
  return {
      templateUrl: "/components/skeletons/student-profile.html",
  };
});


app.directive('adminClassesLoading', function() {
  return {
    templateUrl: '/components/skeletons/admin-classes.html',
  }
})




