/**
 * Created by damia on 30.10.2016.
 */
myApp.controller('MenuList', function($scope,$uibModal,$log,$http) {

    //loadmenuListe();

    function loadmenuListe() {
        $scope.loading = 'MenuListe wird geladen.....';

        $http({
            method: 'GET',
            url: 'backend/main.php?method=getmenus',
            headers: {
                'Accept': 'application/json; charset=UTF-8',
                'Accept-Charset': 'charset=utf-8'
            }
        }).then(function (response) {
            var daten = response.data;
            $scope.lists = daten;
            //console.log(daten);
            $scope.loading = '';
        });
    }



    $scope.settings = function(dbid,size) {

        einstellungen(dbid,size);
    };

});