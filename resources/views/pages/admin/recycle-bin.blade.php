<x-template nav="recycle" title="Recycle Bin" controller="AdminRecyclebinController" ng-init="recycleBin()">

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                Recycle Bin 
            </div>
        </div>
        <div class="card-body">
            <div class="responsive-table no-zebra">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date Deleted</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="trash in deleted" ng-click="takeAction(trash, $index)">
                            <td ng-bind="trash.name"></td>
                            <td ng-bind="trash.deleted_at"></td>
                            <td ng-bind="trash.type"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-template>