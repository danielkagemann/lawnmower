/****************************************************************************************************************
 * controller
 ****************************************************************************************************************/
angular.module('lawn').controller('SetupController', function ($http, $location){

      var vm = this;

      /**
       * call server to store data
       */
      vm.save = function (){
         $http.post("server.php?q=setup", {nick: vm.nickname, code:vm.code}).then(function() {
            $location.path("/");
         });
      };
   }
);

