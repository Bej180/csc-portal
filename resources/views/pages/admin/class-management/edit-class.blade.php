<div x-cloak x-show="editingClass" x-init="overlay=true;" class="grid place-items-center fixed bg-black/50 w-screen h-screen z-[1001] top-0 left-0" x-data="{editinClass: true}">

    <form class="bg-white p-5 rounded-md">
      <h1 class="mb-4 text-2xl font-bold text-center">Edit Class</h1>
      <fieldset class="fieldset">
        <legend>Basic Information</legend>

        <div class="flex gap-4">
          <div class="flex-1">
            <h2>Enrollment Session</h2>
            <select name="session" class="input">
              <option value="">Select Session</option>
              <option value="2018/2019">2018/2019</option>
            </select>
          </div>
          <div class="flex-1">
            <h2>Graduation Session</h2>
            <select name="session" class="input">
              <option value="">Select Session</option>
              <option value="2018/2019">2023/2024</option>
            </select>
          </div>

          <div class="flex-1">
            <h2>Advisor</h2>
            <select name="session" class="input">
              <option value="">Select Advisor</option>
              <option value="2018/2019">2018/2019</option>
            </select>
          </div>
        </div>
      </fieldset>
      <div class="flex gap-2 mt-4 justify-end">
        <button type="button" class="btn-white" x-on:click="editingClass=null">Cancel</button>
        <button type="submit" class="btn-primary">Edit Class</button>
      </div>

    </form>


</div>