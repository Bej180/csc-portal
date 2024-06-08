app.service('StudentService', function() {
    /**
     * createStudentAccount
     * Creates a new student account with the provided data and sends a verification email.
     * @param {Object} academicClass - Academic class object associated with the student.
     */

    this.create = (data, callback) => {
        return api(
            "/app/admin/student/create",
            data,
            callback
        );
    };


    this.register = (data, callback) => {
        return api({
            url: '/doRegister',
            data: data,
            silent: false,
            success: callback,
            error(res) {

            }
        });
    }


});