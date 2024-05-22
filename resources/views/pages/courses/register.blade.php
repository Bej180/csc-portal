<x-body>

    <form action="{{ route('proceed_register.courses') }}" class="form-300" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                Register Courses
            </div>
            <div class="card-body">
                Choose Level
                <div class="input-group">

                    <select name="level" class="form-control">
                        <option>---</option>
                        @foreach ($department->levels() as $level)
                            <option value="{{ $level }}">{{ $level }} Level</option>
                        @endforeach
                    </select>
                </div>
                @error('level')
                    <div class="text-danger small">
                        <i class="fas fa-exclamation-triangle text-danger"></i> {{ $message }}
                    </div>
                @enderror


                Choose Semester
                <div class="input-group">

                    <select name="semester" class="form-control">
                        <option>---</option>
                        <option value='HARMATTAN'>Harmattan</option>
                        <option value='RAIN'>Rain</option>
                    </select>
                </div>
                @error('semester')
                    <div class="text-danger small">
                        <i class="fas fa-exclamation-triangle text-danger"></i> {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="card-footer">
                <input type="submit" value="Proceed" />
            </div>


    </form>


</x-body>
