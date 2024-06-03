/**
 * newSession Directive
 * Directive for managing a new session.
 */

app.directive('newSession', [function() {
    return {
        restrict: 'A',
        link: function(scope, element) {
            scope.options = {};


               

                api(
                    "/class/generate_name",
                    (res) => {
                        scope.options = res;
                        console.log({res})
                    }
                );
                

        }
    }
}])
app.directive("newSession", function () {
    return {
        restrict: "E",
        scope: {
            ngModel: '=',
        },
        templateUrl: "/components/selectnewsession.html",
        link: function (scope, element) {
            scope.clicked = false;
            scope.sessions = [];
            const wrapper = angular.element(`
            <span>
            <select class="ignore" ng-if="ngModel!=='custom-session'"><option value="">Select Session</option></select>
            <input type="text" ng-if="ngModel=='custom-session'" mask="9999/9999"/>
            </span>
            `);
            const select = wrapper.find('select');
            const input = wrapper.find('input')

          element.replaceWith(wrapper);
            // scope.model = 
            

            select.on('click', function(e){
                e.preventDefault();
                e.stopPropagation();

                if (!scope.clicked) {
                scope.clicked = true;
                select.empty();
                select.append(`<option value="">Loading...</option>`);

                api(
                    "/class/generate_name",
                    (res) => {
                        select.empty();
                        select.append('<option value="">Select Session</option>');

                        res.forEach(session => {
                            const option = angular.element(`<option>${session}</option>`);
                            select.append(option);
                        });

                        select.append('<option value="custom-session">Custom Session</option>');
                    }
                );
                }
            });

            select
            .on('blur', (event) => {
                // scope.clicked = false;
            })
            .on('change', (event) => {
                // scope.clicked = false;
                const value = event.target.value;
                scope.ngModel = value;
                
                
                scope.$apply();
            });

            input.on('keydown', (event) => {
                const value = event.target.value;
                scope.ngModel = value;
                
                scope.$apply();
            });
        },
    };
});
