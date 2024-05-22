@php
    $authUser = auth()->user();
    $staff = $authUser->profile;
    $courses = $staff->courses;
@endphp

<x-popend name="upload_materials">
    <div class="header1 primary-text my-3">Upload Material</div>
    <form action="{{ route('upload.material') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="flex flex-col gap-4">

            <div ng-show='course' class='text-xs opacity-20'>Via <b>{% course %}<b> Channel</div>
            <div ng-show="!course">
                <select name="course" ng-model="course" class="input">
                    <option value="">Select Course</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->code }}">{{ $course->code }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <input class="input" accept=".doc, .docx, .pdf, .txt" type="file" name="material"
                    placeholder="Select Material" />
            </div>


            <div class="">
                <button type="submit" class="btn btn-primary">Upload Material</button>
            </div>
        </div>

    </form>
</x-popend>
