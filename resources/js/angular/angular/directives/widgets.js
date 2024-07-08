app.directive("controller", [
    "$timeout",
    function ($timeout) {
        return {
            restrict: "A",
            scope: {
                controller: "&",
                disabled: "<",
                ngDisabled: "=",
                form: "@",
                class: "@",
                values: "=",
                submitting: "=",
            },
            controller:'RootController',
            link: function (scope, element, attr) {
                let values = {initial: element.text()};
                if (typeof scope.values === "object" && scope.values !== null) {
                    values = {...values, ...scope.values};
                }
                if (!element.is("button") && !element.is(":submit")) {
                    return;
                }
                const newButton = angular.element(
                    `<button type="button" class="${scope.class} relative flex gap-1 items-center justify-center">
                        <span class="spinner-wrapper"><i id="btnIcon" class="btn-spinning"></i></span>
                        <span class="button-label flex items-center justify-center gap-1 font-semibold"></span>
                    </button>`
                );
                let form = scope.form
                    ? angular.element(scope.form)
                    : element.closest("form");
                scope.reset = scope.reset || true;

                element.replaceWith(newButton);
                const content = element.html();
                const label = newButton.find("span.button-label");
                const spinner = newButton.find("#btnIcon");
                const class_pattern = /\bstate-(sending|sent|error|initial)\b/;
                spinner.hide();
                try {
                    const setState = (state) => {

                        const class_list = newButton.attr("class") || "";
                        const match_class = class_list.match(class_pattern);
                        if (match_class) {
                            newButton.removeClass(match_class[0]);
                        }

                        newButton.addClass("state-" + state);

                        if (state === "sending") {
                            
                            spinner.attr("class", "btn-spinning").show();
                             label.find('i').hide();

                            if (values.sending) {
                                label.text(values.sending).show();
                            }
                            newButton.prop("disabled", true);
                        } else if (state === "sent") {
                            spinner
                                .attr("class", "sonar_once fa fa-check-circle")
                                .show();

                            if (values.sent) {
                                label.text(values.sent).show();
                            }
                        } else if (state === "error") {
                            spinner
                                .attr(
                                    "class",
                                    "opacity-50 fa fa-exclamation-triangle"
                                )
                                .show();
                            if (values.error) {
                                label.text(values.error).show();
                            }
                        } else if (state === "initial") {
                            spinner.attr("class", "btn-spinning").hide();
                            label.find('i').show();
                            label.html(content).show();
                            newButton.prop("disabled", false);
                        }
                    };

                    label.html(content);

                   

                    newButton.on("click", function (e) {
                        e.preventDefault();

                        if (!scope.disabled && !scope.ngDisabled) {
                            setState("sending");
                            
                            ajax.silent(false);
                            ajax.setEvent(e);
                           
                            
                            let promise = scope.controller.apply(scope);
                           

                            if (promise && angular.isFunction(promise.then)) {
                                promise
                                    .then((res) => {
                                        setState("sent");
                                    })
                                    .catch((err) => {
                                        setState("error");
                                    })
                                    .finally(() => {
                                        $timeout(() => {
                                            setState("initial");
                                        }, 2000);
                                    });
                            } else {

                                $timeout(() => {
                                    setState("initial");
                                }, 3000);
                            }
                        }
                    });

                    scope.$watch('ngDisabled', function(newValue, oldValue){
                        
                        newButton.prop('disabled', newValue === true);
                        
                    })
                } catch (e) {
                    alert(e);
                }
            },
        };
    },
]);

