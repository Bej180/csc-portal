app.controller('AdminRecyclebinController', function($scope){
    $scope.deleted = [];

    $scope.takeAction = (trash, index) => {

        swal({
            title: 'Take Action',
            dangerMode: true,
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
                let data = {
                    id: trash.id,
                    type: trash.type
                };

                let confirm = false;

                if (selection === 'restore') {
                    data.action = 'restore';
                }
                else if (selection === 'delete') {
                    data.action = 'delete';
                    confirm = 'Are you sure you want to permanently delete this '+trash.type;
                }

                if (data.action) {
                    
                    $scope.api({
                        url: '/app/admin/recyclebin/take_action',
                        data: data,
                        confirm: confirm,
                        success: (res) => {
                            $scope.deleted.splice(index, 1);
                        }
                    });
                }
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