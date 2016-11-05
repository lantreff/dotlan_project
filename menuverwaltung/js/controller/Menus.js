/**
 * Created by damia on 05.11.2016.
 */
myApp.controller('Menus',function($scope,$uibModal,$http,$routeParams,$log,$location) {

    loadListe();

    $scope.settings = function(dbid,size) {
        einstellungen(dbid,size);
    };

    $scope.saveitems = function() {
        saveitems();
    };

    $scope.delete = function(dbid,name) {
        var r = confirm('Wollen Sie '+name.toString()+' Wirklich löschen?');
        if(r == true) {
            console.log(dbid);
            loeschen(dbid);
        }
    };

    $scope.insertitem = function() {
        insertnewmenuitem($routeParams.eintrag.toString());

    };

    function insertnewmenuitem(menu)
    {
        $http({
            method: 'GET',
            url: 'backend/main.php?method=insertmenu&menu='+menu.toString()
        }).then(function(response){
            sid = response.data;
            if(sid.response == "ok")
            {
                einstellungen(sid.lastid);
            }
            console.log(sid);
        });
    }




    function loeschen(dbid) {
        $http({
            method: 'GET',
            url: 'backend/main.php?method=deleteentry&id='+dbid.toString()
        }).then(function(response) {
            loadListe();
        });
    }


    function saveitems() {
        $scope.speicherung = 'Menu wird gespeichert. Bitte Warten';
        //console.log('Save');
        $http({
            method: 'POST',
            url: 'backend/main.php?method=saveanordnung',
            data: {
                user:$scope.list
                //lists
            },
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        }).success(function(data) {
          //  console.log(data);
            console.log(data);
            $scope.speicherung = data.response;
            if(data.erfolg == true) {
                console.log('erfolg');
                location.path = '/';
            }

        });
    }

    function loadListe()
    {
        $scope.loading = 'Menuliste wird geladen...';

        $http({
            method: 'GET',
            url: 'backend/main.php?method=getmenus&menueeintrag='+$routeParams.eintrag.toString(),
            headers: {
                'Accept': 'application/json; charset=UTF-8',
                'Accept-Charset': 'charset=utf-8'
            }
        }).then(function(response) {
            $scope.list = response.data;
            console.log(response);
        })
    }

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
            loadListe();
        })
    }
});