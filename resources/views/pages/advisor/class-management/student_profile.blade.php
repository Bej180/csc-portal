<x-popend name="show_student" title="Student Profile">
  <div class="flex flex-col gap-4">
      <fieldset class="p-fieldset">
          <legend class="p-fieldset-legend">
              <div class="flex items-center">
                  <span class="material-symbols-rounded">person</span>
                  <p>Student Details</p>
              </div>
          </legend>
          <div class="p-toggleable-content">
              <div class="p-fieldset-content">
                  <div class="md:flex md:items-center md:gap-2 md:flex-wrap">
                      <img src="/profilepic/{% show_student.id %}" alt="student-img"
                          class="w-24 aspect-square rounded-md">

                      <div class="flex flex-col gap-2">
                          <h1 class="font-bold lg:text-lg" ng-bind="show_student.name"></h1>

                          <p
                              class="text-sm lg:text-base text-[--highlight-text-color] rounded-full py-1 px-3">
                              Reg. Number: <span class="font-bold" ng-bind="show_student.reg_no"></span>
                          </p>

                          <p
                              class="text-sm lg:text-base text-[--highlight-text-color] rounded-full py-1 px-3">
                              Level: <span class="font-bold" ng-bind="show_student.level"></span></p>

                      </div>
                  </div>
              </div>
          </div>
      </fieldset>
      <fieldset class="p-fieldset " data-pc-name="fieldset" data-pc-section="root">
          <legend class="p-fieldset-legend" data-pc-section="legend">
              <div class="flex items-center"><span class="material-symbols-rounded">info</span>
                  <p>Personal Info</p>
              </div>
          </legend>
          <div class="p-toggleable-content">
              <div class="p-fieldset-content" data-pc-section="content">
                  <div class="flex flex-col gap-3 mt-2 md:mt-0">

                      <p
                          class="text-sm lg:text-base text-[--highlight-text-color] rounded-full py-1 px-3">
                          Date of Birth: <span class="font-bold" ng-bind="show_student.birthdate"></span></p>

                      <p
                          class="text-sm lg:text-base text-[--highlight-text-color] rounded-full py-1 px-3">
                          Sex: <span class="font-bold" ng-bind="show_student.gender"></span> </p>

                      <p
                          class="text-sm lg:text-base text-[--highlight-text-color] rounded-full py-1 px-3">
                          Email: <span class="font-bold" ng-bind="show_student.user.email"></span></p>

                      <p
                          class="text-sm lg:text-base text-[--highlight-text-color] rounded-full py-1 px-3">
                          Phone: <span class="font-bold" ng-bind="show_student.user.phone"></span></p>

                  </div>
              </div>
          </div>
      </fieldset>
      <fieldset class="p-fieldset " data-pc-name="fieldset" data-pc-section="root">
          <legend class="p-fieldset-legend">
              <div class="flex items-center"><span class="material-symbols-rounded">book_2</span>
                  <p>Academic Record</p>
              </div>
          </legend>
          <div class="p-toggleable-content">
              <div class="p-fieldset-content" data-pc-section="content">
                  <div class="flex flex-col gap-3 mt-2 md:mt-0">
                      <p
                          class="text-sm lg:text-base text-[--highlight-text-color] rounded-full py-1 px-3">
                          CGPA: <span class="font-bold" ng-bind="show_student.cgpa"></span></p>
                      <button class="btn btn-primary" type="button" ng-click="generateTranscript(show_student.reg_no)">Generate Transcripts</button>

                  </div>
              </div>
          </div>
      </fieldset>
  </div>


</x-popend>