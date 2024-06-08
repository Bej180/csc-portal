@props(['name', 'title', 'colorful', 'class'])

<div class="popend-wrapper show" ng-cloak ng-if="is_popend('{{ $name }}')" {{ $attributes }}>
    <div class="popend {{ $class ?? '' }}" tabindex="-1" aria-labelledby="popendLabel" aria-modal="true" role="dialog">
        <div class="popend-header">
            <h5 class="sentence-case text-[1.25rem] font-[600]">{{ $title ?? '' }}</h5>
            <span ng-click="popDown('{{ $name }}')" class="btn-text btn-close text-reset"></span>
        </div>
        <div class="popend-body relative z-10">
            {{ $slot }}
        </div>
        @if (isset($colorful))
            <img src="{{ asset('svg/frame.svg') }}" alt="frame"
                class="absolute bottom-0 w-full opacity-20 right-0" />
        @endif
    </div>
    <div class="popend-backdrop" ng-click="popDown('{{ $name }}')"></div>
</div>
