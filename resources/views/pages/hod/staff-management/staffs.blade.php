<x-template nav="staff" ng-controller="StaffController" ng-init="init()">
    <main class="columns">
        <section class="p-5 w-full">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div class="flex items-center gap-5">
                            <span>Staff</span>
                            <span class="text-[1rem] text-[--primary-color]" ng-bind="staff_members.length||0"></span>
                        </div>
                    </div>
                </div>
                <div class="card-body" infinite-scroll="loadMore()">
                    <div class="flex items-center gap-3 pb-3">
                        <div class="input-group max-w-72">
                            <input class="input" placeholder="Search" ng-model="search_staff"/>
                            <button ng-click="searchStaff()" class="btn btn-icon btn-primary" type="button">
                                <span class="fa fa-search"></span>
                            </button>
                        </div>
                    </div>
                    <div class="list">

                        <div ng-if="staff_members!=null" ng-click="staffInView(staff)" ng-repeat="staff in staff_members"
                            class="panel cursor-pointer relative hover:shadow hover:bg-[--surface-100] transition-all p-3">
                            <span ng-if="staff.is_class_advisor" class="advisor-badge">Advisor</span>
                            <div class="panel-header">
                                <div class="flex items-center gap-3">
                                    <img src="/profilepic/{%staff.id%}" alt="staff"
                                        class="w-12 aspect-square rounded-full object-cover" />
                                    <div class="overflow-hidden">
                                        <div class="truncate font-bold" ng-bind="staff.user.name"></div>
                                        <div ng-bind="staff.staff_id"></div>

                                        <p ng-if="staff.designation==='advisor'"
                                            class="opacity-80 font-mono font-semibold text-xs text-[--primary-color]">
                                            ADVISOR </p>
                                        <p ng-if="staff.designation!=='advisor'"
                                            class="opacity-50 text-mono font-semibold uppercase text-xs text-[--primary-color]"
                                            ng-bind="staff.designation"></p>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div ng-if="staff_members==null" class="text-zinc-400 text-2xl">
                            NO STAFF ADDED YET
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('pages.hod.staff-management.profile')
</x-template>
