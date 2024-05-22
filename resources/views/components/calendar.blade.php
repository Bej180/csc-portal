<link rel="stylesheet" href="{{ asset('styles/calendar.css') }}" />
<script src="{{ asset('js/calendar.js') }}" defer></script>

<div class="calendar-wrapper">
    <div class="calendar-header">
        <p class="current-date"></p>
        <div class="icons">
            <span id="prev" class="fa fa-chevron-left"></span>
            <span id="next" class="fa fa-chevron-right"></span>
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
