app.controller('AdminRecyclebinController', function($scope){
    $scope.deleted = [];

    $scope.takeAction = (trash, index) => {

        swal({
            title: 'Take Action',
            content: {
                element: 'div',
                attributes: {
                    innerHTML: `
                        <div class="flex justify-start flex-col gap-2">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>Name:</td> <td>${trash.name}</td>
                                    </tr>
                                    <tr>
                                        <td>Type:</td> <td>${trash.type}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="horizontal-divider"></div>
                            <table>
                                <tbody>
                                    <tr>
                                        <td>Created:</td> <td>${trash.created_at}</td>
                                    </tr>
                                    <tr>
                                        <td>Type:</td> <td>${trash.deleted_at}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    `,
                }
            },
            buttons: {
                cancel: true,
                restore: {
                    text: 'Restore',
                    value: 'restore',
                    className: 'btn-primary'
                },
                delete: {
                    text: 'Delete',
                    value: 'delete',
                    className: 'btn-danger'
                }
            }
        })
        .then(selection => {
            if (selection) {
                if (selection === 'restore') {
                    $scope.api({
                        url: '/app/admin/recyclebin/restore',
                        data: trash,
                    })
                }
                else if (selection === 'delete') {}
            }
        });
    }

    $scope.recycleBin = () => {
        $scope.api({
            url: '/app/admin/recyclebin',
            success: (response) => {
                if (response.courses.length > 0) {
                    $scope.deleted = $scope.deleted.concat(response.courses.map(course => {
                        course.type = 'Course';
                        return course;
                    }));
                }
                if (response.users.length > 0) {
                    $scope.deleted = $scope.deleted.concat(response.users.map(user => {
                        user.type = user.role;
                        return user;
                    }));
                }
                if (response.results.length > 0) {
                    $scope.deleted = $scope.deleted.concat(response.results.map(result => {
                        result.type = 'Result';
                        return result;
                    }));
                }
                
            }
        })
    }
})