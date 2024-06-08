app.controller("StaffLabResultsController", function ($scope) {
    $scope.initializePage = () => {
        $scope.api("/app/staff/lab_scores/index", {}, (res) => {
            $scope.pending_results = res.PENDING;
            $scope.approved_results = res.APPROVED;
        });
    };

    $scope.approveLabScore = (result) => {
        return $scope.api(
            "/app/hod/results/approve",
            {
                results_id: result.reference_id,
            },
            (res) => {
                $scope.pending_results = res.data.PENDING;
                $scope.approved_results = res.data.APPROVED;
            }
        );
    };
});