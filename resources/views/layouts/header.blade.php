@php
    $authUser = auth()->user();

    $announcements = auth()->user()?->notifications();

    if (auth()->check()) {
        if (auth()->user()->role === 'student') {
            $announcements = auth()->user()->student?->notifications();
        }
    }

    $user_class = $authUser?->profile?->class;
@endphp

<header id="mainheader" class="print:hidden">
    <div class="flex items-center">
        @auth
            <x-icon name="menu" ng-click="toggleSidebar()"
                class="sidebar-toggler text-body-800 cursor-pointer hover:text-[var(--primary-700)] transition"/>
        @endauth
        <div class="login--top flex items-center gap-1 text-green-700">
            <a href="/home" class="inline-block">
                <img src="{{ asset('svg/logo.svg') }}" alt="logo" width="30">
            </a>
            <div class="text-xm hidden lg:block text-sm">
                <p class="font-size-2 text-body-600 dark:text-white font-bold relative -bottom-[2px]" typing-effect="'Department of
                Computer Science'">Department of
                    Computer Science</p>
                <p class="font-size-1 text-body-400 dark:text-white/60 font-semibold  relative -top-[2px]" typing-effect="'Federal
                University of Technology, Owerri'">Federal
                    University of Technology, Owerri</p>
            </div>
        </div>
    </div>

    <div class="flex-1 flex justify-end items-center">


        <div class="flex items-center gap-1" ng-controller="AnnouncementController">
            <span class="relative notifications" ng-class="{show:show_notifications}">

                <div class="notification-wrapper">
                    <i class="material-symbols-rounded"
                        ng-click="toggleNotificationVisibility()">notifications_rounded</i>
                    @if ($count = $announcements?->count())
                        <span class="notification-bell">{{ $count }} </span>
                    @endif

                    <div class="backdrop" ng-click="hideNotifications($event)"></div>

                    <div class="notification-content">
                        <div class="notification-body">
                            @if (!empty($announcements))
                                @forelse ($announcements as $announcement)
                                    <div class="border-b border-zinc-200 last:border-none p-4">
                                        <span class="font-semibold link">
                                            {{ $announcement->announcer->name }}
                                        </span>


                                        <div class="flex-1">
                                            {{ $announcement->message }}
                                        </div>
                                        <span class="text-zinc-400">
                                            {{ timeago($announcement->created_at) }}
                                        </span>

                                    </div>
                                @empty
                                @endforelse
                            @endif
                        </div>
                    </div>
                </div>
            </span>
            
            <i class="material-symbols-rounded text-green-500" id="page-tips">help</i> 

            @auth
                <div class="relative flex items-center" ng-controller="ProfileCardController">
                    <x-profile-pic :user="$authUser" alt="user_img" class="w-10 h-10 object-cover rounded-full" />
                    <div class="center">
                        <span ng-click="toggleProfileCard()"
                            class="text-body-800 cursor-pointer select-none hover:text-[var(--primary-700)]">
                            <x-icon name="expand_less" ng-if="!open"/>
                            <x-icon name="expand_more" ng-if="open"/>
                        </span>
                    </div>
                    <div class="profile-card-overlay" ng-class="{'show':open}" ng-click="toggleProfileCard()"></div>
                    <div class="profile-card" ng-class="{'show':open}">
                        <div class="profile-card-body">
                            <x-profile-pic :user="$authUser" alt="user_img" class="w-14 h-14 object-cover rounded-full" />
                            <h1 class="flex flex-col items-center text-center justify-center">
                                <div class="font-bold">{{ $authUser->name }}</div>
                                <div>
                                    @if ($authUser->role == 'student')
                                        {{ $authUser->student->reg_no }}
                                    @endif
                                </div>
                            </h1>

                            @if ($user_class?->name)
                                <p class="text-sm">
                                    Class:
                                    <span class="font-semibold text-slate-800 dark:text-white">
                                        {{ $user_class?->name }}
                                    </span>
                                </p>
                                @if ($authUser->role == 'student')
                                    <p class="text-center">
                                        Advisor<br><span class="text-xs font-semibold text-slate-800  dark:text-white">
                                            {{ $user_class?->advisor?->user->name }}
                                        </span>
                                    </p>
                                @endif
                            @endif
                        </div>



                        <div class="profile-card-footer">
                            <x-tooltip label="Account">
                                <a href="#" class="flex justify-center">
                                    <x-icon name="account_circle"/>
                                </a>
                            </x-tooltip>
                            <x-tooltip label="Setting">
                                <a href="#" class="flex justify-center">
                                    <x-icon name="settings"/>
                                </a>
                            </x-tooltip>
                            <x-tooltip label="Logout">
                                <a href="#" class="flex justify-center">
                                    <x-icon name="logout"/>
                                </a>
                            </x-tooltip>
                        </div>
                    </div>
                </div>
            @endauth


        </div>
    </div>
</header>
