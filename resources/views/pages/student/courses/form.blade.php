
<div ng-if="reg_courses.length === 0" class=" place-items-center w-full h-avail grid place-content-center">
  <form class="popup-wrapper !w-[400px] relative">
      <div class="popup-header">
          Course Registeration
      </div>
      <div class="popup-body flex flex-col gap-3">
          <div>
              <label for="semester" class="font-semibold">Semester</label>
              <select placeholder="Select Semester" id="semester" ng-model="regData.semester" class="input" placeholder="Select Semester">
                  <option value="HARMATTAN">
                      HARMATTAN</option>
                  <option value="RAIN">RAIN</option>
              </select>
          </div>
          <div>
              <label for="session" class="font-semibold">Session</label>
              <select placeholder="Select Session" ng-disabled="!regData.semester" id="session" ng-model="regData.session" class="input">
                  @foreach ($sessions as $session)
                      <option value="{{ $session->name }}">{{ $session->name }}</option>
                  @endforeach
              </select>

          </div>
          <div>
              <label class="font-semibold">Level</label>
              <select placeholder="Select Level" relative=".popup-body" ng-disabled='!regData.session' ng-model="regData.level" class="input">
                
                <option value="100">100 LEVEL</option>
                <option value="200">200 LEVEL</option>
                <option value="300">300 LEVEL</option>
                <option value="400">400 LEVEL</option>
                <option value="500">500 LEVEL</option>
                
              </select>
          </div>
      </div>
      <div class="popup-footer">
        <button  controller="displayCourses()" type="button" ng-disabled="!regData.level || !regData.semester || !regData.level" class="btn btn-primary">Fetch Courses</button> 
      </div>
  </form>
</div>