/****************************************************************************************************************
 * controller
 ****************************************************************************************************************/
angular.module('lawn').controller('MainController', function ($scope, $http, $interval){

      var vm = this, $handle = null, $checkEvery = 10;

      /**
       * helper function for updating data
       */
      function $render(){
         $http.get('server.php?q=info').then(function (response){
            console.info(response);
            vm.data = response.data;
         });
      }

      /**
       * on destroy of controller -> cleanup
       */
      $scope.$on("destroy", function (){
         $interval.cancel($handle);
      });

      // create interal
      $handle = $interval(function (){
         $render();
      }, $checkEvery * 1000);

      // for initial data
      $render();

      /**
       * depending on load the batter has different color
       */
      vm.batteryColor = function (){
         if (vm.data.perc_batt < 50) {
            return "low";
         }
         if (vm.data.perc_batt < 70) {
            return "medium";
         }
         return "high";
      };

      vm.isCharging = function() {
         return (vm.data.batteryChargerState !== 'idle');
      };
      vm.isHome = function() {
         return (vm.data.state === 'home');
      };
      vm.isFollowing = function() {
         return (vm.data.state === 'following wire');
      };
   }
);

