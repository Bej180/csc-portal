<aside id="side-slot" class="sidebar-menu">
  <div class="sidebar-search">
        <label for="search" 
            class="material-symbols-rounded">
            search
        </label>
        <div class="sidebar-search-input-area">
          <input type="search" class="-sidebar-search-input" placeholder="Search..."
              class="bg-transparent focus:outline-none">
        </div>
    </div>
  <a href="/home" class="{{$nav == 'home' ? 'active':''}}">
      <span class="material-symbols-rounded">home</span>
      <label>Home</label>
    </a>
  
  @if($role === 'student')
    <a class="{{$nav=='courses'?'active':''}}" href="/courses">
      <span class="material-symbols-rounded">book_2</span>
      <label>Courses</label>
    </a>

    <a class="{{$nav=='results'?'active':''}}" href="/results">
      <span class="material-symbols-rounded">school</span>
      <label>Results</label>
    </a>

  
  
  @elseif($role === 'advisor')
    <a class="{{$nav == 'announcement'?'active':''}}" href="/announcement">
      <span class="material-symbols-rounded">announcement</span>
      <label>Announcement</label>
    </a>

    <a class="{{$nav == 'class' ? 'active':''}}" href="/class">
      <span class="material-symbols-rounded">class</span>
      <label>Class</label>
    </a>
    
    <a class="{{$nav == 'results' ? 'active':''}}" href="/moderator/results">
      <span class="material-symbols-rounded">poll</span>
      <label>Results</label>
    </a>

    <a class="{{$nav == 'transcripts' ? 'active':''}}" href="/advisor/transcript">
      <span class="material-symbols-rounded">description</span>
      <label>Transcripts</label>
    </a>

  @elseif ($role === 'admin')
    <a href="/admin/advisors" class="{{$nav=='advisors'?'active':''}}">
      <span class="material-symbols-rounded">transfer_within_a_station</span>
      <label>Advisors</label>
    </a>

    <a href="/admin/staffs" class="{{$nav=='staffs'?'active':''}}">
      <span class="material-symbols-rounded">transfer_within_a_station</span>
      <label>Staffs</label>
    </a>

    <a href="/admin/courses" class="{{$nav=='courses'?'active':''}}">
      <span class="material-symbols-rounded">book_2</span>
      <label>Courses</label>
    </a>

    <a class="{{$nav == 'classes' ? 'active':''}}" href="/admin/classes">
      <span class="material-symbols-rounded">class</span>
      <label>Classes</label>
    </a>

    <a href="/admin/students" class="{{$nav=='students'?'active':''}}">
      <span class="material-symbols-rounded">school</span>
      <label>Students</label>
    </a>

    

    <a href="/moderator/results" class="{{$nav=='results'?'active':''}}">
      <span class="material-symbols-rounded">stacks</span>
      <label>Results</label>
    </a>


  @elseif ($role === 'staff')   
    <a href="/staff/students" class="{{$nav=='students'?'active':''}}">
      <span class="material-symbols-rounded">school</span>
      <label>Students</label>
    </a>  

    <a href="/admin/students" class="{{$nav=='students'?'active':''}}">
      <span class="material-symbols-rounded">school</span>
      <label>My Course</label>
    </a>

    <a class="{{$nav == 'results' ? 'active':''}}" href="/moderator/results">
      <span class="material-symbols-rounded">poll</span>
      <label>Results</label>
    </a>



  @else 
    <a href="./courses.html">
      <span class="material-symbols-rounded">book_2</span>Login</a>

    <a href="./results.html">
      <span class="material-symbols-rounded">school</span>Lost Password</a>

    <a href="./profile.html">
      <span class="material-symbols-rounded">account_circle</span>Profile</a>
    
  @endif


  @auth

    <a href="/admin/profile"class="{{$nav=='profile'?'active':''}}">
      <span class="material-symbols-rounded">account_circle</span>
      <label>Profile</label>
    </a>

    <a href="/admin/settings" class="{{$nav=='settings'?'active':''}}">
      <span class="material-symbols-rounded">settings</span>
      <label>Settings</label>
    </a>
    <a href="/logout">
      <span class="material-symbols-rounded">logout</span>
      <label>Logout</label>
    </a>
  @endauth
</aside>