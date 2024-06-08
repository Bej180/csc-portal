<x-popend dir="end" name="view_course">
  <div ng-show="view_course">

      <div class="flex-1">
          <div
              class="h-32 grid grid-cols-3 gap-2 border-slate-300 shrink-0 overflow-clip rounded-md mx-2.5 bg-white dark:bg-zinc-950">
              <img class="col-span-1 h-full object-cover" src="{{ asset('svg/course_image_default.svg') }}"
                  alt="default_course_img">

              <div class="col-span-2 flex flex-col justify-center">
                  <p class="text-lg font-semibold text-body-800 dark:text-white select-none whitespace-nowrap text-ellipsis overflow-hidden"
                      ng-bind="view_course.name">
                  </p>
                  <p class="flex items-center select-none">
                      <span class="text-sm text-body-400"
                          ng-bind="view_course.code">
                      </span>
                      <span class="vertical-divider"></span>
                      <span class="text-sm text-body-300  pl-2">
                          <span ng-bind="view_course.units"></span> units
                      </span>
                  </p>
              </div>
          </div>

          <div class="h-32 p-2 flex flex-col gap-2 shrink-0">
              <p class="text-sm font-semibold text-body-300 dark:text-white">
                  Marks distribution
              </p>
              
              <div ng-cloak ng-show="view_course.has_practical != 0" class="flex flex-col gap-3">
                  <div class="grid grid-cols-5">
                      <span class="col-span-1 p-3 flex center font-bold bg-[--orange-200]">
                          20%
                      </span>
                      <span class="col-span-1 p-3 flex center font-bold bg-[--blue-200]">
                          20%
                      </span>
                      <span
                          class="col-span-3 p-3 flex center font-bold bg-[--green-200] rounded rounded-r-full">
                          60%
                      </span>
                  </div>
                  <div class="flex items-center gap-4">
                      <div class="flex items-center gap-1 font-semibold text-body-500 text-sm">
                          <div class="w-3 h-3 bg-[--orange-200] rounded-full">
                          </div>
                          test
                      </div>
                      <div class="flex items-center gap-1 font-semibold text-body-500 text-sm">
                          <div class="w-3 h-3 bg-[--blue-200] rounded-full"></div>
                          practical
                      </div>
                      <div class="flex items-center gap-1 font-semibold text-body-500 text-sm">
                          <div class="w-3 h-3 bg-[--green-200] rounded-full"></div>
                          exam
                      </div>
                  </div>
              </div>
             
              <div ng-cloak ng-show="view_course.has_practical == 0" class="flex-col gap-3">
                  <div class="grid grid-cols-10">
                      <span class="col-span-3 p-3 flex center font-bold bg-[--orange-200]">30%</span>
                      <span
                          class="col-span-7 p-3 flex center font-bold bg-[--green-200] rounded-r-full">70%</span>
                  </div>
                  <div class="flex items-center gap-4">
                      <div class="flex items-center gap-1 font-semibold text-body-500 text-sm">
                          <div class="w-3 h-3 bg-[--orange-200] rounded-full"></div>
                          test
                      </div>
                      <div class="flex items-center gap-1 font-semibold text-body-500 text-sm">
                          <div class="w-3 h-3 bg-[--green-200] rounded-full"></div>
                          exam
                      </div>
                  </div>
              </div>
              
          </div>

          <div style="height: calc(100dvh-22.5rem);" class="p-2 flex flex-col gap-2 shrink-0">
              <p class="text-sm font-semibold text-body-300 dark:text-white">Course Description</p>
              <div class=" rounded overflow-y-auto p-1 text-sm text-body-500" ng-bind="view_course.outline">

              </div>
          </div>

      </div>
      <div>
        <button type="button" ng-if="config.account.role==='admin'" ng-click="EditCourse()" class="btn btn-primary">Course Details</button>
      </div>
  </div>
</x-popend>
