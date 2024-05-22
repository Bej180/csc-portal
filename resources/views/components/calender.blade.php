<link rel="stylesheet" href="{{ asset('styles/calendar.css') }}" />
<script src="{{ asset('js/calendar.js') }}" defer></script>

<div class="calendar-wrapper">
    <div class="calender-header">
        <p class="current-date"></p>
        <div class="icons">
            <span id="prev" class="material-symbols-rounded">chevron_left</span>
            <span id="next" class="material-symbols-rounded">chevron_right</span>
        </div>
    </div>
    <div class="calendar">
        <ul class="calendar-weeks">
            <li>Sun</li>
            <li>Mon</li>
            <li>Tue</li>
            <li>Wed</li>
            <li>Thu</li>
            <li>Fri</li>
            <li>Sat</li>
        </ul>
        <ul class="calendar-days"></ul>
    </div>
</div>
