import { template } from "lodash"

app.directive('profile', function(){
  return {
    restrict: 'A',
    replace: true,
    scope: {
      profile: '=',
      src: '@',
    },
    
    link: function(scope, element, attr) {
      console.log(scope.profile);
      if (!scope.profile || scope.src || !element.is('img')) {
        return;
      }
      let src = '/images/avatar-u.png';
      let gender = '';
     
      if (scope.profile.image) {
        src = scope.profile.image;
      }
      else {
        
        if ('gender' in scope.profile) {
          gender = scope.profile.gender;
        }
        else if (scope.profile.role && typeof scope.profile[scope.profile.role] === 'object' && scope.profile[scope.profile.role] !== null && ('gender' in scope.profile[scope.profile.role])) {
          gender = scope.profile[scope.profile.role].gender;
        }
      }
      
      gender = gender.toLowerCase();

        if (gender === 'male') {
          src = '/images/avatar-m.png';
        }
        if (gender === 'female') {
          src = '/images/avatar-f.png';
        }
      

      const newImg = angular.element(`<img/>`);

        Array.from(element[0].attributes).forEach(attr => {
          if (!['profile'].includes(attr.nodeName)) {
            newImg.attr(attr.nodeName, attr.nodeValue);
          }
        });
        newImg.attr('src', src);

        element.replaceWith(newImg);
        

        
    }
  }
})