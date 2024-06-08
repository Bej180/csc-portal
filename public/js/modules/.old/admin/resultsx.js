
app.controller("AwaitingResultsController", function($scope) {
  $scope.active = Location.get("level", 200);
  $scope.awaitingResults = [];
  $scope.selectAll = false;
  $scope.previousCode = null;

  $scope.loadLevel = (level) => {
    $scope.active = level;
    Location.set({level});
    $scope.init();
  }
  $scope.shouldApplyThickBorder = code => {
      if ($scope.previousCode !== code) {
          $scope.previousCode = code; 
          return true; 
      } else {
          return false;
      }
  };

  $scope.toggleSelect = () => {
    $scope.selectAll = !$scope.selectAll;
  }

  $scope.init = () => {
    $scope.awaitingResults = [];
    const level = $scope.active;
    api('/fetchawaiting-results', {
      level
    })
    .then(response => {
      $scope.awaitingResults = response;
      console.log(response);
      $scope.$apply();
    }).catch(e => console.log(e))
  }


  $scope.processGrade = score => {
    let grade = '';
    if (score > 70) { grade = 'A'; }
    else if (score > 60) { grade = 'B'; }
    else if (score > 50) { grade = 'C'; }
    else if (score > 45) { grade = 'D'; }
    else if (score > 39) { grade = 'E'; }
    else if (score <= 39) { grade = 'F'; }
    return grade;
  }


});

app.controller('ResultController', function($scope){
  $scope.course = Location.get('course');
  $scope.session = Location.get('session');
  $scope.semester = Location.get('semester');
  $scope.grades = [];
  $scope.class_id = null;

  $scope.setClass = (event) => {
    console.log(event,$scope.class_id)
  }
  $scope.init = () => {
    setInterval(() => {
      $scope.uploadBtn = $scope.colors[$scope.current];
      $scope.current = $scope.current + 1;
      if ($scope.current >= $scope.colors.length) {
        $scope.current = 0;
      }
    }, 5000);

    setTimeout(() => {
      const dataGrade = $('[ng-data-grade]').length;
      const gradeA = $('[ng-data-grade=A]').length;
      const  gradeB = $('[ng-data-grade=B]').length;
      const gradeC = $('[ng-data-grade=C]').length;
      const gradeD = $('[ng-data-grade=D]').length;
      const gradeE = $('[ng-data-grade=E]').length;
      const gradeF = $('[ng-data-grade=F]').length;
      const undecided = dataGrade - gradeA - gradeB - gradeC - gradeD - gradeE - gradeF;
      $scope.grades = {};
      $scope.grades['A'] = gradeA;
      $scope.grades['B'] = gradeB;
      $scope.grades['C'] = gradeC;
      $scope.grades['D'] = gradeD;
      $scope.grades['E'] = gradeE;
      $scope.grades['F'] = gradeF;
      $scope.grades['undecided'] = undefined;



    }, 0);
  }

  $scope.updateGrade = (series, grade) => {
    $scope.grades[series] = grade;
  }

  $scope.getGrades = () => {
    if (typeof $scope.grades == 'object' && $scope.grades) {
      return $scope.grades;
    }
    // Count occurrences of each grade
    const gradeCounts = $scope.grades.reduce((counts, currentGrade) => {
      counts[currentGrade] = (counts[currentGrade] || 0) + 1;
      return counts;
    }, {});

    // Create newGrade object
    const newGrade = {
      A: 0,
      B: 0,
      C: 0,
      D: 0,
      E: 0,
      F: 0,
    };

    for (const key in gradeCounts) {
      newGrade[key] = gradeCounts[key];
    }
    
    return newGrade;
  }

});






const Integer = (str) => {
  let value = parseInt(str);
  if (isNaN(value)) {
    value = 0;
  }
  return value;
}

app.controller('ResultSummerController', function($scope) {
  $scope.success = null;
  // $scope.test = null;
  // $scope.lab = null;
  // $scope.exam = null;
  // $scope.score = null;
  // $scope.grade = null;


  $scope.parseInt = function(value) {
    let parsed = parseInt(value);
    if (isNaN(parsed)) {
      return null;
    }
    return parsed;
  }
  

  $scope.onKeyUp = function(event) {
    if (event.target.value.length > 2) {
      event.target.value = event.target.value.slice(0,2);
    }
    
    
    $scope.score = Integer($scope.test) + Integer($scope.lab) + Integer($scope.exam);
    if (isNaN($scope.score)) {
      $scope.score = '';
      $scope.grade = '';
    }
    
    
    switch(true) {
      case $scope.score >= 70: $scope.grade = 'A';break;
      case $scope.score >= 60: $scope.grade = 'B';break;
      case $scope.score >= 50: $scope.grade = 'C';break;
      case $scope.score >= 45: $scope.grade = 'D';break;
      case $scope.score >= 40: $scope.grade = 'E';break;
      default: $scope.grade = 'F';
    }

    const wrapper = $(event.target).closest('tr[data-series]');
    if (wrapper.length > 0) {
      const series = wrapper.data('series');
      const grade = wrapper.find('td.grade');

      $scope.updateGrade(series, grade.text().trim());

    }

    
    
  }

  $scope.onInput = function(event) {
    alert(1);
    if (event.target.value.length > 2) {
      event.target.value = event.target.value.slice(0,2);
    }
  }



  $scope.saveResult = (event) => {
    event.preventDefault();

    const parent = event.target.closest('tr');
    const elements = parent.querySelectorAll('#reg_no,#course_id,#level,#semester,#session,#test,#exam,#lab,#score,#grade');
    let data = {};

    elements.forEach(element => {
      let value;
      if (element.tagName === 'INPUT') {
        value = element.value.trim();
      }
      else {
        value = element.innerText.trim();
      }
      const id = element.getAttribute('id');
      data[id] = value;
    });
    
    api('/result/add', data)
    .then(response => {
      
      $scope.remark = 'PENDING';
      $scope.success = !($scope.success);
      $scope.$apply();
      console.log(response)
      
    })
    .catch(err => console.log(err));
    console.log({data});
    


  }

});

app.directive('myButton', function() {
  return {
    restrict: 'A',
    link: function(scope, element, attrs) {
      element.on('click', function() {
        scope.$apply(function() {
          scope.sendRequest();
        });
      });
    }
  };
});