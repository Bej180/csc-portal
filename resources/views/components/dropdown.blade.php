@props(['name', 'placeholder', 'disabled', 'dir', 'max', 'relative', 'onchange', 'relative', 'class', 'manual'])
@php
    $defaults = [
        'name' => '',
        'placeholder' => 'Select',
        'disabled' => 'false',
        'dir' => 'top-right',
        'max' => 3,
        'relative' => '',
        'onchange' => '',
        'relative' => 'form',
        'disabled' => false,
        'class' => '',
        'manual' => false,
    ];

    foreach ($defaults as $key => $value) {
        if (!isset($$key)) {
            $$key = $value;
        }
    }
@endphp
<div ng-controller="DropdownController"
    ng-init="name='{{ $name }}'; dir='drop-{{ $dir }}'; max='{{ $max }}'; placeholder='{{ $placeholder }}'; relative='{{ $relative }}'; onchange='{{ $onchange }}';texts=['{{ $placeholder }}'];init()"
    class="inline-block">
    <x-tooltip label="{{ $placeholder }}" ng-hide="show">
        <span class="dropdown {% dir %} ignore {{ $class }}"
            ng-class="{'show': show, 'pointer-events-none opacity-50': {{ $disabled ? 'true' : 'false' }}}"
            data-placeholder="{{ $placeholder }}" {{ $attributes }}>

            <div ng-show="show"
                style="position:fixed;left:0;top:0;bottom:0;width:100%;height:100%;z-index:1000;background:rgba(0,0,0,0.02)"
                ng-click="show=false"></div>

            <input type="hidden" ng-model="{{ $name }}" ng-repeat="input in inputs" name="{%name%}"
                value="{%input%}" />
            <span class="inputArea"></span>
            <button class="dropdown-toggle input relative z-[999] text-sm" ng-class="{'z-1000':show}" type="button"
                id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false" ng-click="toggleDropdown($event)">

                <span ng-show="texts.length > 0" class="flex items-center justify-between gap-1">
                    <span class=" max-w-[60px] h-4 whitespace-nowrap overflow-hidden text-ellipsis inline-block"
                        ng-bind="displayText()"></span> <span class="opacity-50 text-xs" ng-bind="extra()"></span>
                </span>
                <span ng-bind="show ? 'expand_less' : 'expand_more'"
                    class="material-symbols-rounded text-body-800 cursor-pointer select-none ">
                    expand_more
                </span>
            </button>
            <span class="dropdown-menu !z-[1000]" ng-class="{'xinvisible':show, 'invisible':!visible}"
                aria-labelledby="dropdownMenuButton" relative="{{ $relative }}">
                <span class="dropdown-header">{{ $placeholder }}</span>
                <span class="dropdown-body">
                    {{ $slot }}
                    @if($manual) 
                      <x-option value="manual">Manual</x-option>
                    @endif
                    <marquee ng-show="multiple" class="dropdown-footer">Ctrl to select more</marquee>
                </span>
            </span>

        </span>
    </x-tooltip>
</div>
