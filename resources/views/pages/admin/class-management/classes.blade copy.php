@php
    $classes = \App\Models\AcademicSet::with(['students', 'advisor'])->get();
@endphp
<x-template nav="classes" title="Admin - Classes">
    <x-wrapper active="Classes">
        <div ng-controller="ClassController" ng-init="init()">
            
            <div class="flex justify-end">
                <button type="button" ng-click="popend('addClass')" class="btn btn-white">Create New Class</button>
            </div>

            <div class="cb responsive-table no-zebra lg:p-5">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="!text-center">Top Students</th>
                            <th>Advisor</th>
                            <th class="!text-center">Total Students</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classes as $class)
                            <tr ng-click="viewClass('{{$class->name}}')" class="cursor-pointer">
                                <td class="font-[500]">
                                    {{ $class->name }}
                                </td>
                                <td>
                                    <div class="py-0.5 justify-center flex -space-x-2 overflow-hidden">
                                        @foreach ($class->students as $n => $student)
                                            <img class="hover:z-10 inline-block h-6 w-6 object-cover rounded-full ring-2 ring-white"
                                                src="{{ $student->user->picture() }}"
                                                alt="{{ $student->user->name }}" />
                                            @php
                                                if ($n == 2) {
                                                    break;
                                                }
                                            @endphp
                                        @endforeach
                                    </div>

                                </td>
                                <td>{{ $class->advisor?->user?->name }}</td>
                                <td class="!text-center">
                                    {{ $class->students()->count() === 0 ? 'NA' : $class->students()->count() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @include('pages.admin.class-management.add')
            @include('pages.admin.class-management.view-class')

        </div>
    </x-wrapper>

</x-template>
