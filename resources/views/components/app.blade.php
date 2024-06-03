<!DOCTYPE html>
<html 
    ng-cloakx
    lang="en" 
    ng-app="cscPortal" 
    ng-controller="RootController" 
    ng-class="{[theme]: true}"
    ng-resize="handleResize()" 
    ng-init="init()" 
    {{ $attributes }}
    custom-on-change>
    
    {{ $slot }}
</html>
