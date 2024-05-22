@php 

    $holders = ['success', 'error', 'warning', 'info', 'message', 'danger'];
    $message = null;
    $type = 'info';

    foreach($holders as $holder) {
        if (session()->get($holder)) {
            $type = $holder;
            $message = session()->get($holder);
            break;
        }
    }
    
    $class = match($type) {
        'error' => 'bg-red-200 text-red-600',
        'warning' => 'bg-yellow-200 text-yellow-600',
        'info' => 'bg-blue-200 text-blue-600',
        'success' => 'bg-green-200 text-green-600',
        default => 'bg-red-200 text-red-600',
    };
@endphp

@php 
    use Illuminate\Support\Facades\Session;

    $holders = ['success', 'error', 'warning', 'info', 'message'];
    $message = null;
    $type = 'info';

    foreach($holders as $holder) {
        if (Session::get($holder)) {
            
            $type = $holder;
            $message = Session::get($holder);
            Session::forget($holder);
            break;
        }
    }
    
    $class = match($type) {
        'error' => 'bg-red-200 text-red-600',
        'warning' => 'bg-yellow-200 text-yellow-600',
        'info' => 'bg-blue-200 text-blue-600',
        'success' => 'bg-green-200 text-green-600',
        default => 'bg-blue-200 text-blue-600',
    };
@endphp

@if ($message)

    <div ng-controller="AlertController">
        <div ng-show="showAlert" ng-class="{ 'fade-in': showAlert, 'fade-out': !showAlert }" class="{{ $class }} flash">
            {{ $message }}
        </div>
    </div>
@endif

