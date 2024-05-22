app.service("CourseService", function() {
  var courses = {};
  var borrowedCourses = {};
  

  return {
    getCourses: _ => courses,

    _getCourses: _ => borrowedCourses,
    getUnits: function() {
      const args = Object.values(courses);
      
      if (args.length == 0) {
        return 0;
      }
      let n = 0;
      for (let i = 0; i < args.length; i++) {
       
        n+=parseInt(args[i]);
      }
      return n;
    },

    _removeCourse: course_id => {
      delete borrowedCourses[course_id];
      borrowedCourses = borrowedCourses;
    },

    _getUnits: function() {
      let n = 0;
      for(const i in borrowedCourses) {
        n += borrowedCourses[i].units;
      }

      return n;
    },
    addCourse: (course_id, course_units) => courses[course_id] = course_units,
    
    _addCourse: (course_id, course) => borrowedCourses[course_id] = course,
    
    toggle: function(course_id, course_units) {
      if (course_id in courses) {
        delete courses[course_id];
      }
      else {
        this.addCourse(course_id, course_units);
      }
      return this.getUnits();
    },
    _toggle: function(course) {
      if (typeof course == 'object' && course && 'id' in course) {
        const course_id = course.id;
        if (course_id in borrowedCourses) {
          delete borrowedCourses[course_id];
        }
        else {
          this._addCourse(course_id, course);
        }
      }
      
      return this._getCourses();;
    }
  };
});


app.controller("CourseRegistrationController", function($scope, CourseService) {
  $scope.minUnits=16;
  $scope.maxUnits=21;
  $scope.selection = [];
  $scope.selectedUnits = 0;
  $scope.borrowQuery = null;
 
  $scope.reg_courses = [];
  


  $scope.units= CourseService.getUnits();
  $scope.courses = CourseService.getCourses();
  $scope.borrowingCourses = [];
  $scope.borrowingUnits = 0;
  $scope.borrowedCourses = [];
  
  $scope.level = '';
  $scope.semester = '';
  $scope.session = '';


 

  

 


  $scope.displayCourses = () => {

      Location.set({
        level: $scope.regData.level,
        semester: $scope.regData.semester,
        session: $scope.regData.session
      });
  
      return $scope.api('/courses', $scope.regData, (response) => {
        $scope.reg_courses = response;
      });

  };
  

  
  
})

app.controller("CourseController", function($scope, CourseService) {


  $scope.push = function(event) {
    
    const parent = $(event.target).closest('tr');
    if (parent.length > 0) {
      const course_id = parent.data('course-id');
      const units = parseInt(parent.data('course-units'));
      const newUnits = CourseService.toggle(course_id, units);
      $scope.reload(newUnits);
    }
    
  };


  // $scope.selectCourse = (event) => {
  //   console.log(event);return;
  //   const parent = $(event.target).closest('tr');
  //   if (event.target.checked) {
  //     $scope.units += unit;
  //   }
  //   else {
  //     $scope.units -= unit;
  //   }
  //   parent.toggleClass('bg-green-50', event.target.checked);
  //   alert('xx')

  // }


  
});