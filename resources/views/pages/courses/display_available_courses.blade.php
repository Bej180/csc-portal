<x-body :title="$title">
    <style>
        tr {
            border-top: 1px solid #ddd;

        }

        td,
        th {
            padding: 5px;
        }
    </style>
    <form class="card" method="POST" action="{{ route('register.courses') }}">
        @csrf
        <div class="card-header">
            Choose Courses
        </div>
        <div class="card-body">
            <table style="width:90%;margin:auto;">
                <thead>
                    <tr>
                        <th>Course Title</th>
                        <th>Course Code</th>
                        <th>Semester</th>
                        <th>Level</th>
                        <th>Unit</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    @php
                        
                        $options = [];
                    @endphp

                    @foreach ($courses as $course)
                        @php
                            $option = $course->mandatory == 1 ? 'Mandatory' : 'Elective';
                        @endphp
                        @if (!in_array($option, $options))
                            <tr>
                                <td colspan="6" style="font-weight:bold; text-align:center;">
                                    {{ $option }}
                                </td>
                            </tr>
                            @php $options[] = $option; @endphp
                        @endif

                        <tr>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->code }}</td>
                            <td>{{ $course->semester }}</td>
                            <td>{{ $course->level }} Level</td>
                            <td>{{ $course->unit }}</td>
                            <td><input type="checkbox" name="course[]" value="{{ $course->id }}"
                                    {{ $course->mandatory == 1 ? 'checked' : '' }}></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <input type="submit" value="Register Courses" class="float-right btn btn-primary" />
        </div>
    </form>

</x-body>
