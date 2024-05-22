@php
    $advisors = \App\Models\Advisor::with('user')->get();
@endphp


<fieldset class="fieldset">
  <legend>Basic Information</legend>

  <div class="flex gap-4 justify-end">
      <div class="flex-1">
          
        <x-input type="text" placeholder="Admission" name="start" class="session-input" data-session='1'/>
      </div>
      <div class="flex-1">
        <x-input type="text" placeholder="Graduation" class="session-input" data-session='1'/>
      </div>

    
  </div>
</fieldset>

