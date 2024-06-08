app.controller("AdvisorController", function ($scope) {
    $scope.show_advisor = false;
    $scope.advisors = [];
    $scope.advisor_name = {};
    

    $scope.advisor_id = Location.get("advisor_id");
    $scope.advisor = false;
    $scope.edit = null;
    $scope.add = null;
    $scope.firstname = null;
    $scope.lastname = null;
    $scope.middlename = null;
    $scope.graduation_session = null;
    $scope.open = false;
    $scope.level = null;
    $scope.data = {};
    $scope.addClass = false;
    $scope.classes = [];



    $scope.showAdvisor = (advisor_id) => {
        api("/advisor", { advisor_id })
            .then((response) => {
                $scope.show_advisor = response;
                $scope.popend('show_advisor');
                $scope.$apply();
            })
            .catch((error) => console.log(error));
    };

    $scope.init = () => {
      api('/find_advisors', )
    }

    $scope.editAdvisor = (advisor_id) => {
        api("/advisor", { advisor_id })
            .then((response) => {
                Location.set({ advisor_id });
                $scope.edit = response;

                $scope.$apply();
            })
            .catch((error) => console.log(error));
    };

    $scope.stopEditing = () => {
        $scope.edit = null;
    };

    $scope.closeEditor = function () {
        $scope.edit = false;
    };
    $scope.openEditor = () => {
        $scope.edit = true;
    };
    $scope.openAdder = () => {
        $scope.add = true;
    };
    $scope.closeAdder = () => {
        $scope.add = false;
    };
    $scope.addCustomClass = (event) => {
        const set_id = $("#set_id");
        const form = set_id.closest("form");

        const element = form.find($("#admission"));

        console.log(element.length);
        if (
            set_id.length > 0 &&
            element.length > 0 &&
            set_id.val() === "custom"
        ) {
            //console.log(event);
        }

        // if (event.target.value === 'custom') {

        //   element.focus();
        // }

        // ng-change="addClass=$event.target.value=='custom'"
    };
    $scope.insertClass = () => {
        alert(1);
    };

    $scope.openAdder = function () {
        $scope.add = true;
    };

    $scope.AddAdvisor = function () {
        api("/admin/advisor/add", $scope.data)
            .then((response) => console.log(response))
            .catch((error) => console.error(error));
    };

    $scope.show = (advisor_id) => {
        api("/advisor", { advisor_id })
            .then((response) => {
                Location.set({ advisor_id });
                $scope.advisor = response;

                $scope.$apply();
            })
            .catch((error) => console.log(error));
    };

    $scope.init = () => {
        if ($scope.advisor_id) {
            api("/advisor", { advisor_id: $scope.advisor_id })
                .then((response) => {
                    $scope.advisor = response;
                    $scope.$apply();
                })
                .catch((error) => console.log(error));
        }
    };

    $scope.back = () => {
        $scope.advisor = null;
        $scope.advisor_id = null;
        Location.drop("advisor_id");
    };

    $scope.loadClasses = function () {
        api("/classes")
            .then((classes) => {
                $scope.classes = classes;
                $scope.$apply();
            })
            .catch((error) => log(error));
    };
    makeClassAdvisor

    // $scope.changeSession = ($evt) => {
    //   const value = $evt.target.value;
    //   const yearMatcher = value.match(/^(\d+){4,4}\/(\d+){4,4}$/);

    //   if (yearMatcher) {
    //     const [ start, end ] = value.split('/').map(item => parseInt(item));
    //     console.log(yearMatcher);
    //     const end_session = `${start+5}/${end+5}`;

    //     $scope.graduation_session = end_session;

    //   }

    // }
});
