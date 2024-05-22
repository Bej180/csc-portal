<form class="bg-white p-5 rounded-md">


  <div class="flex flex-col gap-4">
    <div class="flex-1 flex">
        <label>Enrollment Session</label>
        <div>

          @php
              $start = date('Y');
              $end = $start + 5;
          @endphp

          @for($i = $start; $i <= $end; $i++)

          <div class="flex gap-2">
            <input type="radio" name="session" value="{{$i}}/{{$i+5}}" id="session-{{$i}}/{{$i+5}}" class="radio"/>
            <label for="session-{{$i}}/{{$i+5}}">{{$i}}/{{$i+5}}</label>
          </div>

          @endfor

        </div>
        <input type="number" class="form-control session-mask" max="9999"/>
        <select name="session" class="input">
            <option value="">Select Session</option>
            <option value="2018/2019">2018/2019</option>
        </select>
    </div>
    <div class="flex-1 flex flex-col">
        <label>Graduation Session</label>
        <select name="session" class="input">
            <option value="">Select Session</option>
            <option value="2018/2019">2023/2024</option>
        </select>
    </div>
</div>





    <div class="flex flex-col gap-4">
        <div class="flex-1 flex flex-col">
            <label>Enrollment Session</label>
            <select name="session" class="input">
                <option value="">Select Session</option>
                <option value="2018/2019">2018/2019</option>
            </select>
        </div>
        <div class="flex-1 flex flex-col">
            <label>Graduation Session</label>
            <select name="session" class="input">
                <option value="">Select Session</option>
                <option value="2018/2019">2023/2024</option>
            </select>
        </div>
    </div>
    <div class="flex gap-2 mt-4 justify-end">
        <button type="button" class="btn-white" x-on:click="editingClass=null">Cancel</button>
        <button type="submit" class="btn-primary">Create Class</button>
    </div>

</form>
