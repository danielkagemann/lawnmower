/****************************************************************************************************************
 * controller
 ****************************************************************************************************************/
angular.module('lawn').controller('MainController', function ($scope, $http, $location, $interval) {

      var vm = this, $handle = null, $checkEvery = 3;
      vm.simulation = true;
      vm.data = {};
      vm.action = "";
      vm.icon = "";

      /**
       * show current state
       */
      function $display() {
         // charging ?
         if (vm.data.batteryChargerState !== 'idle') {
            vm.action = "Aufladen";
            vm.icon = "home";
         }
         // home ?
         if (vm.data.state === 'home' && vm.data.batteryChargerState === 'idle') {
            vm.action = "Ladestation";
            vm.icon = "idle";
         }
         if (vm.data.state === 'grass cutting') {
            vm.action = "Mähen";
            vm.icon = "mowing";
         }
         if (vm.data.state === 'following wire') {
            vm.action = "Grenzschnitt";
            vm.icon = "border";
         }
         if (vm.data.state === 'trapped recovery') {
            vm.action = "Gefangen";
            vm.icon = "trapped";
         }
         if (vm.data.message === 'outside wire') {
            vm.action = "Außerhalb";
            vm.icon = "outside";
         }
      }

      /**
       * helper function for updating data
       */
      function $getData() {
         if (vm.simulation) {
            var states = ['home', 'idle', 'grass cutting', 'following wire', 'trapped recovery', 'outside wire'];
            vm.data.state = parseInt(Math.random() * states.length, 10);
            vm.data.state = states[vm.data.state];
            vm.data.batteryChargerState = vm.data.state==='home' ? 'idle' : '';
            vm.data.perc_batt = parseInt(Math.random() * 100, 10);
            $display()
         } else {
            $http.get('server.php?q=info').then(function (response) {
               vm.data = response.data;
               console.info("state = " + vm.data.state);

               $display();
            }, function (error) {
               // we got an error.
               // todo: redirect to whatever the code is
               $location.path('/lost');
            });
         }
      }

      /**
       * on destroy of controller -> cleanup
       */
      $scope.$on("destroy", function () {
         $interval.cancel($handle);
      });

      // create interal
      $handle = $interval(function () {
         $getData();
      }, $checkEvery * 1000);

      // for initial data
      $getData();

      /**
       * depending on load the batter has different color
       */
      vm.batteryColor = function () {
         if (angular.isDefined(vm.data.perc_batt)) {
            if (vm.data.perc_batt < 50) {
               return "low";
            }
            if (vm.data.perc_batt < 70) {
               return "medium";
            }
         }
         return "high";
      };
   }
);

