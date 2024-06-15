
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
    $scope.limit = "10";
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

    $scope.searchFor = (holder, timeout = 0, scope) => {
        $scope.holder = holder;
        $scope.url = "/app/search/" + holder;
        const limit = $scope.limit;
        const page = $scope.page;

        const queries = $scope.scanned();

        setTimeout(() => {
            $scope.api(
                $scope.url,
                { queries, limit, page },
                (res) => {
                    $scope.results = res.data;

                    if (res.current_page) {
                        $scope.prev_page = res.current_page - 1;
                        $scope.next_page = res.current_page + 1;
                        $scope.from = res.from;
                        $scope.to = res.to;
                    }

                    $scope.$apply();
                },
                (error) => {
                    $scope.results =
                        $scope.results && $scope.results.length > 0 ? [] : null;
                }
            );
        }, timeout);
    };

    angular.element($window).bind("scroll", function () {
        console.log(1);
        if (
            $window.innerHeight + $window.scrollY >=
            document.body.offsetHeight
        ) {
            $scope.searchFor($scope.holder);
            $scope.$apply();
        }
    });
});

app.controller("CardController", function ($scope) {
    $scope.minimize = false;

    $scope.toggleWindow = () => {
        $scope.minimize = !$scope.minimize;
    };
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
                ["staffs", "students", "admins"].includes($scope.account)
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
      <div class="dropdown">
        <button class="dropdown-toggle relative z-[999] text-sm">Select Options</button>
        <ul class="dropdown-menu">
        </ul>
      </div>
    `);

        options.each(function (index, option) {
            const isSelected = selectedValues.includes(option.value); // Check if initially selected
            const listItem = angular.element(`
        <li>
          <label>
            <input type="checkbox" class="checkbox" ng-model="selectedOptions.indexOf(${
                option.value
            }) !== -1" ${isSelected ? "checked" : ""}>
            ${option.text}
          </label>
        </li>
      `);
            customContainer.find(".dropdown-menu").append(listItem);
        });

        // Bind selected options to an array (replace 'selectedOptions' with your model property)
        scope.selectedOptions = selectedValues; // Initialize with initial selected values

        // Replace original select element with the custom container
        element.replaceWith(customContainer);

        // Handle trigger button click to toggle options list visibility
        const trigger = customContainer.find(".dropdown-toggle");
        const optionsList = customContainer.find(".dropdown-menu");
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

    $scope.isActive = (nav) => $scope.nav === nav;
    $scope.setActiveNav = (nav) => ($scope.nav = nav);
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
    $scope.todos = [];
    $scope.currentPage = 1;

    $scope.check = (todo_id) => {
        $scope.api("/app/todo/complete", {
            todo_id,
        });
    };

    $scope.moreTodos = () => {
        $scope.api(
            '/app/todo/index',
            {
                page: $scope.currentPage
            },
            res => {
                $scope.todos = $scope.todos.concat(res);
                $scope.currentPage++;
            }
        )
    }


    $scope.initTodo = () => {
        $scope.api(
            '/app/todo/index',
            {},
            res => {
                $scope.todos = res;
            }
        )
    };


    $scope.deleteTask = (task_id) => {

        return $.confirm('Are you sure you want to delete this task?', {
            accept: function() {
                $scope.api(
                    '/app/todo/delete',
                    {
                        task_id
                    },
                    res => {
                        $scope.todos = res.todos;
                    }
                );
            }
        })

    };

    $scope.saveTask = (task) => {
        return $scope.api(
            "/app/todo/store",
            {
                task,
            },
            (res) => {
                $('.todo-list')[0].scrollTop = 0;
                $scope.todos = res.todos;
            }
        );
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
});

app.controller("ProfileCardController", function ($scope) {
    $scope.open = false;

    $scope.toggleProfileCard = function () {
        $scope.open = !$scope.open;
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
                //
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
            //
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

app.controller("InputController", function ($scope) {
    $scope.onInput = (event) => {
        console.log(event);
    };
});

app.directive("popEnd", function () {
    return {
        restrict: "E",
        required: "name",
        // replace: true,
        transclude: true,
        templateUrl: "/components/popend.html",
        scope: {
            title: "@",
            show: "=",
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
                console.log("changed", newValue, oldValue);
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
            $scope.api("/student", { student_id })
                .then((response) => {
                    $scope.student = response;
                    const nameParts = response.user.name.split(" ");
                    $scope.firstname = nameParts[0];
                    $scope.lastname = nameParts.length > 1 ? nameParts[1] : "";
                    $scope.middlename =
                        nameParts.length > 2 ? nameParts[2] : "";
                    $scope.$apply();
                    console.log(response);
                    Location.set({ student_id });
                })
                .catch(async (error) => {
                    const err = await error;
                    console.log(err);
                });
        }
    };

    $scope.init = () => {
        if ($scope.student_id) {
            $scope.api("/student", { student_id: $scope.student_id })
                .then((response) => {
                    $scope.student = response;
                    $scope.$apply();
                })
                .catch((error) => log(error));
        }
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

app.controller("SemesterController", function ($scope) {
    $scope.decreaseSemester = (start) => {
        $scope.start--;
        $scope.end--;
    };

    $scope.increaseSemester = (start) => {
        $scope.start++;
        $scope.end++;
    };
});
