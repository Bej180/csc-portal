import Http from "../../../public/js/http";



app.controller("AlertController", function ($scope, $timeout) {
    $scope.showAlert = false;

    $scope.init = function () {
        $timeout(function () {
            $scope.showAlert = true;
            $timeout(function () {
                $scope.showAlert = false;
            }, 5000);
        });
    };
});





app.controller("ScanUserController", function ($scope, $window) {
    $scope.scan = { id: null, name: null, email: null, phone: null };
    $scope.data = [];
    $scope.cursor = null;
    $scope.limit = '10';
    $scope.holder = null;

    $scope.prev_page = null;
    $scope.next_page = null;
    $scope.page = 1;
    $scope.from = null;
    $scope.to = null;

    $scope.url = null;
    $scope.results = [];

    
    $scope.navigatePage = (page) => {
        $scope.page = page;

        $scope.searchFor($scope.holder);
    };


    $scope.scanned = () => {
        return Object.entries($scope.scan).reduce((acc, [key, value]) => {
            if (value) {
                acc[key] = value;
            }
            return acc;
        }, {});
    };


    $scope.searchFor = (holder, timeout = 0) => {
        $scope.holder = holder;
        $scope.url = "/search/" + holder;
        const limit = $scope.limit;
        const page = $scope.page;

        const queries = $scope.scanned();


        setTimeout(() => {
            $scope.api($scope.url, { queries, limit, page })
                .then((res) => {
                    $scope.results = res.data;
                    
                    
                    if (res.current_page) {
                        $scope.prev_page = res.current_page - 1;
                        $scope.next_page = res.current_page + 1;
                        $scope.from = res.from;
                        $scope.to = res.to;
                    }
                    
                    $scope.$apply();
                })
                .catch((err) => {
                    $scope.results = [];
                    
                    if (typeof err === 'object' && err !== null && err.message) {
                        toastr.error(err.message);
                    }
                    $scope.$apply();
                });
        }, timeout);

    };

    angular.element($window).bind('scroll', function() {
        console.log(1);
        if ($window.innerHeight + $window.scrollY >= document.body.offsetHeight) {
            $scope.searchFor($scope.holder); 
            $scope.$apply();
          }
      });



   
});

app.controller("SearchController", function ($scope) {
    $scope.results = [];
    $scope.query = null;
    $scope.typing = false;

    $scope.account = "students";

    $scope.search = function () {
        setTimeout(() => {
            $scope.typing = false;
            if (
                $scope.account &&
                ["staffs", "students", "admins"].includes(
                    $scope.account
                )
            ) {
                $scope.api("/search/students", {
                    query: $scope.query,
                })
                    .then((response) => {
                        $scope.results = response;
                        $scope.$apply();
                    })
                    .catch((response) => log(""));
            }
        }, 3000);
    };

    $scope.keyDown = () => {
        $scope.typing = true;
    };
});
app.controller("QuizWrapperController", function ($scope) {
    $scope.quizes = [1, 2, 3];
    $scope.addMoreQuiz = () => {
        let len = $scope.quizes.length + 1;
        $scope.quizes.push(len);
    };
});
app.controller("QuizController", function ($scope) {
    $scope.option = "essay";
    $scope.options = { type: "radio", options: [], extendible: false };

    $scope.onChange = () => {
        if ($scope.option === "" || $scope.option === "essay") {
            return;
        } else if ($scope.option === "true_false") {
            $scope.options.type = "radio";
            $scope.options.options = ["True", "False"];
            $scope.options.extendible = false;
        } else if ($scope.option === "multiple_choice") {
            $scope.options.type = "checkbox";
            $scope.options.options = ["Choice 1", "Choice 2"];
            $scope.options.extendible = true;
        } else if ($scope.option === "single_choice") {
            $scope.options.type = "radio";
            $scope.options.options = ["Choice 1", "Choice 2"];
            $scope.options.extendible = true;
        } else {
            $scope.options.type = "text";
            $scope.options.options = [""];
            $scope.options.extendible = false;
        }
    };
    $scope.addMore = () => {
        let len = $scope.options.options.length || 0;
        len++;
        $scope.options.options.push("Choice " + len);
    };

    $scope.dropOption = (event) => {
        event.preventDefault();
        event.stopPropagation();

        if (event.button === 2) {
            // Right click detected, prevent default behavior
            alert(1);
        }
    };

    // $scope.options = () => {
    //   if ($scope.option === 'true_false') {
    //     $scope.selector = 'radio';

    //   }
    // }
});

