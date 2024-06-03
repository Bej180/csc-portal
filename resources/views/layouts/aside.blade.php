@auth
    @php
        $role = auth()->user()->role;
    @endphp
    <div ng-init="nav='{{ $nav }}'" ng-controller="SidebarController">
        <div class="sidebar shrink-0 minimized closed-sidebar" ng-class="{'closed-sidebar':!opensidebar}" ng-mouseenter="enterSidebar()" ng-mouseleave="leaveSidebar()">
           
            <div class="sidebar-background"></div>

            
            
            <div class="sidebar-header mt-8">
                
                <div id="color-scheme-toggler" ng-init="system='System ('+system_detect+')'">
                <select 
                    options="{light:'Light Mode', dark:'Dark Mode', system: system}"
                
                    ng-change="saveTheme(colorScheme)" class=" dark:bg-[--grey-800] w-[90px]" ng-model="colorScheme" placeholder="Color Scheme">
                    
                </select> {% colorScheme %}
            </div>


                <form class="flex w-full justify-center px-3">
                    <div class=" flex justify-between px-1  items-center  rounded-md input-group">

                        <input class="flex-1 p-1 outline-none !border-none !text-white/40 !focus:text-white focus:outline-none !bg-black/10" type="search"
                            placeholder="What are you looking for?" />
                        <button class="btn btn-icon bg-black/30" type="button">
                            <span class="fa fa-search"></span>
                        </button>
                    </div>
                </form>



            </div>
            <div class="sidebar-body">
                <ul class="menu">
                    <li data-nav="home" ng-class="{'active': nav == 'home'}">
                        <a href="/home" ng-click="changeNav('home')">
                            <i class="material-symbols-rounded">space_dashboard</i>
                            <label>Dashboard</label>
                        </a>
                    </li>
                    @auth

                        @include('layouts.aside.sidebar-' . auth()->user()->role)


                        @if ($role == 'student')
                            <li data-nav="profile" ng-class="{'active': nav == 'profile'}" ng-click="changeNav('profile')">
                                <a href="/{{ $role }}/profile">
                                    <i class="material-symbols-rounded">person_rounded</i>
                                    <label>Profile</label>
                                </a>
                            </li>
                        @else
                            <li data-nav="profile" ng-class="{'active': nav == 'profile'}" ng-click="changeNav('profile')">
                                <a href="/profile">
                                    <i class="material-symbols-rounded">person_rounded</i>
                                    <label>Profile</label>
                                </a>
                            </li>
                        @endif

                        <li ng-init="logout=fals">
                            <a ng-click="logout=!logout">
                                <i class="material-symbols-rounded">logout</i>
                                <label>Logout</label>
                            </a>
                            <confirm title="Alert" show="logout" confirm="confirmLogout(this)">
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
                            <div class="group relative" data-headlessui-state="">
                                <div class="flex w-full items-center gap-2 rounded-lg p-2 text-sm">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center overflow-hidden rounded-full">
                                            <div class="relative flex">
                                                <img class="w-8 aspect-square rounded-sm shrink-0"
                                                    src="/profilepic/{{ auth()->id() }}" alt="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sidebar-user-profile">
                                        <div>
                                            {{ auth()->user()->name }}
                                        </div>
                                    </div>
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
