<!DOCTYPE html>
<html lang="en">
<head>
  @include('layouts.head',  ['title' => 'Registered']);
</head>
<body>

  <div class="h-dvh h-dwh grid place-items-center">

    <div class="flex items-center flex-col text-center">
      <i class="fa fa-check-circle fa-5x text-green-600 sonar_once"></i>
      <div class="text-2xl mt-4 text-green-800 font-semibold">
        Registered Successfully
      </div>
      <div class="text-slate-500 mt-2">
        <p>
          Your account has been created successfully.
        </p>
        <p>
          An acctivation email was sent to your email address
        </p>
        <p>
          <a href="{{route('home')}}" class="btn btn-primary mt-4 "><i class="fas fa-home"></i> Goto Home</a>
        </p>
      </div>

    </div>

  </div>
  
</body>
@include('layouts.footer');
</html>