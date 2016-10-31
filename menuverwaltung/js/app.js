var myApp = angular.module('menuVerwaltung',['dndLists','ngRoute','ui.bootstrap']);

myApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'views/MenuList.html',
                controller: 'MenuList'
            })
            .when('/denied', {
                templateUrl: 'vies/denied.html'
            })
            .otherwise({
                redirectTo: '/'
            });
    }
]);


myApp.run(function($rootScope,$timeout,$location,$http) {
    $rootScope.$on('$routeChangeSuccess',function(){
        $rootScope.isViewLoading = false;
    });
    
    $rootScope.$on('$routeChangeStart',function (e,current) {
        $rootScope.isViewLoading = true;

        $http({
            method: 'GET',
            url: 'backend/main.php?method=checkright'
        }).then(function(response) {
           erlaubt=response.data.login;

            if(erlaubt) {
                if(angular.isObject(current.$$route)) {
                    if(current.$$route.orginalPath !== '/denied') {
                        $location.path('/');
                    }
                }
            }
            else
            {
                $location.path('/denied');
            }
        });
    })
});