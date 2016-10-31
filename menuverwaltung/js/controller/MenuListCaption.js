/**
 * Created by damia on 30.10.2016.
 */
myApp.controller('MenuListCaption',['$scope','$uibModal','$http', function($scope,$uibModal,$http){

    $scope.settings = function (id) {

        var modalScope = $scope.$new();

        var modalInstance = $uibModal.open({
            templateUrl: 'views/settingsbox.html',
            controller: 'SettingsBox',
            scope: modalScope,

            resolve: {
                param: function() {
                    //$log.log($scope.dt);
                    return {'data':$scope.data,'mydata':id};
                }
            }
        });

        modalScope.modalInstance = modalInstance;
        modalInstance.result.then(function(result) {

        },null);
    };


    $http({
        method: 'GET',
        url: 'backend/main.php',
        headers: {
            'Accept' : 'application/json; charset=UTF-8',
            'Accept-Charset' : 'charset=utf-8'
        }
    }).then(function(response) {
        var daten = response.data;
        $scope.lists = daten;
        console.log(daten);
    });


    $scope.isChecked = function(item){
        if(item == 0)
            return false;
        else if(item == 1)
            return true;
    };

    $scope.$watch('lists',function(lists) {
        $scope.modelAsJson = angular.toJson(lists,true);
    },true);





    $scope.changeBox = function (item) {
        //console.log(item);
    };
}]);