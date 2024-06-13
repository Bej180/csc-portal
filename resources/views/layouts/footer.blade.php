<?php

$role = auth()->check() ? auth()->user()->role : 'guest';
$module = $module ?? 'page';
$nav = $nav ?? 'all';
?>

<!--absolute bottom-8 right-0-->
@if (in_array($role, ['admin', 'advisor', 'hod']))
    <div class="announcement" ng-controller="AnnouncementController" ng-init="initAnnouncement()" ng-class="{show:displayAnnouncement}">
        <div class="backdrop" ng-click="closeAnnouncement($event)">
            <div class="announcement-wrapper">
                <div class="announcement-body">
                    <ul class="overflow-y-auto">
                        <li ng-if="announcements.length > 0" ng-repeat="announcement in announcements" class="each-announcement">
                            
                            <div class="border-b border-zinc-200 last:border-none p-4">
                                <span class="font-semibold link" ng-bind="announcement.announcer.name"></span>


                                <div class="flex-1" ng-bind="announcement.message"></div>
                                <span class="text-zinc-400" ng-bind="announcement.posted_at"></span>

                            </div>
                        </li>

                        <li ng-if="announcements.length === 0">
                            NO ANNOUNCEMENT MADE YET 
                        </li>

                        
                    </ul>
                    <div class="flex flex-col gap-1 mt-4 justify-end">
                        <textarea placeholder="Enter Announcement" ng-model="announcement.message" class="input h-16"></textarea>
                        <div class="flex flex-end gap-2">
                            <div class="flex-1">
                              @if($role == 'advisor')
                                <input type="hidden" ng-model="announcement.target" value="class"/>
                                <span class="input">My Students</span>
                              @else
                                <label class="font-semibold">Audience</label>
                                <select drop="top" ng-model="announcement.target">
                                    <option value="everyone" selected>Everyone</option>
                                    <option value="students">Students</option>
                                    <option value="lecturers">Lecturers</option>
                                    <option value="techologists">Technologists</option>
                                </select>
                                @endif
                            </div>
                            <div class="flex-1">
                                <button type="button" ng-click="Announce(announcement)"
                                    ng-disabled="!announcement.message"
                                    class="w-full btn btn-primary disabled:!opacity-80 p-1"
                                    ng-disabled="!announcement"><i class="fa fa-paper-plane"></i> Send</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <button ng-click="toggleAnnouncementBell($event)"
            class="z-[1045] absolute bottom-5 right-5 btn btn-primary btn-rounded scale-125"
            ng-class="{'btn-primary':!displayAnnouncement, 'btn-secondary': displayAnnouncement}">
            <i class="material-symbols-rounded">notifications_rounded</i>
        </button>
    </div>
@endif

<div ng-show="showPasswordModal" class="password-modal">
    <div class="password-modal-content">
        <span class="close" ng-click="closeModal()">&times;</span>
        <h2>Verify Password</h2>
        <input type="password" ng-model="password" placeholder="Enter your password">
        <button ng-click="verifyPassword()">Submit</button>
    </div>
</div>
<div id="isLoading" class="">
    <div class="flex items-center gap-1">
        <div class="dot-pulse"></div>
        <div class="text-2xl ml-[20px]">
            Loading...
        </div>
    </div>
</div>
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/jquery-plugins.js') }}"></script>
<script src="{{ asset('js/export-table.js') }}" type="module"></script>
{{-- <script src="{{ mix('js/app.js') }}" defer></script> --}}
@vite('resources/js/app.js')
@isset($script)
    <script type="module" src="{{ $script }}"></script>
@endisset
<script type="module" src="{{ asset('scripts/main.js') }}"></script>


<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/scripts/feather.min.js') }}"></script>
<script src="{{ asset('js/scripts/main.js') }}" type="module"></script>

<script src="{{ asset('js/scripts/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('js/scripts/mask.js') }}"></script>

@include('partials.popup-alert')
<span id="footer-slot"></span>
