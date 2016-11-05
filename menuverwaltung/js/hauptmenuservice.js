/**
 * Created by damia on 05.11.2016.
 */
myApp.factory('routeNavigation',function($route,$location,$http) {

    var routes = [];

    $http({
        method: 'GET',
        url: 'backend/main.php?method=getmenus',
        headers: {
            'Accept': 'application/json; charset=UTF-8',
            'Accept-Charset': 'charset=utf-8'
        }
    }).then(function(response){

        var daten = response.data;
        console.log(daten);
        angular.forEach(daten,function(label) {
           routes.push({
               name: label.label
           });
        });
        //console.log(daten);
    });


    return {
      routes:routes
    };
});

myApp.directive('navigation',function(routeNavigation) {
    return {
        restrict: "E",
        replace: true,
        templateUrl: "navigation.tpl.html",
        controller: function($scope) {
            console.log(routeNavigation.routes);
            $scope.routes = routeNavigation.routes;
        }
    };
});