app.controller("CreateQuizCtrl", function ($scope) {
    $scope.limit = 5;
    $scope.current_date = null;
    $scope.quiz = {
        title: "",
        start_date: null,
        description: "",
        course_id: "",
        time_limit: 15,
        questions: [],
    };
    $scope.init = () => {
        $scope.addQuestion();
    };

    $scope.changeDate = (event) => {
        const pad = (num) => (num > 9 ? num : "0" + num);
        let date = $scope.current_date;
        if (!date) {
            return;
        }
        const year = date.getFullYear();
        const month = date.getMonth() + 1;
        const day = date.getDate();
        const hour = date.getHours();
        const minute = date.getMinutes();
        const second = date.getSeconds();
        $scope.quiz.start_date = `${year}-${pad(month)}-${pad(day)} ${pad(
            hour
        )}:${pad(minute)}:${pad(second)}`;
    };
    $scope.onChange = (index) => {
        let options = [];

        switch ($scope.quiz.questions[index].type) {
            case "":
            case "text":
                return;
            case "true_false":
                options = [
                    {
                        id: 0,
                        value: "True",
                        placeholder: "True",
                        is_answer: false,
                    },
                    {
                        id: 1,
                        value: "False",
                        placeholder: "False",
                        is_answer: false,
                    },
                ];
                break;

            case "multiple_choice":
            case "single_choice":
                options = [
                    {
                        id: 0,
                        placeholder: "Option 1",
                        value: "",
                        is_answer: false,
                    },
                    {
                        id: 1,
                        placeholder: "Option 2",
                        value: "",
                        is_answer: false,
                    },
                ];
                break;
            default:
                options = [""];
                break;
        }

        $scope.quiz.questions[index].options = options;
    };

    $scope.answerQuestion = (event, question_id, option_id) => {
        $scope.quiz.questions[question_id].options[option_id].is_answer =
            event.target.checked;
    };

    $scope.removeOption = (event) => {
        const element = $(event.target);
        const option = element.closest(".option-wrapper[data-option-id]");
        const question = option.closest("[data-question-id]");
        const option_id = option.data("option-id");
        const question_id = question.data("question-id");
        $scope.quiz.questions[question_id].options.splice(option_id, 1);
    };

    $scope.inputType = (type) => {
        return type === "multiple_choice" ? "checkbox" : "radio";
    };

    $scope.removeQuestion = (index) => {
        $scope.quiz.questions.splice(index, 1);
    };

    $scope.addQuestion = function () {
        const index = $scope.quiz.questions.length;
        $scope.quiz.questions[index] = {
            text: "",
            id: index,
            required: "on",
            type: "multiple_choice",
            options: [
                {
                    id: 0,
                    placeholder: "Option 1",
                    value: "",
                    is_answer: false,
                },
            ],
        };

        $(function () {
            const question = $(".questions .question:last-child");
            if (question.length === 0) {
                return;
            }

            const select = question.find(".type-question-box");

            if (select.length > 0) {
                select.focus();

                question.scrollToBottomIfNeeded();
            }
        });
    };

    $scope.isOptional = (type) => {
        return ["multiple_choice", "single_choice", "true_false"].includes(
            type
        );
    };

    $scope.othersAdded = (question) => {
        return question.options.some((item) => item.value === "Others");
    };

    $scope.typeOption = (event, index) => {
        const value = event.target.value;
        if (event.keyCode === 9 || event.keyCode === 13) {
            event.preventDefault();

            $scope.addOption(index);
        } else if (event.keyCode === 8 && value.trim().length < 1) {
            const wrapper = $(event.target).closest(".option-wrapper");
            const prev = wrapper.prev(".option-wrapper");

            if (prev.length > 0) {
                const input = prev.find("input[data-quiz-option]");
                event.preventDefault();
                $scope.removeOption(event);
                input.focus();
            }
        }

        console.log(event.keyCode);
    };

    $scope.submitQuiz = (event) => {
        event.preventDefault();
        const quiz = $scope.quiz;
        let questions = [];
        $scope.quiz.questions.forEach(({ options, text }, index) => {
            if (text.trim().length === 0) {
                return;
            }
            let question = $scope.quiz.questions[index];
            question.options = options.filter((item) => item.value.length > 0);
            questions.push(question);
        });
        $scope.quiz.questions = questions;

        $scope.api("/quiz/create", $scope.quiz)
            .then((response) => console.log(response))
            .catch((error) => console.log(error));
    };

    $scope.addOption = function (questionIndex, value) {
        if (
            $scope.quiz.questions[questionIndex].options.some(
                (item) => item.value === value
            )
        ) {
            return;
        }

        const index = $scope.quiz.questions[questionIndex].options.length;

        let nextId = 0;
        if (index > 0) {
            nextId =
                $scope.quiz.questions[questionIndex].options[index - 1].id + 1;
        }

        const total = nextId + 1;

        $scope.quiz.questions[questionIndex].options.push({
            id: nextId,
            value: value || "",
            placeholder: "Option " + total,
            is_answer: false,
        });

        $(function () {
            const question = $(
                '.questions [data-question="question_' + questionIndex + '"]'
            );
            const input = question?.find(
                "input[data-quiz-option=" + (total - 1) + "]"
            );

            if (input.length > 0) {
                input.focus();
                input[0].selectionStart = 0;
                input[0].selectionEnd = input.val().length;
                question.scrollToBottomIfNeeded();
            }
        });
    };
});

