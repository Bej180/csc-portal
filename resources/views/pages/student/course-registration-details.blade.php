<x-template title='Course Registration Details' nav="courses">
    <div class="scrollerx">
        <div class="hide-on-print px-4 sticky top-10">
            <x-page-header>
                <span>
                    Course Registration Details
                </span>

                <button ng-click="print()" type="button" class="btn-primary transition text-sm">
                    Print
                </button>

            </x-page-header>
        </div>

        <div class="grid place-items-center ">
            <div id="registered-courses-details-container"
                class="mt-2 border slate-400 rounded p-7 pb-10 md:flex md:flex-col md:gap-2 visible-on-print w-[800px] bg-white">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/futo-log.png') }}" alt="futo-logo" class="w-20"/>
                    <h1 class="text-sm font-semibold text-body-400 md:text-base xl:text-lg print:text-black">
                        FEDERAL UNIVERSITY OF TECHNOLOGY, OWERRI
                    </h1>
                    <p class="text-xs text-body-400 font-semibold md:text-sm xl:text-base print:text-black">DEPARTMENT
                        OF
                        COMPUTER SCIENCE (SICT)</p>
                </div>
               
                <div class="flex gap-3 items-center mt-8 w-fit mx-auto" id="student-info">
                    <div>
                        <x-profile-pic :user="$student" alt="user" class="rounded-full w-16 lg:w-20 xl:w-24 aspect-square" />
                    </div>

                    
                    <div
                        class="flex-1 text-[.78rem] gap-4 text-body-800 items-center whitespace-nowrap md:text-sm print:text-black">

                        <div class="w-full">
                        <div class="grid grid-cols-5 gap-1">
                            <div class="col-span-1">Full Name:</div>
                            <div class="col-span-2 uppercase font-semibold">{{ $user->name }}</div>
                            <div class="col-span-1">Registration Number:</div>
                            <div class="col-span-1 uppercase font-semibold">{{ $student->reg_no }}</div>
                        </div>

                        <div class="grid grid-cols-5 gap-1">
                            <div class="col-span-1">School:</div>
                            <div class="col-span-2 uppercase font-semibold">SICT</div>
                            <div class="col-span-1">Department:</div>
                            <div class="col-span-1 uppercase font-semibold">Computer Science</div>
                        </div>

                        <div class="grid grid-cols-5 gap-1">
                            <div class="col-span-1">Entry Mode:</div>
                            <div class="col-span-2 uppercase font-semibold">UTME</div>
                            <div class="col-span-1">Level:</div>
                            <div class="col-span-1 uppercase font-semibold">{{ $level }}</div>
                        </div>

                        <div class="grid grid-cols-5 gap-1">
                            <div class="col-span-1">Session:</div>
                            <div class="col-span-2 uppercase font-semibold">{{ $session }}</div>
                            <div class="col-span-1">Semester:</div>
                            <div class="col-span-1 uppercase font-semibold">{{ $semester }}</div>
                        </div><!--end-->
                    </div>



                    </div>
                </div>

                <div class="mt-4 responsive-table">
                    <table class="mx-auto print:text-black !w-[400px] min-w-[90%]">
                        <thead class="print:text-black">
                            <th class="!w-24">Code</th>
                            <th class="!w-36">Title</th>
                            <th class="w-10">Units</th>
                            <th class="!w-32">Type</th>
                        </thead>
                        <tbody>
                            @php $totalUnits = 0; @endphp
                            @foreach ($enrollments as $enrollment)
                                @php $totalUnits += $enrollment->course->units; @endphp
                                <tr>
                                    <td class="!w-[80px]">{{ $enrollment->course->code }}</td>
                                    <td class="!text-left">{{ $enrollment->course->name }}</td>
                                    <td>{{ $enrollment->course->units }}</td>
                                    <td class="uppercase">{{ $enrollment->course->option }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <td></td>
                                <td class="uppercase">Total</td>
                                <td>{{ $totalUnits }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    class="mt-20 grid grid-cols-2 gap-x-4 gap-y-16 text-[.8rem]
                md:w-[80%] md:self-center md:gap-x-12 md:text-sm
                lg:w-[60%]">
                    <div
                        class="text-body-400 print:text-black font-semibold p-1 border-t-2 border-t-[var(--body-400)] border-dashed text-center">
                        Student's Signature
                    </div>
                    <div
                        class="text-body-400 print:text-black font-semibold p-1 border-t-2  border-t-[var(--body-400)]  border-dashed text-center">
                        Date
                    </div>
                    <div
                        class="text-body-400 print:text-black font-semibold p-1 border-t-2  border-t-[var(--body-400)]  border-dashed text-center">
                        Advisor's Signature
                    </div>
                    <div
                        class="text-body-400  print:text-black  font-semibold p-1 border-t-2  border-t-[var(--body-400)]  border-dashed text-center">
                        HOD's Signature
                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        @media print {
            #registered-courses-details-container {
                border: none;
                padding: 0px;
            }

            #user-info {
                flex-direction: row;
            }

            body {
                background: #fff;
            }

            td,
            th {
                border: none !important;
            }
        }
    </style>
</x-template>