app.directive('check', function() {
    return {
        restrict: 'E',
        scope: {
            name: '@',
            class: '@',
            ngModel: '=',
            oncheck: '&',
            ngSelected: '<',
            selected: '<',
            behaviour: '@',
        },
        link: function(scope, element, attrs) {
            scope.class = scope.class || '';
            scope.behaviour = scope.behaviour || 'radio';

            if (typeof scope.ngModel === 'undefined' && (scope.selected || scope.ngSelected)) {
                scope.ngModel = scope.name;
            }

            const isMultiple = scope.ngModel && Array.isArray(scope.ngModel);
           
            const isSelected = (newValue, name) => Array.isArray(newValue) ? newValue.indexOf(name) >= 0 : newValue === name;

            element.addClass('chip');
            element.addClass(scope.class);



            element.on('click', function(e) {
                e.preventDefault();
                

                if (isMultiple) {
                    const index = scope.ngModel.indexOf(scope.name)
                    if (scope.behaviour === 'checkbox' && index >= 0) {
                        scope.ngModel.splice(index, 1);
                    }
                    else {
                        scope.ngModel.push(scope.name);
                        
                    }

                } else {
                    if (scope.behaviour === 'checkbox' && scope.ngModel == scope.name) {
                        scope.ngModel = null;
                    }
                    else {
                        scope.ngModel = scope.name;
                    }
                }
                scope.$apply();
                
                if (angular.isFunction(scope.oncheck)) {
                    scope.oncheck.apply(scope);
                }

                
            });

            scope.$watch('ngModel', function(newValue, oldValue){
                if (scope.name) {
                    element.toggleClass('chip-selected', Array.isArray(newValue) ? newValue.indexOf(scope.name) >= 0 : newValue === scope.name);
                }
               

            });

            const changeSelection = function(newValue, oldValue){
                if (scope.ngModel && scope.name) {
                    if (isMultiple) {
                        const index = scope.ngModel.indexOf(scope.name);
                        if (newValue && index < 0) {
                            scope.ngModel.push(scope.name);
                        }
                        else if (!newValue && index >= 0) {
                            scope.ngModel.splice(index, 1);
                        }
                        
                    }
                    else {

                        const selected = scope.ngModel === scope.name;
                        if (newValue && !selected) {
                            scope.ngModel = scope.name;
                        }
                        else if (!newValue && selected) {
                            scope.ngModel = null;
                        }
                    }
                }
                scope.$apply();
            };

            // scope.$watch('ngSelected', changeSelection);
            // scope.$watch('selected', changeSelection);





            element.toggleClass('chip-selected', isSelected(scope.ngModel, scope.name));
        }
    }
})

app.directive('sort', function(){
    return {
        restrict: 'E',
        scope: {
            property: '@',
            ngModel: '='
        },
        controller: 'RootController',
        link: function(scope, element, attrs) {
            scope.reverse = scope.reverse || false;

            element.on('click', function(e){
                e.preventDefault();
                
                if (Array.isArray(scope.ngModel) && scope.ngModel[0] == scope.property) {
                    scope.reverse = !scope.reverse; 
                }

                scope.ngModel = [scope.property, scope.reverse];

                element.toggleClass('fa-sort-alpha-up', scope.reverse);
                element.toggleClass('fa-sort-alpha-down', !scope.reverse);
                element.toggleClass('text-primary', Array.isArray(scope.ngModel) && scope.ngModel[0] == scope.property);


                scope.$apply();

            });

            scope.$watch('ngModel', function(newValue, oldValue) {
                element.toggleClass('text-primary', Array.isArray(scope.ngModel) && scope.ngModel[0] == scope.property);


            })

           

        
            element.addClass('fa fa-sort-alpha-down');
        }
    }
});

app.directive('ngSrc', function(){
    return {
        restrict: 'A',
        scope: {
            ngSrc: '=',
            user: '=',
            src: '@'
        },
        link: function(scope, element, attr) {
            
            if (!element.is('img')) {
                return;
            }
            
            const newImage = angular.element('<img>');
            
            
            Array.from(element[0].attributes).forEach(item => {
                const value = item.nodeValue;
                const name = item.nodeName;
                newImage.attr(name, value);
            });



            newImage.attr('src', scope.ngSrc);
            element.replaceWith(newImage);

            newImage.on('error', function(e){
                let image = null;

                if (scope.user) {
                    image = '/images/avatar-u.png';

                    if (typeof scope.user.gender == 'string') {
                        const gender = scope.user.gender.toLowerCase();
                        image = '/images/avatar-'+gender+'.png';
                    }
                }
                if (image) {
                    newImage.attr('src', image);
                }
            });

        }
    }
});


app.directive('printer', function(){
    return {
        restrict: 'E',
        controller: 'RootController',
        link: function(scope, element, attrs) {
            const text = element.text() || 'PRINT';
            const newButton = angular.element(`
                <button class="btn btn-primary flex gap-1 items-center justify-center">
                    <svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 0 24 24' width='24px' fill='currentColor'><path d='M0 0h24v24H0V0z' fill='none'/><path d='M19 8h-1V3H6v5H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zM8 5h8v3H8V5zm8 12v2H8v-4h8v2zm2-2v-2H6v2H4v-4c0-.55.45-1 1-1h14c.55 0 1 .45 1 1v4h-2z'/><circle cx='18' cy='11.5' r='1'/></svg>
                    <label>${text}</label>
                </button>
            `);

            element.replaceWith(newButton);

            newButton.on('click', function(){
                newButton.prop('disabled', true);
                scope.print();

                setTimeout(() => {
                    newButton.prop('disabled', false);
                }, 5000);
            });
        
        }
    }
});


