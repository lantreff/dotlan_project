/**
 * Created by damia on 27.10.2016.
 */
var myApp = angular.module('menuVerwaltung',['dndLists','ngCookies','ngRoute']);


myApp.config(function($routeProvider) {
   $routeProvider
       .when('/', {
          templateUrl: 'views/denied.html'
       })
       .when('/menuliste', {
           templateUrl: 'views/menuliste.html',
           controller: 'MenuListCaption'
       })
       .when('/denied', {
           templateUrl: 'views/denied.html',
       });
});


myApp.run(function ($rootScope,$timeout,$location,$http) {

    $rootScope.$on('$routeChangeSuccess',function() {
        $timeout(function(){
            $rootScope.isViewLoading = false;
        });
    });

    $rootScope.$on('$routeChangeStart',function(e,current) {
        $rootScope.isViewLoading = true;

        $http({
            method: 'GET',
            url: 'http://localhost/admin/projekt/menuverwaltung/backend/'
        });

        if(false) {
            if (angular.isObject(current.$$route)) {
                if (current.$$route.orginalPath !== '/denied') {
                    $location.path('/denied');
                }
            }
        }
    });

});

myApp.controller('MenuListCaption',function($scope,$http){

    $http({
        method: 'GET',
        url: 'http://localhost/admin/projekt/menuverwaltung/backend/main.php',
        headers: {
            'Accept' : 'application/json; charset=UTF-8',
            'Accept-Charset' : 'charset=utf-8'
        }
    }).then(function(response) {
        var daten = response.data;
        $scope.lists = daten;
        console.log(daten);
    });

    $scope.infoboxs = function(number) {
      switch(number) {
          case 1:
              $scope.infobox = "Wird angezeigt wenn ein Session excistiert. Ist in der Regel der Fall";
              break;
          case 2:
              $scope.infobox = "Wird angezeigt wenn man als User auf der Website registriert ist";
              break;
          case 3:
              $scope.infobox = "Menu eintrag wird nur angezeigt wenn man Admin ist";
              break;
          default:
              $scope.infobox = "";

      }
    };
    $scope.isChecked = function(item){
        if(item == 0)
            return false;
        else if(item == 1)
            return true;
    };

    $scope.$watch('lists',function(lists) {
        $scope.modelAsJson = angular.toJson(lists,true);
    },true);


    $scope.saveitems = function(lists) {
        $http({
            method: 'POST',
            url: 'http://localhost/admin/projekt/menuverwaltung/backend/main.php?method=save',
            data: {
                user:$scope.lists
                //lists
            },
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        }).success(function(data) {
            $scope.debug = data;
        });
      //$http.post('http://localhost/admin/projekt/menuverwaltung/backend/main.php?method=save',{user:$scope.lists}).success(
         // function(data) {
         //     $scope.debug = data
         // }

    };

    $scope.changeBox = function (item) {
        //console.log(item);
    };
    /*
   $scope.lists = [
       {
           label: "Hauptmenu",
           max: 5,
           allowedTypes : ['hauptmenu'],
           eintrage: [
               {name: "eins",type: "hauptmenu"},
               {name: "zwei", type: "hauptmenu"}
           ]

       },
       {
           label: "Orga",
           max: 6,
           allowedTypes : ['orga'],
           eintrage: [
               {name: "eins",type: "orga"},
               {name: "blabla", type: "orga"}
           ]
       }

   ];
    */


});