
app.controller('ImportClassListController', function($scope){

    $scope.importClassList = (event, class_id) => {
        const button = event.target;
        
        const input = $('<input>', {
            type: 'file',
            class: 'hidden-input'
        });

        $('body').append(input);

        input.click();
        input.on('click', function(e) {
            button.disabled = true;
            alert('focused')
        })
            .on('blur', function(e){
                alert('blurred')
            })

        input.on('change', (e) => {

            
            ajax.http({
                quiet: false,
                loadingText: 'Processing',
                successLoadingText: 'Importing',
                url: '/app/class/import',
                files: {
                    class_list: e.target.files[0]
                },
                data: {
                    class_id: class_id
                },
                done: () => {
                    button.disabled = false;
                }
            })
        });

    };
})