app.directive("customMultiSelect", function () {
    return {
        restrict: "A",
        priority: 1000,
        link: function (scope, element, attrs) {
            if (element[0].tagName === "SELECT" && element.attr("multiple")) {
                // Extract options and selected values
                const options = element.find("option");
                const selectedValues = element.val() || []; // Handle initial selected values

                // Create custom multi-select container
                const customContainer = app.element(`
          <div class="custom-multi-select">
            <button class="custom-multi-select__trigger">Select Options</button>
            <ul class="custom-multi-select__options">
              </ul>
          </div>
        `);

                // Build list items with checkboxes and labels
                options.each(function (index, option) {
                    const isSelected = selectedValues.includes(option.value); // Check if initially selected
                    const listItem = app.element(`
            <li>
              <label>
                <input type="checkbox" ng-model="selectedOptions.indexOf(${
                    option.value
                }) !== -1" ${isSelected ? "checked" : ""}>
                ${option.text}
              </label>
            </li>
          `);
                    customContainer
                        .find(".custom-multi-select__options")
                        .append(listItem);
                });

                // Bind selected options to an array (replace 'selectedOptions' with your model property)
                scope.selectedOptions = selectedValues; // Initialize with initial selected values

                // Replace original select element with the custom container
                element.replaceWith(customContainer);

                // Handle trigger button click to toggle options list visibility
                const trigger = customContainer.find(
                    ".custom-multi-select__trigger"
                );
                const optionsList = customContainer.find(
                    ".custom-multi-select__options"
                );
                trigger.on("click", function () {
                    optionsList.toggleClass("active");
                });

                // Update selectedOptions on checkbox changes
                customContainer
                    .find('input[type="checkbox"]')
                    .on("change", function () {
                        const value = this.value;
                        const index = scope.selectedOptions.indexOf(value);
                        if (this.checked) {
                            if (index === -1) {
                                scope.selectedOptions.push(value);
                            }
                        } else {
                            if (index !== -1) {
                                scope.selectedOptions.splice(index, 1);
                            }
                        }
                        // Update scope (trigger digest cycle)
                        scope.$apply();
                    });
            }
        },
    };
});