app.directive('avatar', function(){
    return {
        restrict: 'E',
        scope: {
            user: '=',
        },
        link: function(scope, element, attr) {

            const newImage = angular.element('<img>');
            element.replaceWith(newImage);
            
            scope.$watch('user', function(user, oldValue){
                if (typeof user === 'object' && user !== null) {
                    
                    
                    
            
            
                    Array.from(element[0].attributes).forEach(attr => {
                        newImage.attr(attr.nodeName, attr.nodeValue);
                    });

           
                    let role = user.role;
                    
                    let image = user.image;
                    let gender = user.gender;
                
                    if (typeof user[role] === 'object' && user[role] !== null) {
                        if (user[role].image) {image = user[role].image;}
                        if (user[role].gender) {gender = user[role].gender;}
                    }
                
                    
                    gender = gender?gender.toLowerCase():'u';
                    

                    if (!image) {
                        image = '/images/avatar-'+gender+'.png';
                    }

                    

                    newImage.attr('src', image);
                
            

                    newImage.on('error', function(e){
                        newImage.attr('src', '/images/avatar-'+gender+'.png');
                    });
                }
            });
           

        }
    }
});

app.directive('autocomplete', function($timeout) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            if (element.attr('autocomplete') === 'off' && ['password', 'email'].includes(element.attr('type'))) {
                applyObfuscation(element);
            }

            function applyObfuscation(element) {
                // Clear the value of the field
                element.val('');

                // Prevent autofill by resetting value on focus
                element.on('focus', function() {
                    element.val('');
                });

                // Generate unique IDs and names if they don't exist
                let originalId = attrs.id || `obfuscated-${Math.random().toString(36).substring(2, 9)}`;
                let originalName = attrs.name || `obfuscated-${Math.random().toString(36).substring(2, 9)}`;

                if (!attrs.id) {
                    element.attr('id', originalId);
                }
                if (!attrs.name) {
                    element.attr('name', originalName);
                }

//                 // Obfuscate the field ID and name after a delay
//                 const newId = `obfuscated-${Math.random().toString(36).substring(2, 9)}`;
//                 const newName = `obfuscated-${Math.random().toString(36).substring(2, 9)}`;

//                 $timeout(() => {
//                     element.attr('id', newId);
//                     element.attr('name', newName);
//                 }, 1000);

//                 // Restore original ID and name before form submission
//                 element.closest('form').on('submit', function() {
//                     element.attr('id', originalId);
//                     element.attr('name', originalName);
//                 });
            }

//             // Watch for changes in the DOM and apply obfuscation to new elements
//             scope.$watch(function() {
//                 return element.parent().find('input[autocomplete=off]');
//             }, function(newElements) {
//                 newElements.each(function() {
//                     applyObfuscation(angular.element(this));
//                 });
//             });
        }
    };
})


app.directive('loading', function(){
    return {
        restrict: 'E',
        templateUrl: '/components/loading.html'
    }
});

app.directive('title', function(){
    return {
        restrict: 'A',
        transpose: true,
        link: function(scope, element, attrs) {
            scope.hovered = false;

            element.on('mouseenter', function(){
                
            })
        }
    }
})

app.directive('typingEffect', ['$timeout', function($timeout) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            let text = '';
            let index = 0;
            let isDeleting = false;

            function type() {
                let fullText = scope.$eval(attrs.typingEffect);

                if (!isDeleting && index < fullText.length) {
                    text += fullText.charAt(index);
                    index++;
                    element.text(text);
                    $timeout(type, 100); // Adjust typing speed here
                } else if (isDeleting && index > 0) {
                    text = text.substring(0, text.length - 1);
                    index--;
                    element.text(text);
                    $timeout(type, 50); // Adjust deleting speed here
                } else if (index === fullText.length) {
                    isDeleting = true;
                    $timeout(type, 500); // Pause before starting to delete
                } else if (index === 0 && isDeleting) {
                    isDeleting = false;
                    $timeout(type, 500); // Pause before starting to type again
                }
            }

            // Initial typing effect
            type();
        }
    };
}]);

