/****************************************************************************************************************
 * routing
 ****************************************************************************************************************/
angular.module('lawn').config(function ($routeProvider){
   $routeProvider
      .when("/", {
         templateUrl: "page/main.html",
         controller: 'MainController',
         controllerAs: 'ctrl'
      })
      .when("/setup", {
         templateUrl: "page/setup.html",
         controller: 'SetupController',
         controllerAs: 'ctrl'
      })
      .when("/lost", {
         templateUrl: "page/lost.html",
      })
      .otherwise({
         templateUrl: "page/error.html"
      });
});