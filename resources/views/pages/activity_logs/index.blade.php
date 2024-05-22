<h1>Activity Logs</h1>

    @if (count($activityLogs) > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Log Name</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activityLogs as $log)
                    <tr>
                        <td>{{ $log->user->name }}</td>
                        <td>{{ $log->log_name }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->ip_address }}</td>
                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No activity logs found.</p>
    @endif