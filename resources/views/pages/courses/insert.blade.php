<x-template title="Add Course" nav="course">
    <div class="grid place-items-center">
        <form action="{{ route('store.course') }}" method="POST" autocomplete="off">
            <div class="card">
                @csrf
                <div class="card-header">
                    Add Course
                </div>
                <div class="card-body">
    
                    <div class="form-group mb-4">
                        <label class="form-check-label small">
                            <input type="checkbox" name="addmorecourse" class="mr-1" checked> Add more courses
                        </label>
                    </div>
    
                    @error('name')
                        <div class="text-danger small">
                            <i class="fas fa-exclamation-triangle text-danger"></i> {{ $message }}
                        </div>
                    @enderror
                    <div class="input-group mb-3">
                        
                        <input type="text" class="input w-full" name="name" value="{{ old('name') }}"
                            placeholder="Course Title" />
                    </div>
    
                    @error('code')
                        <div class="text-danger small">
                            <i class="fas fa-exclamation-triangle text-danger"></i> {{ $message }}
                        </div>
                    @enderror
                    <div class="flex mb-3">
                        <div class="flex-1">
                            
                            <input type="text" class="input" name="code" value="{{ old('code') }}"
                                placeholder="Code (eg: CIT 401)" />
                        </div>
                        <div>
                           
                           @php
                               $selection = old('mandatory');
                               
                           @endphp
                           <select class="input" name="mandatory">
                               <option value="1" {{ $selection && $selection == 1 ? ' selected' : '' }}>Mandatory
                               </option>
                               <option value="0" {{ $selection && $selection == 0 ? ' selected' : '' }}>Elective</option>
                           </select>
                        </div>
                    </div>
    
                   
                    <div class="flex gap-2">
                        <div>
                            <select class="form-control input @error('semester') invalid @enderror" name="semester">
                                <option value="">Semester</option>
                                <option value="HARMATTAN" {{ old('semester') === 'HARMATTAN' ? ' selected' : '' }}>Harmattan
                                </option>
                                <option value="RAIN" {{ old('semester') === 'RAIN' ? ' selected' : '' }}>Rain</option>
    
                            </select>
                        </div>
                        <div>
                            @php
                                $selection = old('unit');
                            @endphp
                            <select class="input @error('unit') invalid @enderror" name="unit">
                                <option value="" class="input @error('semester') invalid @enderror">Unit</option>
                                @for ($i = 1; $i < 7; $i++)
                                    <option value="{{ $i }}" {{ $selection == $i ? ' selected' : '' }}>
                                        {{ $i }} unit{{ $i > 1 ? 's' : '' }}
                                    </option>
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            @php
                                
                                $levels = [100, 200, 300, 400, 500];
                                $selection = old('level');
                                
                            @endphp
                            <select class="input @error('level') invalid @enderror" name="level">
                                <option value="">Level</option>
    
                                @foreach ($levels as $level)
                                    <option value="{{ $level }}" {{ $selection == $level ? ' selected' : '' }}>
                                        {{ $level }} Level</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
    
                   
                    
    
                   
    
    
    
                    <div class="mt-4">
                        <label class="form-check-label">
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
                    <div class="input-group">
    
                        <button type="submit" class="form-control btn btn-primary">Submit Course</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

</x-template>
