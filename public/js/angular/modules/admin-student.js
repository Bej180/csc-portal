/**
 * StudentController
 * Controller responsible for managing student-related data and actions.
 * @param {Object} $scope - AngularJS scope object for data binding.
 */
app.controller("AdminStudentController", function ($scope) {
    // Initialize variables
    $scope.show_student = null; // Selected student to display
    $scope.addStudent = null; // Data for adding a new student
    $scope.academicClass = null; // Selected academic class
    $scope.limit = "10"; // Limit for student records
    $scope.student = {}; // Student data object
    $scope.no_student = false;
    $scope.students = [];
    $scope.searchinput = null;
    $scope.currentPage = 1;
    
    $scope.loaded = false;
    $scope.sorting = {
        attr: 'cgpa',
        order: 'ASC'
    };
    /**
     * setClass
     * Sets the selected academic class.
     * @param {Object} set - Academic class object to set.
     */
    $scope.setClass = (set) => {
        $scope.academicClass = set;
    };

    /**
     * showStudent
     * Retrieves and displays the details of a student.
     * @param {string} student_id - The ID of the student to display.
     */
    $scope.showStudent = (student) => {
        $scope.show_student = student;
        $scope.popend("show_student");
        
    };

    /**
     * createStudentAccount
     * Creates a new student account with the provided data and sends a verification email.
     * @param {Object} academicClass - Academic class object associated with the student.
     */
    $scope.createStudentAccount = (academicClass) => {
        return $scope.api(
            "/app/admin/student/create",
            $scope.student,
            (res) => ($scope.students = [res.data].concat($scope.students))
        );
    };

    $scope.showEnrollmentDetails = (enrollments) => {
        $scope.enrollment_details = enrollments;
        $scope.route('enrollment_details');
    };

    $scope.addMoreCourseToEnrollment = (event, {enrollment_id}) => {
        const button = event.target;
        button.disabled = true;

        swal({
            text: 'Course Code',
            content: {
                element: 'input',
                attributes: {
                    placeholder: 'Enter Course Code',
                },
            },
            buttons: {
                cancel: true,
                confirm: {
                    text: 'Search',
                }
            }
        })
        .then(code => {
            if (code) {
                    $scope.api({
                        url: '/app/admin/courses/search',
                        data: {
                            search: code
                        },
                        success: (res) => {
                            
                            if (res.data.length > 0) {
                                const course = res.data[0];
                                const course_id = course.id;
                                const course_code = course.code;
                            
                                swal({
                                    text: `Are you sure you want to add ${course_code}?`,
                                    buttons: {
                                        cancel: true,
                                        confirm: {
                                            text: 'Add Course',
                                        },
                                    },
                                })
                                .then(confirmed => {
                                    if (confirmed) {
                                        $scope.api({
                                            url: '/app/admin/student/enrollments/course/add',
                                            askPassword: true,
                                            data: {
                                                course_id,
                                                enrollment_id
                                            },
                                            success: ({data}) => {
                                                $scope.enrollment_details.courses = $scope.enrollment_details.courses.concat(data);
                                                $scope.enrollment_details.totalUnits = $scope.enrollment_details.courses.reduce(
                                                    (acc, current) => acc + current.units,
                                                    0
                                                );
                                            }
                                        })
                                    }
                                })
                            }
                        },
                    })
            }
        })
        .finally(() => {
            button.disabled = false;
        })

    }

    $scope.deleteCourseFromEnrollment = (event, enrollment) => {
        const tr = $(event.target).closest('tr');
        

        tr.addClass('danger-alert');

        swal({
            text: `Are you sure you want to remove ${enrollment.code}?`,
            dangerMode: true,
            icon: 'warning',
            buttons: {
                cancel: true,
                confirm: {
                    text: 'Yes, Delete',
                    value: true,
                }
            }
        })
        
        .then(confirmed => {
            tr.removeClass('danger-alert');

            if (confirmed) {
               
                
                $scope.api({
                    url: '/app/admin/student/enrollments/course/drop',
                    data: {
                        id: enrollment.id
                    },
                    askPassword: true,
                    beforeSend: () => {
                        tr.addClass('faint');
                    },
                    success: (data) => {
                        $scope.enrollment_details.courses = $scope.enrollment_details.courses.filter(
                            item => item.id !== enrollment.id
                        );
                        $scope.enrollment_details.totalUnits = $scope.reduce($scope.enrollment_details.courses, 'units');
                        
                        
                    },
                    error: (err) => {
                        tr.removeClass('faint');

                    },
                });
            }
        }) 
    }

    $scope.editStudent = (student) => {
        $scope.route('edit_student');
        $scope.edit_student = student;
    }
    $scope.studentEnrollments = (reg_no) =>{
        return $scope.api(
            '/app/student/enrollments',
            {
                reg_no
            },
            res => {
                $scope.student_enrollments = Object.values(res);
            }
        );
    }

    $scope.changeEnrollment = (details, column) => {
        const selection = details[column];
        
        const objs = {
            session: {
                title: 'Enter Session below',
                content: {
                    element: 'input',
                    attributes: {
                        className: 'input mk-session',
                        type: 'text',
                        id:'enroller',
                        placeholder: 'YYYY/YYYY',
                        ...(selection && {value:selection})
                    }
                }
            },
            level: {
                text: 'Choose Level',
                content: {
                    element: 'select',
                    attributes: {
                        id: 'enroller',
                        innerHTML: `
                            <option ${ selection == 100 && 'selected' }>100</option>
                            <option ${ selection == 200 && 'selected' }>200</option>
                            <option ${ selection == 300 && 'selected' }>300</option>
                            <option ${ selection == 400 && 'selected' }>400</option>
                            <option ${ selection == 500 && 'selected' }>500</option>
                        `,
                        className: 'input mk-session',
                    },
                }
            },
            semester: {
                text: 'Choose Semester',
                content: {
                    element: 'select',
                    attributes: {
                        id: 'enroller',
                        innerHTML: `
                            <option ${ selection == 'HARMATTAN' && 'selected' }>HARMATTAN</option>
                            <option  ${ selection == 'RAIN' && 'selected' }>RAIN</option>
                        `,
                        className: 'input mk-session',
                    },
                }
            },
        }
        if (!objs[column]) return;

        swal(objs[column])
            .then(value => {
                if (value) {
                    const element = document.getElementById('enroller');
                    if (element && element.value.trim()) {
                        const value = element.value.trim();
                        if (selection == value) {
                            return swal({
                                text: column.toUpperCase() + ' was not changed because it still the same like it was',
                                timer: 5000,
                                buttons: false,
                            });
                        }
                        
                        
                        return $scope.api({
                            askPassword: true,
                            url: '/app/admin/enrollments/update',
                            data: {
                                enrollment_id: details.enrollment_id,
                                [column]: value
                            },
                            success: function(data) {
                                $scope.enrollment_details = details;
                                $scope.enrollment_details[column] = value;
                            }
                        });
                    }
                    $scope.changeEnrollment(details, column);
                }
            })
           
    }

    $scope.deleteEnrollment = async (event, index, {enrollment_id, reg_no}) => {
        const button = $(event.target);
        const tr = button.closest('tr');


        const confirmed = await swal({
            content: {
                element: 'div',
                attributes: {
                    innerText: 'Are you sure you want to delete this enrollment?'
                }

            },
            icon: 'warning',
            dangerMode: true,
            buttons: {
                cancel: true,
                confirm: {
                    text: 'Accept',
                }
            }
        });
        if (confirmed) {
            return $scope.api({
                url: '/app/admin/enrollments/delete',
                data: {
                    enrollment_id,
                    reg_no
                },
                askPassword: true,
                loadingText: 'Deleting',
                beforeSend: () => {
                   
                    tr.slideUp();
                },
                success: (data) => {
                    $scope.student_enrollments.splice(index, 1);
                },
                error: (err) => {
                    tr.slideDown();
                }
            });
        }
        
        
    }
    $scope.bootStudentAccounts = () => {
        
        $scope.api(
            "/app/students/index",
            (students) => {
                $scope.students = students;
                $scope.loaded = true;
            },
            err => {
                $scope.loaded = true;
            }
        );
    };

    $scope.more = () => {
        $scope.api(
            "/app/students/index",
            {
                search: $scope.searchinput,
                page: $scope.page,
            },
            (students) => {
                $scope.students = $scope.students.concate(students);
                $scope.currentPage += 1;
            }
        );
    };

    
    $scope.searchStudent = (search) => {
    
        $scope.searchinput = search;
        $scope.api(
            "/app/students/index",
            {
                search: search,
                page: $scope.page,
            },
            (res) => {
                $scope.students = res.data;
            }
        );
    };

    $scope.ResetPassword = (account, use) => {
       
        return $scope.api(
            '/admin/user/reset_password',
            {
                new_password: use,
                id: account.id,
            }
        );
    }
    $scope.sortStudent = () => {
        if ($scope.sorting.attr) {
            $scope.api(
                '/app/students/index',
                {
                    search: $scope.searchinput,
                    page:$scope.page,
                    sort: [$scope.sorting.attr, $scope.sorting.order]
                },
                res => {
                    $scope.students = res.data;
                }
            )
        }
    }


});
