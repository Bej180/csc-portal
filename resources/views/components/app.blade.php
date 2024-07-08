@props(['title'])
<!DOCTYPE html>
<html 
    class="no-ng {% theme %}"
    ng-cloakx
    lang="en" 
    ng-app="cscPortal" 
    ng-controller="RootController" 
    ng-class="{[theme]: true}"
    ng-resize="handleResize()" 
    ng-init="init({{ auth()->check()?'true':'false'}}, '{{$title ?? config("app.title", "Futo CSC Portal")}}')" 
    {{ $attributes }}
    custom-on-change>
    
    {{ $slot }}
</html>
