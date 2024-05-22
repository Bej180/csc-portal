<x-popend name="select_advisor_class">

    <div class="center-page">
        <div>
            <div class="paragraph">
              {% name %}
            </div>
            <div>
                Make Class advisor of:

                <form class="flex flex-col gap-3 mt-2">

                    <new-session class="input" name="select" ng-model="session"></new-session>

                    <submit class="btn btn-primary" ng-click="makeStaffAdvicer(make_advisor_id, session)"
                        state="buttonState.add_class" submit="add_class" value="Make Staff Advicer"></submit>
                </form>
            </div>
        </div>
    </div>

</x-popend>
