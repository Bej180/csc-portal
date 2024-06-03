app.controller("HODResultsController", function ($scope) {
    $scope.pending_results = [];
    $scope.approved_results = [];
    $scope.initialized = false;
    $scope.view_course_results = [];
    $scope.search_input = null;
    $scope.sorting = {
        attr: "id",
        order: "ASC",
    };

    $scope.initializePage = () => {
        $scope.api(
            "/app/hod/results/index",
            {},
            (res) => {
                $scope.pending_results = Object.values(res.pendingResults);
                $scope.approved_results = Object.values(res.approvedResults);
                $scope.initialized = true;
                console.log(res);
            },
            (error) => {
                $scope.initialized = true;
            }
        );
    };

    $scope.SearchIn = (text) => {
        $scope.api(
            "/app/hod/results/index",
            {
                search: text,
            },
            (res) => {
                $scope.pending_results = Object.values(res.pendingResults);
                $scope.approved_results = Object.values(res.approvedResults);
                $scope.initialized = true;
            },
            (error) => {
                $scope.initialized = true;
            }
        );
    };

    $scope.sortResults = () => {
        $scope.api(
            "/app/hod/results/index",
            {
                sort: [$scope.sorting.attr, $scope.sorting.order],
            },
            (res) => {
                $scope.pending_results = Object.values(res.pendingResults);
                $scope.approved_results = Object.values(res.approvedResults);
                $scope.initialized = true;
            },
            (error) => {
                $scope.initialized = true;
            }
        );
    };

    $scope.approveResult = (result) => {
        console.log({ result });
        return $scope.api(
            "/app/hod/results/approve",
            {
                results_id: result.reference_id,
            },
            (res) => {
                $scope.pending_results = res.data.pendingResults;
                $scope.approved_results = res.data.approvedResults;
            }
        );
    };

    $scope.viewResults = (results) => {
        $scope.view_course_results = results;
        $scope.route("view_results");
    };
});

app.controller("HODStaffController", function ($scope) {
    

   
});


