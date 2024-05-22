
app.controller('StudentController', function($scope) {

    $scope.addStudentForm = null;
    $scope.student_id = Location.get('student_id');
    $scope.student = null;
    $scope.open = false;
    $scope.level = null;
    $scope.editStudent = null;

    $scope.show = (event) => {
      let element = $(event.target);
      if (!element.is('.student')) {
        element = element.closest('.student');
      }
      const student_id = element.attr('student_id');
     
      
      if (student_id) {
          $scope.student_id = student_id;
          api('/student', {student_id})
          .then(response => {
            $scope.student = response;
            const nameParts = response.user.name.split(" ");
            $scope.firstname = nameParts[0];
            $scope.lastname = nameParts.length > 1 ? nameParts[1] : '';
            $scope.middlename = nameParts.length > 2 ? nameParts[2] : '';
            $scope.$apply();
            console.log(response);
            Location.set({student_id});
          })
          .catch(async error => {
            const err = await error;
            console.log(err);
          });
      }
      
    }
    
    
    $scope.init = () =>{
      if ($scope.student_id) {
        api('/student', {student_id:$scope.student_id})
        .then(response => {
          $scope.student = response;
          $scope.$apply();
        })
        .catch(error => log(error));
    }
    }
    
    $scope.back = () => {
      $scope.student_id = null;
      $scope.student = null;
      Location.push('/admin/students');
    }


    $scope.openEditor = () => {
      $scope.editStudent=true;
    }
    $scope.closeEditor = () => {
      $scope.editStudent=false;
    }
    $scope.openForm = () => {
      $scope.addStudentForm=true;
    }
    $scope.closeForm = () => {
      $scope.addStudentForm=false;
    }

    
    

});


app.directive('viewStudentSkeleton', function() {
  return {
    template: `<div class="loading-skeleton flex flex-col lg:m-5 lg:p-8">
    <div class="flex flex-col lg:flex-row text-center justify-center gap-5 items-center lg:text-left lg:justify-start p-4">
      <div class="skeleton w-28 h-28 object-cover rounded-full"></div>
      <div class="flex flex-col gap-2">
        <span class="text-2xl lg:text-3xl font-bold mb-3 skeleton w-[150px]"></span>
        <span class="font-bold skeleton w-[100px]"></span>
      </div>
    </div>

    <div class="flex-1">
    
      <div class="p-4 my-2 flex flex-col">
        <span class="font-bold mb-4 skeleton w-[120px]"></span>
        <div class="flex flex-col lg:flex-row justify-between flex-wrap gap-3">
          <div class="flex lg:flex-col gap-3">
            <span class="skeleton w-[60px]"></span> 
            <span class="skeleton w-[100px]"></span>
          </div>


          <div class="flex lg:flex-col gap-3">
            <span class="skeleton w-[50px]"></span> 
            <span class="skeleton w-[90px]"></span>
          </div>

          
          <div class="flex lg:flex-col gap-3">
            <span class="skeleton w-[55px]"></span> 
            <span class="skeleton w-[90px]"></span>
          </div>


          <div class="flex lg:flex-col gap-3">
            <span class="skeleton w-[40px]"></span> 
            <span class="skeleton w-[30px]"></span>
          </div>

          

          <div class="flex lg:flex-col gap-3">
            <span class="skeleton w-[60px]"></span> 
            <span class="skeleton w-[150px]"></span>
          </div>
          
        

        </div>
      </div>


      <div class="p-4 my-2">
        <div class="font-bold mb-4 skeleton w-[60px]"></div>
        <div class="mt-2 grid grid-cols-1 lg:grid-cols-3 gap-3 lg:gap-5">
          <div class="overflow-hidden grid-span-1  rounded-md p-4 skeleton ">

          </div>

          <div class="overflow-hidden grid-span-1 rounded p-4 skeleton">
              
          </div>
          
  
          <div class="overflow-hidden grid-span-1 rounded p-4 skeleton h-20">
              
          </div>
        </div>
      </div>
    </div>

</div>`
  }
});