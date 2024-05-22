@php 
    $advisor = \App\Models\Advisor::active();
    $set = $advisor->class;
    $students = $set->students()->paginate(10);
@endphp
<x-template nav="transcripts" title="Transcript" data="{reg_no:null, name: null}">
    <h1 class="text-lg text-body-300 font-semibold">Transcipts</h1>
    <span class="font-semibold text-sm text-primary">Click 
        <a target="_blank" href="./generated-transcript"
            class="text-secondary">
            here
        </a> 
            to see the generated transcript sample
        </span>

    <div class="w-full mt-4">
        <form action="" class="flex items-center gap-2 w-full relative">
            <label for="student-search" class="text-body-200 absolute top-3 left-1">
                <span class="material-symbols-rounded">search</span>
            </label>
            <input type="search" name="studentSearch" id="student-search" placeholder="Search student..."
                class="border border-[var(--primary)] outline-none h-8  pl-8 text-body-300 w-full">
            <div class="select">
                <select name="searchBy" id="searchBy">
                    <option value="">Search by</option>
                    <option value="name">Name</option>
                    <option value="regNum">Reg. Number</option>
                </select>
            </div>
            <button type="submit"
                class="btn-sm text-white bg-[var(--primary)] hover:bg-[var(--primary-700)] transition rounded h-8">
                Search
            </button>
        </form>   
    </div>

    <div class="w-full lg:flex lg:gap-2">
        <div class="scroller">
            <table class="responsive-table whitespace-nowrap cursor-context-menu">
                <thead>
                    <th></th>
                    <th>Name</th>
                    <th>Reg. No.</th>
                    <th class="hidden md:table-cell">Level</th>
                    <th class="hidden md:table-cell">CGPA</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        @php
                            $user = $student->user;
                            $cgpa = \App\Models\Enrollment::calculateCGPA($student);
                        

                        @endphp
                        <tr>
                            <td align="center">
                                <x-profile-pic :user="$user" alt="student_img" class="h-8 w-8 object-cover rounded-md" style="height:30px;width:30px;"/>
                            </td>
                            <td>{{$user->name}}</td>
                            <td>{{$student->reg_no}}</td>
                            <td class="hidden md:table-cell">{{$student->level}}</td>
                            <td class="hidden md:table-cell">{{$student->calculateCGPA()}}</td>
                            <td>
                                <button
                                data-name="{{$user->name}}"
                                data-regNo="{{$student->reg_no}}"
                                @click="generateTranscript"
                                @xclick="formOpen = true"
                                type="button"
                                    class="btn-primary">
                                    Generate
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        
        
    </div>
    
   
    <div id="transcriptgenerator" class="hidden">
        <div class="bg-black opacity-25 fixed top-0 bottom-0 left-0 right-0 z-[1000]  close-transcript-generator"></div>
        <div class="fixed top-[50%] left-[50%] -translate-x-[50%] -translate-y-[50%] bg-white dark:bg-gray-900 rounded shadow-lg border border-slate-300 dark:border-gray-700 p-5 w-80 z-[1000]
         ">
            <form action="{{route('generate.transcript')}}" method="POST" class="grid gap-5">
                @csrf
                <div class="flex flex-col gap-4">
                    <h1 class="font-semibold  text-xl">Generate Transcript</h1>
    
    
                    <div class="flex justify-between items-center">
                        <label for="name" class=" font-semibold text-sm">Name:</label>
                        <input type="text" id="transcriptHolder" value=""
                            class="input" readonly>
                    </div>
                    <div class="flex items-center justify-between">
                        <label for="regNum" class="font-semibold text-sm">Reg. No.:</label>
                        <input placeholder="Reg Numbr" type="text" id="transcriptregNum" value="" name="reg_no"
                            class="input" readonly>
                    </div>
    
                </div>
    
                <div x-data="{single: false, range: false}" class="flex flex-col gap-2">
                    <h1 class="font-semibold text-body-300">Options</h1>
    
                    <div class="flex items-center gap-1">
                        <input
                        @click="single=false, range=false"
                        type="radio" name="transcriptType" id="full" value="full" checked>
                        <label for="full" class="text-secondary-800 font-semibold text-sm">Full</label>
                        <span class="text-sm text-body-300">(From year 1 to current year)</span>
                    </div>
    
                    <div class="flex items-center gap-1">
                        <input
                        @click="single=true, range=false"
                        type="radio" name="transcriptType" id="single" value="single">
                        <label for="single" class="text-secondary-800 font-semibold text-sm">Single Year</label>
                        <div class="select flex-1">
                            <select name="year" id="year" class="w-full" x-bind:disabled="!single">
                                <option value="1">Year 1</option>
                                <option value="2">Year 2</option>
                                <option value="3">Year 3</option>
                                <option value="4">Year 4</option>
                                <option value="5">Year 5</option>
                            </select>
                        </div>
                    </div>
    
                    <div class="flex items-center gap-1 flex-wrap">
                        <input
                        @click="single=false, range=true"
                        type="radio" name="transcriptType" id="range" value="range">
                        <label for="range" class="text-secondary-800 font-semibold text-sm">Range</label>
                        
                        <div class="w-full">
                            <div class="select">
                                <label for="startYear">From</label>
                                <select name="year" id="startYear" x-bind:disabled="!range">
                                    <option value="1">Year 1</option>
                                    <option value="2">Year 2</option>
                                    <option value="3">Year 3</option>
                                    <option value="4">Year 4</option>
                                    <option value="5">Year 5</option>
                                </select>
                            </div>
                            <span class="font-semibold">-</span>
    
                            <div class="select">
                                <label for="endYear">To</label>
                                <select name="year" id="endYear" x-bind:disabled="!range">
                                    <option value="1">Year 1</option>
                                    <option value="2">Year 2</option>
                                    <option value="3">Year 3</option>
                                    <option value="4">Year 4</option>
                                    <option value="5">Year 5</option>
                                </select>
                            </div>
                        </div>
    
                    </div>
                </div>
    
                <div class="flex gap-2 items-center">
                    <button type="button" class="btn-primary">Generate</button>
    
                    <button @click="formOpen=false" type="button" class="btn-cancel">Cancel</button>
                </div>
                
            </form>
        </div>
    </div>
</x-template>