@props(['checked', 'name', 'value'])
@php 
  if (!isset($checked)) {
    $checked = 'false';
  }
  else {
    $checked = 'true';
  }

  $id = uniqid($name);

@endphp
<div class="group custom-radio" ng-init="checked={{$checked}};">
  <div class="custom-radio-wrapper" ng-class="{'checked':checked}" ng-click="checkRadio($event)">
    <div class="radio-container">
      <div class="radio-touch"></div>
      <input type="radio"
        id="{{$id}}" ng-model="{{$name}}" class="peer" name="{{$name}}" value="{{$value}}"  tabindex="0" ng-checked="checked" {{ $attributes }}/>
      <div class="radio-bg">
        <div class="radio-outer-circle"></div>
        <div class="radio-inner-circle"></div>
      </div>
      <div class="input-focus-indicator">
        <div></div>
      </div>
    </div><label for="{{$id}}">{{$slot}} </label>
  </div>
</div>