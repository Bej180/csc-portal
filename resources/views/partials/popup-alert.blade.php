@php
    
$holders = ['success', 'error', 'warning', 'info', 'message', 'danger'];
$icons = [
  'success' => 'task_alt',
  'error' => 'error',
  'warning' => 'warning',
  'info' => 'info',
  'message' => 'feedback',
  'danger' => 'dangerous',
];
$alert = 'null';
foreach($holders as $holder) {
  if (session()->has($holder)) {
    $message = session()->get($holder);
    $color = match($holder) {
        'danger','error', 'red' => 'red',
        'warning' => 'yellow',
        'success', 'green' => 'green',
        default => 'blue',
    };
    
    $alert = "{message:'$message', type: '$holder', color: '$color'}";
    break;
  }
}

@endphp


<script type="module">
                                 
@foreach($holders as $holder)
@if (Session::get($holder))
toastr.{{$holder}}('{{ Session::get($holder) }}');
@endif
@endforeach
</script>