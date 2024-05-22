@php
    $appendUrl = '';
    $callbackUrl = request()->get('callbackUrl');
    if ($callbackUrl) {
        $appendUrl = 'callbackUrl=' . urlencode($callbackUrl);
    }
@endphp
<!DOCTYPE html>
<html lang="en" ng-class="darkMode" ng-app="cscPortal" ng-controller="RootController">

<head>
    @include('layouts.head')
</head>

<body class="grid min-h-[100dvh] place-items-center bg-zinc-50">

    @include('partials.popup-alert')
    <div class="h-[250px] w-[400px] popup" ng-controller="AuthController">
        <div class="popup-wrapper">
            <div class="popup-body text-2xl text-center font-bold text-primary">Lock Screen</div>
            <div class="popup-body">
                <div class="font-[600] text-center">{{ $profile->name }}</div>

                <div class="rounded  mt-[10px] mx-auto mb-[30px] p-0 relative w-[290px]">

                    <div class="rounded-full bg-green-100 -left-[10px] p-[5px] absolute -top-[19px] z-10">
                        <img src="{{ $profile->picture() }}" class="rounded-full h-[70px] w-[70px] object-cover"
                            alt="User Image">
                    </div>
                    <div id="alert"></div>
                  


                    <form action="?" method="POST" class="ml-[70px]">
                        @csrf
                        @if (request()->has('callbackUrl'))
                            <input type="hidden" name="callbackUrl" value="{{ request()->get('callbackUrl') }}" />
                        @endif
                        <div class="flex justify-between relative -left-[20px] bg-green-100 overflow-clipx">
                            <input type="text" class="opacity-0 w-0 h-0" autocomplete=""
                                value="{{ $profile->email }}" name="credential" />
                            <div class="flex-1 flex bg-green-100 items-center rounded-e-lg">
                                <div class="flex-1">
                                    <input autofocus="true" type="password" name="password" ng-model="password"
                                        autocomplete="new-password"
                                        class="focus:!outline-none !bg-transparent !border-none"
                                        placeholder="Password" />
                                </div>
                                <x-tooltip label="Send" disabled="disabled" ng-disabled="!password">
                                    <button type="button" class="px-2 disabled:opacity-40" ng-click="login($event)"
                                        ng-disabled="!password"
                                        style="height: unset;
                                    position: relative;
                                    bottom: -2px;"
                                        disabled="true">
                                        <span class="material-symbols-rounded">
                                            arrow_forward
                                        </span>
                                    </button>
                                </x-tooltip>
                            </div>
                        </div>
                    </form>

                </div>

                <div class="mb-4 text-center">
                    Enter your password to retrieve your session
                </div>
                <div class="text-center">
                    <a href="/login?change=user&{{ $appendUrl }}">Or sign in as a different user</a>
                </div>
            </div>
        </div>
    </div>
</body>
@include('layouts.footer')

</html>
