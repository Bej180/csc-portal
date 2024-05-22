<x-body title="Registered Courses">




    @if (count($courses) === 0)

        <div class="text-muted" style="text-align:center;margin-top:30vh">
            No Academic Record yet
        </div>
    @else
        <h1 class="border-bottom p-2">
            Registers Courses ({{ $courses->count() }})
        </h1>
        <table style="width:100%">
            <thead>
                <tr>
                    <th>Course Title</th>
                    <th>Course Code</th>
                    <th>Semester</th>
                    <th>Level</th>
                    <th>Unit</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($courses as $n => $course)
                    <tr>
                        <td>{{ $n + 1 }}. {{ $course->name }}</td>
                        <td>{{ $course->code }}</td>
                        <td>{{ $course->semester }}</td>
                        <td>{{ $course->level }} Level</td>
                        <td>{{ $course->unit }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <div class="py-2" style="text-align:center">
        <a href="{{ route('course.registration_form') }}" class="btn btn-primary">Register for New Courses</a>
    </div>


</x-body>
