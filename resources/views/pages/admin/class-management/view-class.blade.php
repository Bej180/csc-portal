<x-popend name="display_class" title="Class View">
    <div class="p-2">
        <div class="font-bold text-2xl">
            CSC <span ng-bind="display_class.name"></span>
        </div>
        <div ng-if="display_class.advisor">
            Class Advisor: <b>{% display_class.advisor.user.name %}</b> <span 
                tooltip-title="Change Advisor" ng-click="toggleAdvisorSelector()" class="fa fa-edit"></span>

        </div>
        <form ng-if="!display_class.advisor || add_advisor" class="flex items-center gap-2 my-3">
            <div class="flex-1">
                <label>Select Class Advisor</label>
                <select class="!border-none input !rounded-l-md" ng-model="choose_advisor"
                    placeholder="Select Class Advisor">
                    @foreach ($staffs as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="button" controller="saveCourseAdvisor(display_class, choose_advisor)" ng-disabled="!choose_advisor"
                class="btn btn-primary btn-adaptive">Save</button>

        </form>

        <p>
            Graduation Year: <b ng-bind='display_class.end_year'></b>
        </p>

        <div ng-controller="ImportClassListController">
            <button type="button" ng-click="importClassList($event, display_class.id)" class="mt-5 btn btn-primary w-full">Import Students</button>
        </div>

        <div ng-if="display_class.students.length" class="mt-5">
            <b>{% display_class.students.length %} Students</b>
            <div class="card">
                <table class="responsive-table no-zebra">
                    <thead>
                        <tr>
                            <th class="text-center">S/N</th>
                            <th>Name</th>
                            <th>Reg No</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="student in display_class.students">
                            <td ng-bind="$index+1" class="text-center"></td>
                            <td ng-bind="student.user.name"></td>
                            <td ng-bind="student.reg_no" class="text-center"></td>
                        </tr>
                    </tbody>
                </table>
            </div>


        </div>
    </div>
</x-popend>
