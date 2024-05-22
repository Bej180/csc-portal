@php
    use App\Models\{Staff, Student, Result, AcademicSession};

    $activeAccount = auth()->user();
    $staffsCount = Staff::count();
    $studentsCount = Student::count();
    $pendingResults = Result::where('status', 'PENDING')->groupBy('reference_id')->get();
    $pendingResultsCount = $pendingResults->count();
    $currentSession = AcademicSession::latest()->first();
    $advisors = Staff::where('is_class_advisor', true);
    $listOfClassAdvisors = $advisors->get();
    $advisorsCount = $advisors->count();



@endphp
<x-template title="HOD Dashboard">
    <main class="w-dvw lg:w-full h-full grid content-start gap-5 md:grid-cols-2 lg:grid-cols-5 p-5 overflow-y-scroll">
        <div class="card md:col-span-2 lg:col-span-5">
            <div class="card-header">
                <div class="card-title">Overview</div>
            </div>
            <div class="card-body">
                <div class="card-content">
                    <section class="grid gap-3 md:grid-cols-4">
                        <div
                            class="bg-[--blue-200] p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                            <header class="w-full flex items-center justify-between">
                                <p class="font-medium uppercase">staff</p><span
                                    class="material-symbols-rounded">work</span>
                            </header>
                            <div>
                                <h1 class="font-medium text-4xl">{{ $staffsCount }}</h1>
                                <h1 class="font-medium text-4xl"></h1>
                            </div>
                        </div>
                        <div
                            class="bg-[--red-200] p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                            <header class="w-full flex items-center justify-between">
                                <p class="font-medium uppercase">students</p><span
                                    class="material-symbols-rounded">group</span>
                            </header>
                            <div>
                                <h1 class="font-medium text-4xl">{{ $studentsCount }}</h1>
                                <h1 class="font-medium text-4xl"></h1>
                            </div>
                        </div>
                        <div
                            class="bg-[--primary-200] p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                            <header class="w-full flex items-center justify-between">
                                <p class="font-medium uppercase">pending results</p><span
                                    class="material-symbols-rounded">school</span>
                            </header>
                            <div>
                                <h1 class="font-medium text-4xl">{{ $pendingResultsCount }}</h1>
                                <h1 class="font-medium text-4xl"></h1>
                            </div>
                        </div>
                        <div
                            class="bg-[--yellow-200] p-4 flex flex-col justify-between overflow-hidden rounded-md min-h-32">
                            <header class="w-full flex items-center justify-between">
                                <p class="font-medium uppercase">general info</p><span
                                    class="material-symbols-rounded">info</span>
                            </header>
                            <div>
                                <h1 class="font-medium text-4xl"></h1>
                                <h1 class="font-medium text-4xl"></h1>
                            </div>
                            @if ($currentSession)
                                <div>
                                    <p class="text-sm">Session: <span class="text-lg font-medium" ng-bind="config.active_session.name"></span></p>
                                    <p class="text-sm">Semester: <span class="text-lg font-medium" ng-bind="config.active_session.active_semester"></span></p>
                                </div>
                            @else
                                <p>Not yet added
                            @endif
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <div class="card md:col-span-1 lg:col-span-2">
            <div class="card-body">
                <div class="card-caption">
                    <div class="card-title">Profile</div>
                </div>
                <div class="card-content">
                    <div class="flex flex-col gap-2 items-center justify-center md:justify-start md:items-start">

                        <x-profile-pic :user="auth()->user()" alt="user-img"
                            class="aspect-square w-24 md:w-20 lg:w-28 rounded-full object-cover" />
                        <div class="text-center md:text-left grid gap-2">
                            <p class="font-semibold text-2xl">{{ auth()->user()->name }}</p>
                            <p class="text-lg">{{ auth()->user()->staff->staff_id }}</p>
                            <p>DEPARTMENT OF COMPUTER SCIENCE</p>
                            <p class="uppercase font-medium text-[--red-500]">H.O.D</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card md:col-span-1 lg:col-span-3">
            <div class="card-body">
                <div class="card-caption">
                    <div class="card-title">Advisors <span class="font-semibold text-[--primary-color]">{{ $advisorsCount }}</span></div>
                </div>
                <div class="card-content">
                    <section class="list grid content-start gap-3 overflow-y-scroll">

                      @forelse ($listOfClassAdvisors as $advisor)
                          <div class="border rounded-md min-h-20 px-3 py-2">
                            <header class="flex items-center gap-2">
                              <img
                                    src="/profilepic/{{$advisor->id}}" alt="advisor"
                                    class="aspect-square w-8 rounded-full object-cover">
                                <p class="font-semibold text-lg">{{ $advisor->user->name }}</p>
                            </header>
                            <p class="text-sm mt-1">Class: <span
                                    class="font-semibold text-[--primary-color]">{{ $advisor->class->name }}</span></p>
                        </div>
                      @empty
                      
                          <div class="uppercase text-zinc-400">
                              No Staff has been made Class Advisor of any class
                          </div>
                      @endforelse
                        
                    </section>
                </div>
            </div>
        </div>
    </main>
</x-template>
