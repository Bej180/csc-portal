@props([ 'name', 'check'])
@php 
  
  $cleanName = preg_replace('/([^a-zA-Z0-9_]+)/', '', $name);
  $id = uniqid('checkbox.'.$cleanName);
  if (!isset($check)) {
    $check = 'checked';
  }
  if ($check) {
    $check = implode(' peer-checked:',preg_split('/\s+/', $check));
  }

  $checked = 'false';
  if (isset($attributes['ng-checked']) && $attributes['ng-checked'] != 'false') {
    $checked = 'true';
  }


@endphp
<div class="custom-checkbox" ng-controller="CheckboxController" ng-init="$ctrl.checked=$ctrl.checked||{{$attributes['ng-checked']}}">
    <div class="text-black inline-flex items-center align-middle">
        <div class="checkbox-area" ng-click="changed($event)">
            <div class="checkbox-touch"></div>
            <input type="checkbox" name="{{$name}}" ng-checked="$ctrl.checked!=0" id="{{$id}}" tabindex="0" ng-model="$ctrl.checked" ng-modelx="{{$cleanName}}" {{$attributes}}>
            <div class="checkbox-ripple"></div>
            <div class="checkbox-bg">
                <svg focusable="false" viewBox="0 0 24 24" aria-hidden="true" class="mdc-checkbox__checkmark">
                    <path fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59" >
                    </path>
                </svg>
                <div class="checkbox-mixedmark"></div>
            </div>
            <div class="focus-indicator group-focus:opacity-100"></div>
        </div>
        <label class="checkbox-label" for="{{$id}}" 
        ng-class="{ '{{$check}}': $ctrl.checked }">{!!$slot!!}</label>
    </div>
</div>
