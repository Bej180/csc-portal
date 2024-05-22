@php
$authUser = auth()->user();
$userActivities = $authUser->activityLogs()->orderBy('created_at', 'desc')->paginate(7);
$image = $authUser->picture();
@endphp


<x-template style="activities">

    <x-wrapper active="My Activities">

        <ul class="activity">
          @forelse ($userActivities as $n => $log)
            @php
                $index = $n + 1;
                $class = $index % 2 === 0 ? 'activity-inverted': '';
                
            @endphp
            <li class="{{$class}}">
                <div class="activity-badge success">
                    <img src="{{$image}}"/>
                </div>
                <div class="activity-panel">
                    <div class="activity-body">
                        <p><b class="link !font-semibold">You</b> {{ preg_replace('/(^|\s):(his|him|her|he|she)(\s|$)/', ' your ', $log->description) }}</p>
                        <p class="opacity-50 text-sm">{{ timeago($log->created_at) }}</p>
                        <div class="mt-3 border-t border-t-zinc-200 pt-3 opacity-25 text-xs">
                          <p><b>IP Address:</b> {{ $log->ip_address }}</p>
                          <p><b>User Agent:</b> {{ $log->user_agent }}</p>
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
            {{-- <li class="activity-inverted">
                <div class="activity-badge warning">
                    <i class="fas fa-users"></i>
                </div>
                <div class="activity-panel">
                    <div class="activity-heading">
                        <h4 class="activity-title">Title </h4>
                    </div>
                    <div class="activity-body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium maiores
                            odit qui est tempora eos, nostrum provident explicabo dignissimos debitis
                            vel! Adipisci eius voluptates, ad aut recusandae minus eaque facere.</p>
                            <p>{{ timeago($log->created_at) }}</p>
                    </div>
                </div>
            </li>
            <li>
                <div class="activity-badge danger">
                    <i class="fas fa-gift"></i>
                </div>
                <div class="activity-panel">
                    <div class="activity-heading">
                        <h4 class="activity-title">Lorem ipsum dolor</h4>
                    </div>
                    <div class="activity-body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus numquam
                            facilis enim eaque, tenetur nam id qui vel velit similique nihil iure
                            molestias aliquam, voluptatem totam quaerat, magni commodi quisquam.</p>
                    </div>
                </div>
            </li>
            <li class="activity-inverted">
                <div class="activity-panel">
                    <div class="activity-heading">
                        <h4 class="activity-title">Lorem ipsum dolor</h4>
                    </div>
                    <div class="activity-body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates est
                            quaerat asperiores sapiente, eligendi, nihil. Itaque quos, alias sapiente
                            rerum quas odit! Aperiam officiis quidem delectus libero, omnis ut debitis!</p>
                    </div>
                </div>
            </li>
            <li>
                <div class="activity-badge info">
                    <i class="fa fa-save"></i>
                </div>
                <div class="activity-panel">
                    <div class="activity-heading">
                        <h4 class="activity-title">Lorem ipsum dolor</h4>
                    </div>
                    <div class="activity-body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis minus modi
                            quam ipsum alias at est molestiae excepturi delectus nesciunt, quibusdam</p>
                    </div>
                </div>
            </li>
            <li class="activity-inverted">
                <div class="activity-badge success">
                    <i class="fa fa-graduation-cap"></i>
                </div>
                <div class="activity-panel">
                    <div class="activity-heading">
                        <h4 class="activity-title">Lorem ipsum dolor</h4>
                    </div>
                    <div class="activity-body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt obcaecati,
                            quaerat tempore officia voluptas debitis consectetur culpa amet, accusamus
                            dolorum fugiat, animi dicta aperiam, enim incidunt quisquam maxime neque
                            eaque.
                        </p>
                    </div>
                </div>
            </li> --}}
        </ul>
    </x-wrapper>

</x-template>
