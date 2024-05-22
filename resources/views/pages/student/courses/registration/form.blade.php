
<div ng-if="reg_courses.length === 0" class=" place-items-center w-full h-avail grid place-content-center">
  <form class="popup-wrapper !w-[400px] relative" ng-action="displayCourses()">
      <div class="popup-header">
          Course Registeration
      </div>
      <div class="popup-body flex flex-col gap-3">
          <div>
              <label for="semester" class="font-semibold">Semester</label>
              <select placeholder="Select Semester" id="semester" ng-model="regData.semester" class="input ignore" placeholder="Select Semester">
                  <option value="HARMATTAN">
                      HARMATTAN</option>
                  <option value="RAIN">RAIN</option>
              </select>
          </div>
          <div>
              <label for="session" class="font-semibold">Session</label>
              <select placeholder="Select Session" drop="middle-center" ng-disabled="!regData.semester" id="session" ng-model="regData.session" class="input ignore">
                  @foreach ($sessions as $session)
                      <option value="{{ $session->name }}">{{ $session->name }}</option>
                  @endforeach
              </select>

          </div>
          <div>
              <label class="font-semibold">Level</label>
              <select placeholder="Select Level" drop="top" ng-disabled='!regData.session' ng-model="regData.level" class="input ignore">
                @foreach([100, 200, 300, 400, 500] as $level)
                <option value="{{ $level }}">{{ $level }}</option>
                @endforeach
              </select>
          </div>
      </div>
      <div class="popup-footer">
        <button type="submit" ng-disabled="!regData.level || !regData.semester || !regData.level" class="btn btn-primary">Fetch Courses</button> 
      </div>
  </form>
</div>