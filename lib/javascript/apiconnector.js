/**
 * Created by damia on 28.10.2016.
 */
'use strict';

myApp.service('apiconnector',function apiconnector ($http) {

//angular.module('myApp').service('apiconnector',function apiconnector ($http) {
    var apiBase = '';
    var obj = {};

    obj.get = function(q) {
        return $http.get(apiBase + q).then(function(results){
            return results.data;
        });
    };

    obj.post = function (q,object) {
        return $http.post(apiBase + q).then(function (results) {
            return results.data;
        });
    };

    obj.put = function (q, object) {
        return $http.put(apiBase + q, object).then(function(results){
            return results.data;
        });
    };

    obj.delete = function(q) {
        return $http.delete(apiBase + q).then(function (results) {
            return results.data;
        });
    };

    return obj;
});
