<x-popend name="add_students">
    <form>
        <h1 class="font-bold">Add Student</h1>
        <div class="flex flex-col gap-4">

            <div class="md:flex gap-4">
                <input type="text" class="input row-auto md:w-[55%]" placeholder="Surname" ng-model="student.surname" />
                <input type="text" class="input" placeholder="Other Name" ng-model="student.othernames" />
            </div>
            <div class="flex flex-col">
                <input type="text" class="input mk-reg_no" placeholder="Reg Number" ng-model="student.reg_no"
                    maxlength="11" />
            </div>
            <div class="flex flex-col">
                <input type="text" class="input" placeholder="Email Address" ng-model="student.email" />
            </div>
            <div class="md:flex gap-4">
                <input type="text" class="input mk-phonex" placeholder="Phone Number" ng-model="student.phone"
                    input-mask="9999 999 9999" autocomplete="off" />
                <div>
                    <label>Gender</label>
                    <select name="gender" drop="bottom-right" class="input" ng-model="student.gender">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>
            <div>
                <textarea class="input h-24" ng-model="student.address" placeholder="Contact Address"></textarea>

            </div>
            <div class="md:flex gap-4">

                <input type="date" class="input grid-span-1" ng-model="student.birthdate" />

            </div>
        </div>

        <button class="btn btn-primary" controller="createStudentAccount(student)">Create Account</button>
    </form>
</x-popend>
