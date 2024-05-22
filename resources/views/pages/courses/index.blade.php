<x-template title="Courses" class="half">
   <div class="full">
    <div class="flex gap-4 pt-8">
        <div class="flex-1">
            <div class="card">
                <div class="card-body">
                    <table class="w-full table">
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

                            @foreach ($courses as $course)
                                <tr data-course-url="/course/{{ $course->id }}">
                                    <td>{{ $course->name }}</td>
                                    <td>{{ $course->code }}</td>
                                    <td>{{ $course->semester }}</td>
                                    <td>{{ $course->level }} Level</td>
                                    <td>{{ $course->unit }}</td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{$courses->links()}}
                </div>
            </div>
        </div>

        <div>
            <form class="form-300" action="{{ route('store.course') }}" method="POST" autocomplete="off">
                <div class="card">
                    @csrf
                    <div class="card-header">
                        Add Course
                    </div>
                    <div class="text-slate-300 card-px">All the fields are required</div>
                    <div class="card-body">




                        @error('name')
                            <div class="text-danger small">
                                <i class="fas fa-exclamation-triangle text-danger"></i> {{ $message }}
                            </div>
                        @enderror
                        <div class="mb-3">
                            <input type="text" class="input" name="name" value="{{ old('name') }}"
                                placeholder="Course Title" />
                        </div>

                        @error('code')
                            <div class="text-danger small">
                                <i class="fas fa-exclamation-triangle text-danger"></i> {{ $message }}
                            </div>
                        @enderror
                        <div class="mb-5">
                            
                            <input type="text" class="input" name="code" value="{{ old('code') }}"
                                placeholder="eg: CIT 401" />
                        </div>

                        @error('semester')
                            <div class="text-danger small">
                                <i class="fas fa-exclamation-triangle text-danger"></i> {{ $message }}
                            </div>
                        @enderror
                        <div class="mb-3">
                            <label for="semester" class="block">
                                <i class="small">Semester</i>
                            </label>  
                            <select class="input" name="semester">
                                <option>---</option>
                                <option value="HARMATTAN" {{ old('semester') === 'HARMATTAN' ? ' selected' : '' }}>Harmattan
                                </option>
                                <option value="RAIN" {{ old('semester') === 'RAIN' ? ' selected' : '' }}>Rain</option>
                            </select>
                        </div>

                        @error('unit')
                            <div class="text-danger small">
                                <i class="fas fa-exclamation-triangle text-danger"></i> {{ $message }}
                            </div>
                        @enderror
                        <div class="mb-3">
                            <label for="unit" class="block">
                                <i class="small">Unit</i>
                            </label>
                            
                            <select class="input" name="unit">
                                <option>---</option>
                                @for ($i = 1; $i < 7; $i++)
                                    <option value="{{ $i }}" {{  old('unit') == $i ? ' selected' : '' }}>
                                        {{ $i }} unit{{ $i > 1 ? 's' : '' }}
                                    </option>
                                    </option>
                                @endfor
                            </select>
                        </div>


                        @error('level')
                            <div class="text-danger small">
                                <i class="fas fa-exclamation-triangle text-danger"></i> {{ $message }}
                            </div>
                        @enderror
                        <div class="mb-3">
                            
                            <label for="level" class="block">
                                <i class="small">Level</i>
                            </label>
                            @php
                                
                                $levels = [100, 200, 300, 400, 500];
                                $selection = old('level');
                                
                            @endphp
                            <select class="input" name="level">
                                <option>---</option>

                                @foreach ($levels as $level)
                                    <option value="{{ $level }}" {{ $selection == $level ? ' selected' : '' }}>
                                        {{ $level }} Level</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                           
                            <label for="mandatory" class="block">
                                <i class="small">Option</i>
                            </label>
                            
                            <select class="form-control" name="mandatory">
                                <option value="1" {{ old('mandatory') && old('mandatory') == 1 ? ' selected' : '' }}>Mandatory
                                </option>
                                <option value="0" {{ old('mandatory') && old('mandatory') == 0 ? ' selected' : '' }}>Elective</option>
                            </select>
                        </div>



                        <div class="mb-3">
                            <label for="check">
                                <input type="checkbox" name="check" required="required">
                                I accept that all the fields are field correctly
                                @error('check')
                                    <div class="text-danger small">
                                        <i class="fas fa-exclamation-triangle text-danger"></i> {{ $message }}
                                    </div>
                                @enderror
                            </label>
                        </div>
                        @error('check')
                            <div class="text-danger small">
                                <i class="fas fa-exclamation-triangle text-danger"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="card-footer">
                    
                        <button type="submit" class="btn-primary">Submit Course</button>
                        
                    </div>
                </div>
            </form>
        </div>
    </div>
   </div>
    
</x-template>
