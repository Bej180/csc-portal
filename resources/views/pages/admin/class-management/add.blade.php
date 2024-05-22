<x-popend name="addClass" title="Add Class">
    @php $advisors = \App\Models\Staff::with('user')->get();@endphp


    <form>
        <fieldset class="fieldset">
            <p class="font-bold primary-text">Class Info</p>

            <div class="flex gap-4 flex-col mt-5">
                <div class="flex">
                    <input type="text" ng-model="session" placeholder="Session" class="input session-input !w-full" data-session='5' />
                </div>


                <div class="flex">
                    <select ng-model="advisor" class="input">
                        <option value="">Select Advisor</option>
                        @foreach ($advisors as $advisor)
                            <option value="{{ $advisor->id }}">{{ $advisor->user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </fieldset>


        <div class="flex gap-2 mt-4 justify-end">
            
            <submit state="buttonStates.add_class" submit="add_class" class="btn btn-primary" ng-click="saveClass()" value="Add Class"/>
        </div>

    </form>



</x-popend>