app.service("CustomMultiSelectService", function ($rootScope) {
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (
                    node.tagName === "SELECT" &&
                    node.hasAttribute("multiple")
                ) {
                    this.convertSelect(node);
                }
            });
        });
    });

    observer.observe(document.body, { childList: true });

    this.convertSelect = function (selectElement) {
        // Replace existing code (replace comments with the directive's logic)
        // Extract options and selected values
        const options = app.element(selectElement).find("option");
        const selectedValues = selectElement.value || []; // Handle initial selected values

        // Create custom multi-select container
        const customContainer = app.element(`
      <div class="custom-multi-select">
        <button class="custom-multi-select__trigger">Select Options</button>
        <ul class="custom-multi-select__options">
        </ul>
      </div>
    `);

        // ... (rest of the directive's logic to build list items and handle interactions)

        // Build list items with checkboxes and labels
        options.each(function (index, option) {
            const isSelected = selectedValues.includes(option.value); // Check if initially selected
            const listItem = angular.element(`
        <li>
          <label>
            <input type="checkbox" ng-model="selectedOptions.indexOf(${
                option.value
            }) !== -1" ${isSelected ? "checked" : ""}>
            ${option.text}
          </label>
        </li>
      `);
            customContainer
                .find(".custom-multi-select__options")
                .append(listItem);
        });

        // Bind selected options to an array (replace 'selectedOptions' with your model property)
        scope.selectedOptions = selectedValues; // Initialize with initial selected values

        // Replace original select element with the custom container
        element.replaceWith(customContainer);

        // Handle trigger button click to toggle options list visibility
        const trigger = customContainer.find(".custom-multi-select__trigger");
        const optionsList = customContainer.find(
            ".custom-multi-select__options"
        );
        trigger.on("click", function () {
            optionsList.toggleClass("active");
        });

        // Update selectedOptions on checkbox changes
        customContainer
            .find('input[type="checkbox"]')
            .on("change", function () {
                const value = this.value;
                const index = scope.selectedOptions.indexOf(value);
                if (this.checked) {
                    if (index === -1) {
                        scope.selectedOptions.push(value);
                    }
                } else {
                    if (index !== -1) {
                        scope.selectedOptions.splice(index, 1);
                    }
                }
                // Update scope (trigger digest cycle)
                scope.$apply();
            });
    };

    $rootScope.$on("$destroy", () => {
        observer.disconnect(); // Clean up observer on application destroy
    });
});

app.controller("QuizOptionController", function ($scope) {
    $scope.selection = false;

    $scope.toggleCheckbox = (event) => {
        console.log(event);

        $scope.selection = event.target.value;
    };
});



app.controller("SidebarController", function ($scope) {
    $scope.nav = null;
    $scope.open = false;

    $scope.toggle = (nav) => {
        // let li = $(event.target);
        // if (li.is('li[data-nav]')) {
        //   li = li.closest('li[data-nav]');
        // }
        // const nav = li.attr('data-nav');
        if ($scope.nav === nav) {
            $scope.nav = null;
        } else {
            $scope.nav = nav;
        }
        if ($scope.nav) {
            $scope.openSidebar();
        }
    };
    $scope.changeNav = (nav) => ($scope.nav = nav);
});

app.controller("SettingsController", function ($scope) {
    $scope.active = "personal";
    $scope.openSetting = (nav) => ($scope.active = nav);
});

app.controller("CheckboxController", function ($scope) {
    $scope.checked = false;
    $scope.focused = false;
    $scope.toggleCheck = function (event) {
        $scope.checked = !$scope.checked;
    };
});

app.controller("TodoController", function ($scope) {
    $scope.check = (todo_id) => {
        $scope.api("/todo/complete", {
            todo_id,
        });
    };
});
app.controller("RadioCheckboxController", function ($scope) {
    $scope.onChange = function (event) {
        $scope.checked = !this.checked;
        console.log(this);
        return;
        let element = $(event.target);
        const type = element.attr("type");
        const name = element.attr("name");
        if (type === "radio") {
            let selector = `input[name='${name}'][type='${type}']`;
            let find = $(selector);
            let form = element.closest("form");

            if (form.length > 0) {
                find = form.find(selector);
            }
            if (find.length > 0) {
                find.each(function () {
                    if (!$(this).is(element)) {
                        console.log($(this));
                        $(this).prop("checked", false);
                    }
                });
            }
        }
        element.prop("checked", true);

        //$scope.checked = !event.target.checked;
    };

    $scope.changed = function (event) {
        alert(1);
    };
});

app.controller("ProfileCardController", function ($scope) {
    $scope.open = false;

    $scope.toggleProfileCard = function () {
        $scope.open = !$scope.open;
    };
});

app.controller("ResetPasswordController", function ($scope) {
    $scope.email = "";
});

