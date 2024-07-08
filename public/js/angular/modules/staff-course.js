app.controller("StaffCourseController", function ($scope) {
    $scope.results = {};
    $scope.uploader = { student: [], data: {} };

    $scope.results_loaded = false;
    $scope.active_nav = null;
    $scope.courses_loaded = false;
    $scope.all_results = [];
    $scope.courses = [];
    $scope.propertyName = "created_at";
    $scope.reverse = false;
    $scope.old_results = [];
    $scope.filter = "all";

    $scope.enrollments = {
        students: [],
    };

    $scope.displayStudentsEnrolledForCourse = (data) => {
        
        return $scope.api({
            loadingText: 'Fetching Data',
            url: "/app/staff/courses/students",
            data, 
            success(res){
                if (res.submitted) {
                    $scope.ViewCourseResult(res);
                } else {
                    $scope.enrollments = res;
                    $scope.route("add_result");
                }
            }
        });
    };

    $scope.uploadOGMR = (data) => {
        
        swal({
            text: 'Select File',
            content: {
                element: 'input',
                attributes: {
                    type: 'file',
                    className: 'input',
                    id: 'upload_ogmr_excel'
                }
            },
            buttons: {
                cancel: true,
                upload: {
                    text: 'Upload',
                },
            },
        })
        .then(value => {
            if (value) {
                const file = $('#upload_ogmr_excel')[0].files;

                if (file.length === 0) {
                    return $scope.uploadOGMR();
                }

                $scope.api({
                    loadingText: 'Uploading Result...',
                    url: '/app/staff/results/upload_ogmr',
                    data: data,
                    files: {
                        result: file[0]
                    },
                    success(excelResult) {

                        $scope.enrollments.students = $scope.enrollments.students.map(student => {
                            
                                let no_results = true;
                                let total = 0;
                                for(var i = 0; i < excelResult.length; i++) {
                                    if (student.reg_no == excelResult[i].reg_no) {
                                        const current = excelResult[i];
                                        
                                        student.results = {...student.results, ...current}
                                        no_results = false;
                                        total++;
                                        break;
                                        
                                        
                                    }

                                }
                                if (no_results) {
                                    student.results = {test:null, exam: null, score: null, remark: null, grade:null}
                                }
                               
                            

                            return student;
                        })
                        Toast('Result results populated')

                    }
                })
            }
        })
    }

    $scope.bootAddResult = () => {
        const tips = localStorage.getItem('add_results_tips');

        if (!tips) {

            swal({
                title: 'Result Upload Tips',
                content: {
                    element: 'ol',
                    attributes: {
                        innerHTML: `
                            <li>
                                Click <b class="text-warning">Upload Excel</b> to upload OGMR in excel format.
                            </li>
                            <li>
                                Click <b>Save As Draft</b> to save results that will. These results will never be approved until FINISHED
                            </li>
                            <li>
                                Click <b class="text-primary">FINISH</b> to upload the results for approval.
                            </li>`,
                        className: 'items-start'
                    }
                },
                buttons: {
                    confirm: {
                        value: true,
                        text: 'OKAY',
                    },
                }
            })
            .then(confirm => {
                if (confirm) {
                    localStorage.setItem('add_results_tips', 'seen');
                }
            });
        }
    }

    $scope.updateGrade = (event, index) => {
        const results = $scope.enrollments.students[index].results;

        let exam = 0;
        let lab = 0;
        let test = 0;
        if (typeof results === "object" && results !== null) {
            exam = parseInt(results.exam || exam);
            lab = parseInt(results.lab || lab);
            test = parseInt(results.test || test);

            let score = exam;
            score += lab;
            score += test;
            let grade = "F";

            switch (true) {
                case $scope.enrollments.has_practical && lab < 1:
                    grade = "F";
                    break;
                case score > 69:
                    grade = "A";
                    break;
                case score > 59:
                    grade = "B";
                    break;
                case score > 49:
                    grade = "C";
                    break;
                case score > 44:
                    grade = "D";
                    break;
                case score > 39:
                    grade = "E";
                    break;
            }
            $scope.enrollments.students[index].results.score = score;
            $scope.enrollments.students[index].results.grade = grade;
            $scope.enrollments.students[index].results.remark =
                grade === "F" ? "FAILED" : "PASSED";
        }
    };

    $scope.ViewCourseResult = ({ course_id, semester, session }) => {
        return $scope.api({
            url: "/app/staff/course/results",
            data: {
                course_id,
                semester,
                session,
            },
            success: function (res) {
                console.log({ event: this.event, response: res });
                $scope.view_course_results = res;
                $scope.route("course_results");
            },
            error: (res) => {
                console.log(res);
            },
        });
    };

    $scope.uploadResults = (event) => {
        const parent = $(event.target).closest("div");
        const buttons = parent.find("button");

        const { students, course_id, session } = $scope.processedData();

        $scope.api({
            url: "/app/staff/results/add",
            askPin: true,
            trigger: buttons,
            data: {
                students,
                course_id,
                session,
            },
            success: (res) => {
                $scope.loadResultSessions(session);
                // $scope.loadResults();
                $scope.route("index");
            },
        });
    };
    $scope.processedData = () => {
        let records = $scope.enrollments;

        records.students = records.students.map((student) => {
            return {
                lab: student.results?.lab,
                test: student.results?.test,
                exam: student.results?.exam,
                score: student.results?.score,
                reg_no: student.reg_no,
                ...student,
            };
        });
        return records;
    };

    $scope.saveResultsAsDraft = (event) => {
        const parent = $(event.target).closest("div");
        const buttons = parent.find("button");

        const { students, course_id, session } = $scope.processedData();

        return $scope.api({
            trigger: buttons,
            url: "/app/staff/results/save_draft",
            data: {
                students,
                course_id,
                session,
            },
            success: (res) => {
                // $scope.loadResults();
                $scope.loadResultSessions(session);
                $scope.route("index");
            },
        });
    };
    $scope.onFilter = (filter) => {
        if (!filter || filter === 'all') {
            $scope.all_results = $scope.old_results;
        }
        else if (filter !== "all") {
            for (var i = 0; i < $scope.all_results.length; i++) {
                const current = $scope.all_results[i];
                if (current.session === $scope.active_nav) {
                    $scope.all_results[i].results = current.__results.filter(
                        (result) => result.status === filter
                    );

                    break;
                }
            }
        }
    };

    $scope.canEdit = (obj) => {
        if (obj.status === "APPROVED") {
            return false;
        }
        try {
            return $scope.config.account.id === obj.id;
        } catch (e) {}
        return false;
    };

    $scope.loadResults = (page) => {
        let data = {};
        if (page) {
            data.page = page;
        }
        return $scope.api({
            url: "/app/staff/results/index",
            data: {
                page: $scope.results_page_counter,
            },
            success: (results) => {
                // $scope.all_results = $scope.all_results.concat(results);
                $scope.results_page_counter += 1;
            },
            done: () => {
                $scope.results_loaded = true;
            },
        });
    };

    $scope.loadCourses = (page) => {
        return $scope.api({
            url: "/app/staff/courses/index",
            data: {
                page: $scope.result_page_counter,
            },
            success: (courses) => {
                $scope.courses = $scope.courses.concat(courses);
                $scope.courses_page_counter += 1;
            },
            done: () => {
                $scope.courses_loaded = true;
            },
        });
    };

    $scope.changeNav = (nav) => {
        $scope.active_nav = nav;
    };

    $scope.addResults = (obj) => {
        let options = "";
        obj.no_results.forEach((course) => {
            options += `<option value="${course.id}">${course.code}</option>`;
        });

        const popup = () => {
            return swal({
                text: "Select Course",
                content: {
                    element: "div",
                    attributes: {
                        innerHTML: `<select id="add_results" class="input">
                            ${options}
                        </select>`,
                    },
                },
                buttons: {
                    cancel: true,
                    confirm: {
                        text: "Add Result",
                    },
                },
            }).then((confirmed) => {
                if (confirmed) {
                    const selection = $("#add_results").val();

                    if (!selection) {
                        return popup();
                    }
                    $scope.displayStudentsEnrolledForCourse({
                        course_id: selection,
                        session: obj.session,
                    });
                }
            });
        };

        return popup();
    };

    $scope.loadResultSessions = (active_nav) => {
        $scope.method("get").api({
            url: "/app/staff/results/sessions",
            success: (results) => {
                if (typeof results === "object" && results !== null) {
                    results = Object.values(results);
                }
                const old_results = results
                    .map((item) => {
                        item.results = [];
                        item.__results = [];
                        item.no_results = [];
                        item.courses.forEach((course) => {
                            if (course.result) {
                                item.results.push(course.result);
                                item.__results.push(course.result);
                            } else {
                                item.no_results.push(course);
                            }
                        });
                        return item;
                    });
                $scope.old_results = old_results;
                $scope.all_results = old_results;

                $scope.active_nav = active_nav || $scope.all_results[0].session;
            },
        });
    };

    $scope.boot = () => {
        $scope.loadResultSessions();
        $scope.loadCourses();
        $scope.loadResults();
    };
});
