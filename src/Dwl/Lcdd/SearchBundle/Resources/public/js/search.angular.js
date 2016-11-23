angular
    .module('leges', ['elasticui', 'ngSanitize'])
    .filter('nquote',function() {
        return function(input) {
            if (input) {
                return input.replace(/&#39;|U\+0027/g, '\'');
            }
        }
    })
    .config(function($interpolateProvider){
        $interpolateProvider
            .startSymbol('{[{')
            .endSymbol('}]}');
    })
    .controller("formCtrl", ['$scope', '$log', function($scope, $log) {
        $scope.querystorage = '';
        $scope.hide = function() {
          $scope.querystorage = $scope.indexVM.query.query();
          $log.log($scope.querystorage);
          $scope.indexVM.query.query('');
          $scope.indexVM.refresh(0);
        };
        $scope.show = function() {
          $log.log($scope.querystorage);
          $scope.indexVM.query.query($scope.querystorage);
          $scope.indexVM.refresh(0);
        };
    }])
    .constant('euiHost', lcdd.elastic.request) // ACTION: change to cluster address
    .constant("euiIndex", lcdd.elastic.index);

angular.bootstrap(document.getElementById('dwl-search-block-search-home-form'), ['leges']);
