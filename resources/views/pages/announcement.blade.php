@php 
  $authUser = auth()->user();
  $announcements = \App\Models\Announcement::where('user_id', '=', $authUser->id)->orWhere('target', '=', $authUser->role)->with('announcer')->paginate(10);
 
@endphp
<x-user-layout nav="announcement" title="Announcement">
  <x-page-header>Announcement</x-page-header>
  <div class="pt-5">
    @if($authUser->role !== 'student')
      <fieldset class="rounded-md border border-gray-700/20 dark:border-gray-500">

          <form action="/announcement/add" method="POST" class="flex flex-col">
            @csrf
              @error('message')
                <x-alert type="error" class="mx-4">{{$message}}</x-alert>
              @enderror
              <textarea name="message" class="bg-transparent @error('message') invalid @enderror  focus:outline-none p-4" placeholder="Enter your announcements here" rows="6">{{old('message')}}</textarea>

              @error('target')
                <x-alert type="error" class="mx-4">{{$message}}</x-alert>
              @enderror
              <div class="flex items-center justify-between mx-4">
                  
                  <select name="target" class="input input-sm @error('target') invalid @enderror">
                      <option value="">Audience</option>
                      <option value="student">Students</option>
                      <option value="advisor">Advisors</option>
                      <option value="admin">Admins</option>
                      
                  </select>
                  <button class="btn-primary input-sm">Announce</button>
              </div>
          </form>

      </fieldset>
    @endif

    <div class="">
      @foreach($announcements as $announcement) 
        <div class="p-5">

          <div class="flex items-center justify-between gap-3">
              <div class="font-semibold link">
                {{$announcement->announcer->name}}
              </div>
              <div>
                {!! $announcement->created_at !!}
              </div>
          </div>

          <div class="flex-1">
            {{$announcement->message}}
          </div>

        </div>
      @endforeach
    </div>

  </div>

</x-user-layout>