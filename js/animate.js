/****************************************************************************************************************
 * directive for animation
 ****************************************************************************************************************/
angular.module('lawn').directive('animate', function () {
   return {
     restrict:'A',
      scope: {
         text:"="
      },
      link:function(scope, ele, attr) {
         var cl = attr.animate ||"bounceInDown";
         ele.addClass(cl);

         scope.$watch('text', function() {
            if (scope.text === '') {
               ele.removeClass('animated');
            } else {
               ele.addClass('animated');
            }
         });
      }
   };
});
