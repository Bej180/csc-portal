<div class="popend-wrapper" ng-class="{show:is_popend('{{ $name }}')}">
    <div class="popend" tabindex="-1" aria-labelledby="popendLabel" aria-modal="true" role="dialog">
        <div class="popend-header">
            <h5 class="sentence-case font-semibold text-sm">@yield('header', '')</h5>
            <span ng-click="close_popend('{{ $name }}')" class="btn-text btn-close text-reset"></span>
        </div>
        <div class="popend-body">
            @yield('body')
        </div>
        <div class="popend-footer">
            @yield('footer', '')
        </div>
    </div>
    <div class="popend-backdrop" ng-click="close_popend('{{ $name }}')"></div>
</div>
