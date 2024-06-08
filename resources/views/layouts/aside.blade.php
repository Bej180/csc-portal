@auth
    @php
        $role = auth()->user()->role;
    @endphp
    <div ng-init="nav='{{ $nav }}'" ng-controller="SidebarController">
        <div class="sidebar shrink-0 minimized closed-sidebar" ng-class="{'closed-sidebar':!opensidebar}"
            ng-mouseenter="enterSidebar($event)" ng-mouseleave="leaveSidebar($event)">

            <div class="sidebar-background"></div>



            <div class="sidebar-header">


            </div>




            <div class="sidebar-body">
                <ul class="menu">
                    <li data-nav="home" ng-class="{'active': nav == 'home'}">
                        <a href="/home" ng-click="changeNav('home')">
                            <i class="material-symbols-roundedx"><x-icon name="dashboard" /> </i>
                            <label>Dashboard</label>
                        </a>
                    </li>
                    @auth

                        @include('layouts.aside.sidebar-' . auth()->user()->role)


                        @if ($role == 'student')
                            <li data-nav="profile" ng-class="{'active': nav == 'profile'}" ng-click="changeNav('profile')">
                                <a href="/{{ $role }}/profile">
                                    <i class="faIcon"><x-icon name="manage_accounts"/></i>
                                    <label>Profile</label>
                                </a>
                            </li>
                        @else
                            <li data-nav="profile" ng-class="{'active': nav == 'profile'}" ng-click="changeNav('profile')">
                                <a href="/profile">
                                    <i class="faIcon"><x-icon name="manage_accounts"/></i>
                                    <label>Profile</label>
                                </a>
                            </li>
                        @endif

                        <li>
                            <a ng-click="logout()">
                                <i class="faIcon"><x-icon name="logout"/></i>
                                <label>Logout</label>
                            </a>
                            <confirm title="Alert" ng-if="logout" confirm="confirmLogout(this)">
                                Are you sure you want to log out?
                            </confirm>
                        </li>
                    @else
                    @endauth






                </ul>
            </div>
            @auth
                <div class="sidebar-footer">
                    <div class="flex w-full items-center">
                        <div class="grow">
                            <div>
                                <div class="theme-toggler" ng-click="toggleTheme()">
                                    <x-icon name="dark_mode" ng-if="theme!=='dark'" />
                                    <x-icon name="light_mode" ng-if="theme==='dark'" />

                                    <label ng-bind="theme === 'dark' ? 'Light Mode':'Dark Mode'"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth

        </div>
        <!-- <div ng-show="opensidebar" class="sidebar-backdrop" ng-click="closeSidebar()"></div> -->
    </div>
@endauth
