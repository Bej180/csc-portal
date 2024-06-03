@php
    
$holders = ['success', 'error', 'warning', 'info', 'message', 'danger'];

$alerts =  ['alert', 'alert_success', 'alert_danger', 'alert_info', 'alert_warning'];



@endphp


<script type="module">
                                 
@foreach($holders as $holder)
@if (Session::get($holder))
toastr.{{$holder}}('{{ Session::get($holder) }}');
@endif
@endforeach
</script>
<script>
  @foreach($alerts as $alert) 

    @if (Session::get($alert)) 
      $.confirm('{{ Session::get($alert) }}', {
        type: 'alert'
      });
    @endif
  @endforeach

  </script>