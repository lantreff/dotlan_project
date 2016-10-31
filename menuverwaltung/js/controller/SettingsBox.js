myApp.controller('SettingsBox',function($scope,$uibModalInstance,items,$http) {
    $scope.items = items;
    $scope.id = items;

    $scope.loading = 'Laden. Bitte warten';
    $http({
        method: 'GET',
        url: 'backend/main.php?method=getentry&entryid='+$scope.id.toString(),

    }).then(function(response) {
        console.log(response);
        $scope.menudaten = response.data;
        $scope.loading = '';
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


    function speichern()
    {
        $http({
            method: 'POST',
            url: 'backend/main.php?method=saveeintrag&id='+$scope.id.toString(),
            data: {
                user:$scope.menudaten
            },
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        }).then(function(data){
            $scope.speichermeldung = data.data.response;
        })
    }

    function close()
    {
        $uibModalInstance.dismiss('cancel');
    }


    $scope.save = function() {
        speichern();
    };

    $scope.saveandquit = function() {
        speichern();
        close();
    };

    $scope.close = function() {
        close();
    }
});