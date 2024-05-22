<div class="flex justify-between items-center w-full py-3">
    <div class="lg:invisible flex items-center cursor-pointer" ng-click="back()">
        <span class="material-symbols-rounded">arrow_back</span>
        <span>Back</span>
    </div>

    <div>
        {{ $slot }}
    </div>
</div>
