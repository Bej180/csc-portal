@php
    use App\Models\Course;

    $classes = \App\Models\Admin::academicSets();

@endphp
<x-template nav="courses" route="/admin/course" title="Add Course">
    <div class="popup">


        <form action="/admin/advisor/add" class="popup-wrapper" method="POST" enctype="multipart/form-data">
            @csrf
            <h1 class="font-bold text-center popup-header">New Advisor</h1>
            <div class="popup-body">
                <x-drag-and-drop />


                <div class="grid grid-cols-3 gap-4">
                    <input type="text" class="input" name="firstname" placeholder="First Name" />
                    <input type="text" class="input" name="lastname" placeholder="Last Name" />
                    <input type="text" class="input" name="middlename" placeholder="Middle Name" />

                    <input type="text" class="input" name="phone" placeholder="Phone Number" />
                    <input type="text" class="input" name="email" placeholder="Email Address" />
                    <select name="gender" class="input">
                        <option value="">Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>




                <div class="flex gap-4 mt-4 ">
                    <div class="flex-1">
                        <input type="text" class="input w-full grid-rows-2" name="address"
                            placeholder="Contact Address" />
                    </div>
                    <div class="flex gap-3 relative">



                        <x-tooltip label="Date of Birth" direction='top'>
                            <input type="date" class="input grid-span-1" name="birthdate" />
                        </x-tooltip>

                        <select name="set_id" class="input data-load-classes"
                            ng-change="addClass=$event.target.value=='custom'" ng-model="set_id">

                            <option value="">--Select Class--</option>
                            <option id="addClass" value="custom">Create Class</option>

                        </select>






                    </div>
                </div>




            </div>
            <div class="flex gap-2 mt-5 justify-end popup-footer">
                <button type="button" ng-click="popdown($event)" class="btn-white" ng-click="addAdvisor=null">Cancel</button>
                <submit type="submit" class="btn-primary" value="Add Advisor"></submit>
            </div>










        </form>

    </div>
</x-template>
