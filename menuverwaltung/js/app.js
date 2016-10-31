var myApp = angular.module('menuVerwaltung',['dndLists','ngRoute','ui.bootstrap']);

myApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'views/MenuList.html',
                controller: 'MenuList'
            })
            .otherwise({
                redirectTo: '/'
            });
    }
]);