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
      .constant('euiHost', lcdd.elastic.request)
      .constant('lcdd', lcdd)
      .controller("formCtrl", ['$scope', '$window', '$log', '$http', 'lcdd', function($scope, $window, $log, $http, lcdd) {

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

          $scope.isBlockDisplay = false;
          $scope.isInlineDisplay = false;
          if (lcdd.search.display != 'inline') {
            $scope.placeholder = 'Vous avez une question ?';
            $scope.isBlockDisplay = true;
          } else {
            $scope.placeholder = 'Rechercher ...';
            $scope.isInlineDisplay = true;
          }

          $scope.isAllwaysSubmitingNewQuestion = false;
          if (lcdd.search.display == 'bottom') {
            $scope.isAllwaysSubmitingNewQuestion = true;
          }

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

              var $form = $window.jQuery($window.lcdd.form.name);
              var values = {};
              var notyTarget = $window.lcdd.form.name + ' .result';
              // var notyTarget = 'document';

              $log.log($form.length);

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
                $window.jQuery(notyTarget).noty({
                    text: response.data.message,
                    timeout: 2000,
                    type: response.data.success ? 'success':'warning'
                });
              }, function(response){
                $window.jQuery(notyTarget).noty({
                    text: response.data.message,
                    timeout: 2000,
                    type: 'error'
                });
              });

          };

          $scope.showQuestion = function(id) {
            return Routing.generate('dwl_lcdd_question', { id: id });
          };
          $scope.searchQuestion = function() {
            var $form = $window.jQuery('[name="'+$window.lcdd.form.header_name+'"]');
            $form.submit();
          };

      }])
      .config(['$httpProvider', function($httpProvider) {
          $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
          $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
      }]);
  jQuery(document).ready(function(){
    jQuery(lcdd.form.name+' .btn-question').attr('ng-click','initForm()');
    angular.bootstrap(document.getElementsByClassName('dwl-search-block'), ['leges']);
  });
}
