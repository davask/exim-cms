if(typeof(lcdd) != 'undefined' && typeof(lcdd.elastic) != 'undefined' && typeof(lcdd.elastic.request) != 'undefined') {

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
      .controller("formCtrl", ['$scope', '$window', '$log', '$http', 'euiHost', function($scope, $window, $log, $http) {

          $scope.userQuestion = '';
          $scope.$watch('indexVM.query.query()', function(newVal, oldVal){
            $scope.userQuestion = newVal;
            $scope.unSubmitNewQuestion();
          });

          $scope.$watch('indexVM.results.hits.hits', function(newVal, oldVal){
            if(typeof(newVal) != 'undefined' && newVal.length) {
              $scope.unSubmitNewQuestion();
            }
          });

          $scope.$watch('isSubmitingNewQuestion', function(newVal, oldVal){
            if(newVal) {
              $scope.hideResultsList();
              $scope.showNewQuestion();
            } else {
              $scope.hideNewQuestion();
              $scope.showResultsList();
            }
          });

          $scope.isSubmitingNewQuestion = false;
          $scope.submitNewQuestion = function() {
            $scope.isSubmitingNewQuestion = true;
          };
          $scope.unSubmitNewQuestion = function() {
            $scope.isSubmitingNewQuestion = false;
          };
          $scope.newQuestion = false;
          $scope.showNewQuestion = function() {
            $scope.newQuestion = true;
          };
          $scope.hideNewQuestion = function() {
            $scope.newQuestion = false;
          };

          $scope.resultsList = true;
          $scope.showResultsList = function() {
            $scope.resultsList = true;
          };
          $scope.hideResultsList = function() {
            $scope.resultsList = false;
          };

          $scope.allowDisplay = true;
          $scope.showDisplay = function() {
            $scope.allowDisplay = true;
          };
          $scope.hideDisplay = function() {
            $scope.allowDisplay = false;
          };

          $scope.initForm = function () {

              var $form = $window.jQuery('[name="'+$window.lcdd.form.name+'"]');
              var values = {};
              // $.each( $form.serializeArray(), function(i, field) {
              //     values[field.name] = field.value;
              // });
              // values[$window.lcdd.form.name+'[submit]'] = null;

              var req = {
                method: $form.attr( 'method' ),
                url: $form.attr( 'action' ),
                data: 'question%5Bsubmit%5D=&'+$form.serialize()
              }

              $http(req).then(function(response){
                var c = 'text-warning';
                if (response.data.success === true) {
                    c = 'text-success';
                }
                $window.jQuery('.result', '[name="'+$window.lcdd.form.name+'"]').noty({
                    text: response.data.message,
                    timeout: 2000,
                    type: response.data.success ? 'success':'warning'
                });
              }, function(response){
                $window.jQuery('.result', '[name="'+$window.lcdd.form.name+'"]').noty({
                    text: response.data.message,
                    timeout: 2000,
                    type: 'error'
                });
              });

          };

      }])
      .config(['$httpProvider', function($httpProvider) {
          $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
          $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
      }])
      .constant('euiHost', lcdd.elastic.request);
  jQuery(document).ready(function(){
    jQuery('[name="'+lcdd.form.name+'"] .btn-question').attr('ng-click','initForm()');
    angular.bootstrap(document.getElementById('dwl-search-block-search-home-form'), ['leges']);
  });
}