app.controller("MyCtrl", function ($scope) {
    $scope.myOptions = [
        { name: "Option 1 (port)" },
        { name: "Option 2 (Longer Text)" },
        { name: "Option 3 (Even Longer)" },
        {
            name: "This is a very long option that might need to be positioned differently",
        },
    ];
    $scope.selectedItem = {};

    $scope.onOptionSelected = function (option) {
        console.log("Selected option:", option);
    };
});
app.controller("DropdownController", function ($scope) {
    $scope.show = false;
    $scope.visible = false;
    $scope.selections = {};
    $scope.set = false;
    $scope.texts = [];
    $scope.advisor = "Uzoma";

    $scope.extra = () => {
        let len = $scope.texts.length;
        if (len < 2) {
            return "";
        }
        return "+" + (len - 1);
    };
    $scope.displayText = () => {
        return $scope.texts[$scope.texts.length - 1];
    };
    $scope.toggleDropdown = (event) => {
        $scope.show = !$scope.show;
        $scope.positions = [];
        //$scope.visible = !$scope.visible;

        const parent = $(event.target).closest(".dropdown");
        const menu = $(".dropdown-menu", parent);

        setTimeout(() => {
            const post = menu.offset();
            const bounds = menu[0].getBoundingClientRect();
            const bottom = bounds.top + bounds.height;
            const right = bounds.left + bounds.width;
            let container = $($scope.relative);
            const postLeft = menu.offset().left - container.offset().left;
            const postTop = menu.offset().top - container.offset().top;

            const topDiff = menu.offset().top - container.offset().top;
            const bottomDiff =
                container.innerHeight() - (topDiff + menu.outerHeight());
            const leftDiff = menu.offset().left - container.offset().left;
            const rightDiff =
                container.innerWidth() - (leftDiff + menu.outerWidth());
            const menuWidth = menu.outerWidth();
            const menuHeight = menu.outerHeight();

            const contPost = container.offset();
            const contRight = container.width() + contPost.left;
            const contBottom = container.height() + contPost.top;
            let yPosts = [];
            let xPosts = [];

            if ($scope.positions.length === 0) {
                yPosts = [
                    {
                        bottom: Math.floor(bottomDiff),
                        top: Math.floor(topDiff),
                    },
                ];
                xPosts = [
                    {
                        left: Math.floor(leftDiff),
                        right: Math.floor(rightDiff),
                    },
                ];

                $scope.positions = [
                    [xPosts],
                    [yPosts],
                    [{ width: menuWidth, height: menuHeight }],
                ];
            } else {
                try {
                    [xPosts, yPosts] = JSON.parse(positions);
                } catch (e) {}
            }

            if (container.length === 0) {
                container = $(window);
            }
            let scan = $scope.dir.match(/drop-(top|bottom)-(left|right)/);
            if (scan) {
                let [, Y, X] = scan;

                // x calculators

                let xDIR = "center";
                let yDIR = "middle";

                for (var x in xPosts) {
                    if (xPosts[x] + 2 > menuWidth) {
                        xDIR = x;
                        break;
                    }
                }

                for (var y in yPosts) {
                    if (yPosts[y] + 2 > menuHeight) {
                        yDIR = y;
                        break;
                    }
                }
                $scope.dir = `drop-${yDIR}-${xDIR}`;
                $scope.visible = true;
                $scope.$apply();

                // if (X === 'left') {
                //   if (rightDiff < menuWidth) {
                //     X = 'right';
                //   }
                // }
                // else if (X === 'right') {
                //   if (leftDiff < menuWidth) {
                //     X = 'left';
                //   }
                // }
                // else {
                //   X = 'center';
                // }

                // if (Y === 'top') {
                //   if (topDiff < menuHeight) {
                //     Y = 'bottom';
                //   }
                // }
                // else if (Y === 'bottom') {

                //   if (bottomDiff < menuHeight) {
                //     Y = 'top';
                //     alert(menuHeight+' '+bottomDiff);
                //   }
                // }
                // else {
                //   Y = 'middle';
                // }

                // $scope.dir = `drop-${Y}-${X}`;
                // $scope.visible = true;
                // $scope.$apply();
            }

            // if (!$scope.set) {
            //   if (post.left <= 0 && right >= contRight) {
            //     $scope.dir = $scope.dir.replace(/^drop-(top|bottom)-(left|right)$/, "drop-$1-right");
            //   }
            //   else if (post.left <= 0)  {

            //     $scope.dir = $scope.dir.replace(/^drop-(top|bottom)-(left|right)$/, "drop-$1-left");
            //   }
            //   else if (right >= contRight) {
            //     $scope.dir = $scope.dir.replace(/^drop-(top|bottom)-(left|right)$/, "drop-$1-right");
            //   }
            //   else {
            //     alert(postLeft);
            //     $scope.dir = $scope.dir.replace(/^drop-(top|bottom)-(left|right)$/, "drop-middle-$2");
            //   }
            //   if (bottom > container.innerHeight()) {

            //     $scope.dir = $scope.dir.replace(/^drop-(top|bottom)-(left|right)$/, "drop-top-$2");
            //   }
            //   $scope.visible = true;
            //   // $scope.set = true;
            //   $scope.$apply();
            // }
        }, 100);

        $(document).on("keydown", function (event) {
            if (event.keyCode === 27) {
                $scope.show = false;
                $scope.$apply();
            }
        });
    };

    $scope.inSelection = (value) => {
        return value in $scope.selections;
    };

    $scope.handleMultipleSelection = (event, value) => {
        const dropdown = $(event.target).closest(".dropdown");
        const placeholder = dropdown.attr("data-placeholder");
        const textWrapper = $(".dropdown-text", dropdown);
        const item = $('.dropdown-item[data-value="' + value + '"]', dropdown);
        const pill = dropdown.find('[data-pill="' + value + '"]', textWrapper);

        // check if value exists in selection
        if (value in $scope.selections) {
            delete $scope.selections[value];
        } else {
            //console.log();
            $scope.selections[value] = item.text();
        }
        const texts = Object.values($scope.selections);
        const inputs = Object.keys($scope.selections);

        //console.log($scope.selections);
        $scope.texts = texts.length > 0 ? texts : [placeholder || "Select"];
        $scope.inputs = inputs.length > 0 ? inputs : [];

        if (!event.ctrlKey && !event.metaKey) {
            $scope.show = false;
        }
    };

    $scope.handleOptionSelection = (event, value) => {
        const dropdown = $(event.target).closest(".dropdown");
        const placeholder = dropdown.data("placeholder");
        const inputArea = $(".inputArea", dropdown);
        let item = $('.dropdown-item[data-value="' + value + '"]', dropdown);
        if (item.length === 0) {
            return;
        }
        item = item.eq(0);

        if (value in $scope.selections) {
            delete $scope.selections[value];
        } else {
            $scope.selections = { [value]: item.text() };
        }
        const texts = Object.values($scope.selections);
        const inputs = Object.keys($scope.selections);

        $scope.texts = texts.length > 0 ? texts : [placeholder || "Select"];

        $scope.inputs = inputs.length > 0 ? inputs : [];
    };

    $scope.selectOption = (event) => {
        const value = $(event.target).data("value");

        if ($scope.onchange && $scope.onchange.length > 0) {
            try {
                // eval($scope.onchange + '("' + value + '")');

                eval(`${$scope.onchange}($scope,"${value}")`);
            } catch (e) {
                console.log(e);
            }
        }
        $scope.selection = value;
        if (!/\[\]$/.test($scope.name)) {
            $scope.show = false;
            $scope.handleOptionSelection(event, value);
        } else {
            $scope.handleMultipleSelection(event, value);
        }
    };

    $scope.init = () => {
        $scope.selection = $scope.placeholder;
        if (/\[\]$/.test($scope.name)) {
            $scope.multiple = true;
        }
    };
});

