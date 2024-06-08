// app.js (or your main AngularJS application file)
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

                            var promise = scope.controller();

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
                                setState("initial"); // In case the ngClick doesn't return a promise
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