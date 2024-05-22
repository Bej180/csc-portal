<?php



  $role = 'guest';
  if(!isset($module)) {
    $module = 'page';
  }
  if (!isset($nav)) {
    $nav = 'all';
  }
  
  if (auth()->check()) {
    $role = auth()->user()->role;
  } 

  $scripts = [
    "js/modules/$role.js",
    "js/modules/$module.js",
    "js/modules/$role-$module.js",
    "js/modules/$nav.js",
    "js/modules/$nav-$module.js",
    'js/upload.js',
    'js/jselect.js',
    "js/modules/$nav-$module.js",
    
    "js/modules/$nav-$module.js"
  ];
  $scripts = array_unique($scripts);

?>

  
  <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
  <script src="{{asset('js/jquery-plugins.js')}}"></script>


  <!-- <script defer type="module" src="{{asset('scripts/init-alpine.js')}}"></script> -->
  <script type="module" src="{{asset('scripts/main.js')}}"></script>
  
  <script src="{{asset('js/angular/angular.min.js')}}"></script>
  <script type="module" src="{{ asset('js/ng-controllers.js') }}"></script>
  

  @auth

      <script type="module" src="{{asset('scripts/'.auth()->user()->role.'.js')}}"></script>
      
  @endauth

  @foreach($scripts as $script) 
    @if(file_exists(public_path($script)))
      <script type="module" src="{{ asset($script) }}"></script>
    @endif
  @endforeach
  <script>
    $(function(){
  offOverlay(6000);
});
</script>