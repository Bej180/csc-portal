<x-popend name="view_class" title="Class View" class="popstart">
  <div class="p-2">
    <div class="font-bold text-2xl">
        CSC <span ng-bind="displayClass.name"></span>
    </div>
    <div>
        Class Advisor: <b>{% displayClass.advisor.user.name %}</b>

    </div>
    <p>
        Graduation Year: <b ng-bind='displayClass.end_year'></b>
    </p>

    <div class="mt-5">
        <b>{% displayClass.students.length %} Students</b>
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
                    <tr ng-repeat="student in displayClass.students">
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
