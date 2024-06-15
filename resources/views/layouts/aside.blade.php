@auth
    @php
        $role = auth()->user()->role;
    @endphp
    <div ng-init="changeNav('{{ $nav }}')" ng-controller="SidebarController">
        <div class="sidebar shrink-0 minimized closed-sidebar" ng-class="{'closed-sidebar':!opensidebar}"
            ng-mouseenter="enterSidebar($event)" ng-mouseleave="leaveSidebar($event)">

            <div class="sidebar-background"></div>



            <div class="sidebar-header">


            </div>




            <div class="sidebar-body">
                <ul class="menu">
                    <li class="p-[20px] flex md:hidden items-center justify-between">
                        <span class="text-xs opacity-20 inline-flex gap-2 items-center">
                            <x-icon name="menu"/> 
                            <label>Menu</label>
                        </span>
                        <span class="text-2xl opacity-15 hover:scale-150 hover:opacity-100" ng-click="closeSidebar()">&times;</span>
                    </li>
                    <li data-nav="home" ng-class="{'active': isActiveNav('home')}">
                        <a href="/home" ng-click="changeNav('home')">
                            <i class="material-symbols-roundedx"><x-icon name="dashboard" /> </i>
                            <label>Dashboard</label>
                        </a>
                    </li>
                    @auth

                        @include('layouts.aside.sidebar-' . auth()->user()->role)


                        @if ($role == 'student')
                            <li data-nav="profile" ng-class="{'active': isActiveNav('profile')}" ng-click="changeNav('profile')">
                                <a href="/{{ $role }}/profile">
                                    <i class="faIcon"><x-icon name="manage_accounts"/></i>
                                    <label>Profile</label>
                                </a>
                            </li>
                        @else
                            <li data-nav="profile" ng-class="{'active': isActiveNav('profile')}" ng-click="changeNav('profile')">
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
                                    <x-icon ng-cloak name="dark_mode" ng-if="theme!=='dark'" />
                                    <x-icon name="light_mode" ng-if="theme==='dark'" />

                                    <label ng-bind="theme === 'dark' ? 'Light Mode':'Dark Mode'">Light Mode</label>
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
