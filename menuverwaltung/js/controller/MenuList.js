/**
 * Created by damia on 30.10.2016.
 */
myApp.controller('MenuList', function($scope,$uibModal,$log,$http) {

    loadmenuListe();

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

    function insertmenuelement(menu)
    {
        $http({
            method: 'GET',
            url: 'backend/main.php?method=insertmenu&menu='+menu.toString()
        })
        .then(function(response){

            sid = response.data;
            if(sid.response == "ok")
            {
                einstellungen(sid.lastid);
            }
            console.log(sid);
        });
    }

    $scope.delete = function(dbid,name) {
        var r = confirm('Wollen Sie '+name.toString()+' Wirklich löschen?');
        if(r == true) {
            console.log(dbid);
            loeschen(dbid);
        }
    };

    function loeschen(dbid) {
        $http({
            method: 'GET',
            url: 'backend/main.php?method=deleteentry&id='+dbid.toString()
        }).then(function(response) {
            loadmenuListe();
        });
    }

    $scope.saveitems = function(lists) {
        $scope.speicherung = 'Menu wird gespeichert. Bitte Warten';
        console.log('Save');
        $http({
            method: 'POST',
            url: 'backend/main.php?method=saveanordnung',
            data: {
                user:$scope.lists
                //lists
            },
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        }).success(function(data) {
            console.log(data);
            if(data.erfolg == true)
            {
                $scope.speicherung = data.response;
            }
            else
            {
                $scope.speicherung = data.response;
            }

        });
    };

    $scope.insertnewelement = function(menueintrag) {

        //alert(menueintrag);
        insertmenuelement(menueintrag);
    };

    function einstellungen(dbid,size)
    {
        var modalInstance = $uibModal.open({
            templateUrl: 'views/settingsbox.html',
            controller: 'SettingsBox',
            size:size,
            resolve: {
                items: function() {
                    return dbid;
                }
            }
        });

        modalInstance.result.then(function(editedItem) {
            alert(editedItem);
            $scope.selected = editedItem;
        }, function(editedItem) {
            $log.info('Modal dismissed');
            $scope.lists = '';
            loadmenuListe();
        })
    }

    $scope.settings = function(dbid,size) {

        einstellungen(dbid,size);
    };

});