<x-template title="Moderators - CSC Portal">

    <main class="w-dvw lg:w-full h-full overflow-y-scroll p-5 grid gap-5 md:grid-cols-2"
        ng-controller="AdminModeratorsController" ng-init="loadModeratorDashboard()">
        <div class="card p-component">
            <div class="card-body">
                <div class="card-caption">
                    <div class="card-title">H.O.D</div>
                </div>
                <div class="card-content">
                    <section class="overflow-y-scroll md:h-[calc(-12.5rem+100dvh)]">

                        <div class="grid place-items-center">
                            <div ng-if="!current_hod">
                                <img src="{{ asset('svg/no_courses.svg') }}" class="w-40 lg:w-52" />
                                <div class="text-zinc-300 text-2xl">
                                    NO HOD ADDED
                                </div>
                            </div>

                            <div class="" ng-if="current_hod">
                                <div class="flex flex-col gap-3 items-center md:flex-row">
                                    <img profile="current_hod"
                                        class="w-28 md:w-24 lg:w-28 aspect-square rounded-md object-cover" />
                                    <div class="text-center md:text-left">
                                        <h1 class="text-2xl font-semibold" ng-bind="current_hod.user.name"></h1>
                                        <p class="mt-1 text-lg px-2 py-1 bg-green-50/35 text-[--highlight-text-color]">
                                            HOD,
                                            CSC </p>
                                    </div>
                                </div>
                                <div class="horizontal-divider"></div>
                                <div class="grid grid-cols-5 mt-4">
                                    <p
                                        class="px-2 rounded-l-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-1">
                                        StaffID:</p>
                                    <p class="px-2 rounded-r-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-4"
                                        ng-bind="current_hod.staff_id"></p>

                                    <p
                                        class="px-2 rounded-l-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-1 bg-green-50/35 text-[--highlight-text-color]">
                                        Sex:</p>
                                    <p class="px-2 rounded-r-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-4 bg-green-50/35 text-[--highlight-text-color] font-semibold uppercase"
                                        ng-bind="current_hod.gender"></p>

                                    <p
                                        class="px-2 rounded-l-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-1">
                                        Phone:</p>
                                    <p class="px-2 rounded-r-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-4"
                                        ng-bind="current_hod.user.phone"></p>

                                    <p
                                        class="px-2 rounded-l-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-1 bg-green-50/35 text-[--highlight-text-color]">
                                        Address:</p>
                                    <p class="px-2 rounded-r-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-4 bg-green-50/35 text-[--highlight-text-color] font-semibold"
                                        ng-bind="current_hod.address"></p>

                                    <p
                                        class="px-2 rounded-l-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-1">
                                        Email:</p>
                                    <p class="px-2 rounded-r-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-4"
                                        ng-bind="current_hod.user.email"></p>
                                </div>
                            </div>

                            <div class="horizontal-divider"></div>
                            <div class="px-2 w-full" ng-init="changeHod=current_hod">
                                <button type="button"
                                    class="text-sm font-semibold flex items-center justify-between w-full"
                                    ng-click="changeHod=!changeHod" ng-disabled="!current_hod">

                                    <span class="font-[600]">Select H.O.D</span>
                                    <span ng-if="current_hod" class="fa"
                                        ng-class="{'fa-chevron-down':!changeHod, 'fa-chevron-up': chageHod}"></span>
                                </button>
                                <form ng-action="makeHOD(new_hod)" class="flex flex-col gap-2"
                                    ng-if="changeHod||!current_hod">
                                    <select drop="top" placeholder="Select staff" ng-model="new_hod">
                                        @foreach ($staffs as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->user->name }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary btn-adaptive" type="button" ng-disabled="!new_hod"
                                        ng-submit>Make HOD</button>
                                </form>
                            </div>
                        </div>

                        <div ng-if="current_hod">
                            <div class="horizontal-divider"></div>
                            <div>
                                <label class="text-sm font-semibold">Reset HOD's password</label>
                                <div class="info my-2">This will change the HOD's username and password to
                                    {%current_hod.gender.toLowerCase() == 'male' ? 'his' : 'her'%} staff ID.</div>
                                <button class="btn btn-warning w-full" type="button">Reset HOD Pasword</button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <div class="card p-component">
            <div class="card-body">
                <div class="card-caption">
                    <div class="card-title">Dean</div>
                </div>
                <div class="card-content">
                    <section class="overflow-y-scroll" style="height: calc(-12.5rem + 100dvh);">
                        <div class="" ng-if="current_dean">
                            <div class="flex flex-col gap-3 items-center md:flex-row">
                                <img profile="current_dean" class="w-28 md:w-24 lg:w-28 aspect-square rounded-md object-cover" />
                                <div class="text-center md:text-left">
                                    <h1 class="text-2xl font-semibold" ng-bind="current_dean.user.name"></h1>
                                    <p class="mt-1 text-lg px-2 py-1 bg-green-50/35 text-[--highlight-text-color]">
                                        Dean,
                                        SICT </p>
                                </div>
                            </div>
                            <div class="horizontal-divider"></div>
                            <div class="grid grid-cols-5 mt-4">
                                <p
                                    class="px-2 rounded-l-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-1">
                                    StaffID:</p>
                                <p class="px-2 rounded-r-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-4"
                                    ng-bind="current_dean.staff_id"></p>

                                <p
                                    class="px-2 rounded-l-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-1 bg-green-50/35 text-[--highlight-text-color]">
                                    Sex:</p>
                                <p class="px-2 rounded-r-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-4 bg-green-50/35 text-[--highlight-text-color] font-semibold uppercase"
                                    ng-bind="current_dean.gender"></p>

                                <p
                                    class="px-2 rounded-l-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-1">
                                    Phone:</p>
                                <p class="px-2 rounded-r-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-4"
                                    ng-bind="current_dean.user.phone"></p>

                                <p
                                    class="px-2 rounded-l-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-1 bg-green-50/35 text-[--highlight-text-color]">
                                    Address:</p>
                                <p class="px-2 rounded-r-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-4 bg-green-50/35 text-[--highlight-text-color] font-semibold"
                                    ng-bind="current_dean.address"></p>

                                <p
                                    class="px-2 rounded-l-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-1">
                                    Email:</p>
                                <p class="px-2 rounded-r-md py-1 md:py-2 text-center md:text-left col-span-5 md:col-span-4"
                                    ng-bind="current_dean.user.email"></p>
                            </div>
                        </div>
                        <div class="overflow-hidden" ng-init="collapsed=true">
                            <button ng-disabled="!current_dean" ng-click="collapsed=!collapsed"
                                class="w-full text-[--surface-500] flex items-center justify-between h-[3.2rem] py-4 px-4 cursor-pointer hover:bg-green-50/35 hover:text-[--surface-700] transition-all rounded-[--border-radius]">
                                <p class="font-[600]">New Dean</p>
                                <span class="fa"
                                    ng-class="{'fa-chevron-down':collapsed, 'fa-chevron-up': !collapsed}">
                                </span>
                            </button>
                            <div ng-if="!collapsed || !current_dean" class="transition-all overflow-hidden">
                                <form ng-action="addDean(new_dean)">
                                    <div class="p-2 grid gap-4">

                                        <div class="flex gap-4">
                                            <div class="w-[100px]">

                                                <input class="input" placeholder="Title" ng-model="new_dean.title">
                                            </div>
                                            <div class="flex-1">
                                                <input class="input w-full" placeholder="Full Name"
                                                    ng-model="new_dean.name">
                                            </div>
                                        </div>
                                        <div class="flex gap-4 items-end">
                                            <div class="flex-1">

                                                <input class="input w-full" placeholder="Staff ID"
                                                    ng-model="new_dean.staff_id">
                                            </div>
                                            <div>
                                                <label class="text-sm font-semibold">Select a Gender</label>
                                                <select ng-model="new_dean.gender">
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="md:flex gap-4">
                                            <div class="flex-1">
                                                <input class="input w-full" placeholder="Email Address"
                                                    ng-model="new_dean.email">
                                            </div>
                                            <div class="md:w-[150px]">
                                                <input class="input w-full mk-phone" placeholder="Phone Number"
                                                    ng-model="new_dean.phone">
                                            </div>
                                        </div>
                                        <div>
                                            <textarea class="input w-full h-20" placeholder="Contact Address" ng-model="new_dean.address"></textarea>
                                        </div>
                                        <div class="info text-sm">Dean's password will automatically
                                            be set to the phone number</div>
                                        <button class="btn btn-primary btn-adaptive" type="button" ng-submit>Add
                                            Dean</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div ng-if="current_dean">
                            <div class="horizontal-divider"></div>
                            <div>
                                <label class="text-sm font-semibold">Reset password</label>
                                <div class="info">This will change the Dean's password to
                                    their staff ID.</div>
                                <button class="btn btn-warning mt-4 w-full" type="button" aria-label="Reset">Reset
                                    Dean Password</button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>


</x-template>