app.directive("clickOutside", function ($document) {
    return {
        restrict: "A",
        scope: {
            clickOutside: "&",
        },
        link: function (scope, element, attrs) {
            element.on("click", function (event) {
                event.stopPropagation();
            });

            $document.on("click", function (event) {
                if (!element[0].contains(event.target)) {
                    scope.$apply(function () {
                        scope.clickOutside();
                    });
                }
            });

            scope.$on("$destroy", function () {
                $document.off("click");
            });
        },
    };
});

app.directive("customScrollbar", function () {
    return {
        link: function (scope, element, attrs) {
            // Watch for changes in content height
            scope.$watch(
                function () {
                    return element[0].scrollHeight;
                },
                function (newHeight, oldHeight) {
                    if (newHeight !== oldHeight) {
                        // Update content height dynamically
                        scope.contentHeight = newHeight + "px";
                    }
                }
            );
        },
    };
});

app.directive("profileSkeleton", function () {
    return {
        templateUrl: "/components/skeletons/profile.html",
    };
});

app.directive("popEnd", function () {
    return {
        restrict: "E",
        required: 'name',
        // replace: true,
        transclude: true,
        templateUrl: "/components/popend.html",
        scope: {
            title: "@",
            show: "="
        },
        link: function (scope) {
            if (typeof scope.dir === "undefined") {
                scope.dir = "end";
            }

            scope.openPopend = function () {
                scope.popendIsOpen = true;
            };

            scope.closePopend = function () {
                scope.popendIsOpen = false;
            };

            scope.togglePopend = function () {
                scope.popendIsOpen = !scope.popendIsOpen;
            };

            scope.$watch("show", function (newValue, oldValue) {
                console.log('changed', newValue, oldValue);
                if (newValue !== oldValue) {
                    scope.popendIsOpen = newValue;
                }
            });

            scope.popendIsOpen = scope.show || false;
        },
    };
});



