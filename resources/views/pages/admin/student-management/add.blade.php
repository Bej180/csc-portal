@php

$classes = \App\Models\AcademicSet::get()->unique('name');
@endphp 
<x-popend title="Add Student" name="addStudent">
    <form action="{{ route('admin_create_student') }}" method="POST" enctype="multipart/form-data" ng-init="academicClass = null;">
        @csrf

        <div class="my-5 font-semibold text-center">
            New Student Account
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-bottom nav-justified">
                <li class="nav-item"><a id="nextTab" class="nav-link active" href="#bottom-tab2" data-bs-toggle="tab">Class</a></li>
                <li class="nav-item"><a class="nav-link" href="#bottom-tab1" data-bs-toggle="tab" ng-class="{'cursor-not-allowed disabled':!student.class_id}">Personal
                        Details</a></li>
            </ul>
            <div class="tab-content pt-5">
                <div class="tab-pane active" id="bottom-tab2">
                    <div>

                        <div class="paragragh mb-3">Select Class for Student</div>

                        <select class="input" ng-model="student.class_id">
                            @foreach($classes as $class)
                            <option value="{{ $class->id}}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @include('pages.admin.class-management.add')
                </div>
                <div class="tab-pane" id="bottom-tab1">
                    <div class="lg:grid grid-cols-2 gap-4">

                        <input type="text" class="input row-auto" placeholder="First Name"
                            ng-model="student.firstname" />
                        <input type="text" class="input" placeholder="Last Name" ng-model="student.lastname" />
                        <input type="text" class="input" placeholder="Middle Name" ng-model="student.middlename" />
                        <input type="text" class="input" placeholder="Phone Number" ng-model="student.phone" />
                        <input type="text" class="input" placeholder="Email Address" ng-model="student.email" />
                        <select name="gender" class="input" ng-model="student.gender">
                            <option value="">Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="lg:grid grid-cols-2 gap-3 mt-4 ">
                        <input type="text" class="input w-full" ng-model="student.address"
                            placeholder="Contact Address" />

                        <input type="date" class="input grid-span-1" ng-model="student.birthdate" />
                        
                    </div>

                    <div class="flex gap-2 mt-5 justify-end popup-footer">
                        <button ng-disabled="!student.class_id" type="button" class="btn-primary" ng-click="createStudentAccount()">Create Student Account</button>
                    </div>

                </div>
                

                
            </div>
        </div>
       









    </form>
</x-popend>
