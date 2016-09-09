/****************************************************************************************************************
 * controller
 ****************************************************************************************************************/
angular.module('lawn').controller('SetupController', function ($http, $location){

      var vm = this;

      // try to get the data from existing file
      $http.get('server.php?q=setup.get').then(function (response){
         vm.nickname = response.data.nickname;
         vm.code = response.data.code;
         vm.url = response.data.url;
      });

      /**
       * call server to store data
       */
      vm.save = function (){
         $http.post('server.php?q=setup.set&nickname=' + encodeURI(vm.nickname) + '&code=' + encodeURI(vm.code) + '&url=' + encodeURI(vm.url)).then(function (){
            $location.path("/");
         });
      };
   }
);