app.controller("StudentController", function ($scope) {
    $scope.addStudentForm = null;
    $scope.student_id = Location.get("student_id");
    $scope.student = null;
    $scope.open = false;
    $scope.level = null;
    $scope.editStudent = null;

    $scope.show = (event) => {
        let element = $(event.target);
        if (!element.is(".student")) {
            element = element.closest(".student");
        }
        const student_id = element.attr("student_id");

        if (student_id) {
            $scope.student_id = student_id;
            $scope.api(
                "/student", 
                { student_id },
                (response) => {
                    $scope.student = response;
                    const nameParts = response.user.name.split(" ");
                    $scope.firstname = nameParts[0];
                    $scope.lastname = nameParts.length > 1 ? nameParts[1] : "";
                    $scope.middlename =
                        nameParts.length > 2 ? nameParts[2] : "";
                    $scope.$apply();
                    console.log(response);
                    Location.set({ student_id });
                }
            );
        }
    };

    $scope.init = () => {
        if ($scope.student_id) {
            $scope.api(
                "/student",
                { student_id: $scope.student_id },
                (response) => {
                    $scope.student = response;
                    $scope.$apply();
                }
            );
        }
    };

    $scope.back = () => {
        $scope.student_id = null;
        $scope.student = null;
        Location.push("/admin/students");
    };

    $scope.openEditor = () => {
        $scope.editStudent = true;
    };
    $scope.closeEditor = () => {
        $scope.editStudent = false;
    };
    $scope.openForm = () => {
        $scope.addStudentForm = true;
    };
    $scope.closeForm = () => {
        $scope.addStudentForm = false;
    };
});

app.directive("viewStudentSkeleton", function () {
    return {
        template: `<div class="loading-skeleton flex flex-col lg:m-5 lg:p-8">
    <div class="flex flex-col lg:flex-row text-center justify-center gap-5 items-center lg:text-left lg:justify-start p-4">
      <div class="skeleton w-28 h-28 object-cover rounded-full"></div>
      <div class="flex flex-col gap-2">
        <span class="text-2xl lg:text-3xl font-bold mb-3 skeleton w-[150px]"></span>
        <span class="font-bold skeleton w-[100px]"></span>
      </div>
    </div>

    <div class="flex-1">
    
      <div class="p-4 my-2 flex flex-col">
        <span class="font-bold mb-4 skeleton w-[120px]"></span>
        <div class="flex flex-col lg:flex-row justify-between flex-wrap gap-3">
          <div class="flex lg:flex-col gap-3">
            <span class="skeleton w-[60px]"></span> 
            <span class="skeleton w-[100px]"></span>
          </div>


          <div class="flex lg:flex-col gap-3">
            <span class="skeleton w-[50px]"></span> 
            <span class="skeleton w-[90px]"></span>
          </div>

          
          <div class="flex lg:flex-col gap-3">
            <span class="skeleton w-[55px]"></span> 
            <span class="skeleton w-[90px]"></span>
          </div>


          <div class="flex lg:flex-col gap-3">
            <span class="skeleton w-[40px]"></span> 
            <span class="skeleton w-[30px]"></span>
          </div>

          

          <div class="flex lg:flex-col gap-3">
            <span class="skeleton w-[60px]"></span> 
            <span class="skeleton w-[150px]"></span>
          </div>
          
        

        </div>
      </div>


      <div class="p-4 my-2">
        <div class="font-bold mb-4 skeleton w-[60px]"></div>
        <div class="mt-2 grid grid-cols-1 lg:grid-cols-3 gap-3 lg:gap-5">
          <div class="overflow-hidden grid-span-1  rounded-md p-4 skeleton ">

          </div>

          <div class="overflow-hidden grid-span-1 rounded p-4 skeleton">
              
          </div>
          
  
          <div class="overflow-hidden grid-span-1 rounded p-4 skeleton h-20">
              
          </div>
        </div>
      </div>
    </div>

</div>`,
    };
});
