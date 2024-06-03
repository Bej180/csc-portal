<x-popend name="display_class" title="Class View" class="popstart">
  <div class="p-2">
    <div class="font-bold text-2xl">
        CSC <span ng-bind="display_class.name"></span>
    </div>
    <div>
        Class Advisor: <b>{% display_class.advisor.user.name %}</b>

    </div>
    <p>
        Graduation Year: <b ng-bind='display_class.end_year'></b>
    </p>

    <div class="mt-5">
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
