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
    .controller("formCtrl", ['$scope', '$window', '$log', '$http', function($scope, $window, $log, $http) {
        $scope.block = null;
        $scope.display = 'block';
        $scope.form = 'question';
        $scope.isBlockDisplay = false;
        $scope.isInlineDisplay = false;
        $scope.isAllwaysSubmitingNewQuestion = false;

        $scope.initSettings = function(block, display, form) {
            $scope.indexVM.autoLoad=false;
            $scope.block=block;
            $scope.display=display;
            $scope.form=form;

            if ($scope.display != 'inline') {

              $scope.placeholder = 'Vous avez une question ?';
              $scope.isBlockDisplay = true;

              if ($scope.display == 'bottom') {
                $scope.isAllwaysSubmitingNewQuestion = true;
              }

            } else {

              $scope.placeholder = 'Rechercher ...';
              $scope.isInlineDisplay = true;

            }

        };

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

            var $form = $window.jQuery('.dwl-search-block-question-form[name="'+$scope.form+'"]');
            var values = {};
            var notyTarget = '.dwl-search-block-question-form[name="'+$scope.form+'"]' + ' .result';

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
          var $form = $window.jQuery('[name="header_'+$scope.form+'"]');
          $form.submit();
        };

    }])
    .config(['$httpProvider', function($httpProvider) {
        $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
    }]);
jQuery(document).ready(function(){
  jQuery('.dwl-search-block').each(function(){
    var form = jQuery('.dwl-search-block-search-hero-form', this).data('form');
    jQuery('.dwl-search-block-question-form[name="'+form+'"] .btn-question').attr('ng-click','initForm()');
  });
  angular.bootstrap(document.getElementsByClassName('dwl-search-block'), ['leges']);
});

angular
    .module('search', ['ngSanitize', 'ui.bootstrap'])
    .filter('slice', function() {
      return function(arr, start, end) {
        return arr.slice(start, end);
      };
    })
    .config(function($interpolateProvider){
        $interpolateProvider
            .startSymbol('{[{')
            .endSymbol('}]}');
    })
    .controller("searchCtrl", ['$scope', '$window', '$log', '$http', function($scope, $window, $log, $http) {

        $scope.userQuestion = '';
        $scope.searchUrl = '#';

        $scope.questions = '';
        $scope.type = 'qualified';

        $scope.pagination = {
          qualified : {
            totalItems: 0,
            currentPage: 1,
            itemsPerPage: 20,
            items: []
          },
          unqualified : {
            totalItems: 0,
            currentPage: 1,
            itemsPerPage: 20,
            items: [],
          }
        };

        $scope.categories = [];
        $scope.tags = [];

        $scope.start = function(type) {
          return (($scope.pagination[type].currentPage - 1) * $scope.pagination[type].itemsPerPage);
        };
        $scope.end = function(type) {
          return $scope.start(type) + $scope.pagination[type].itemsPerPage;
        };

        $scope.setPage = function (pageNo) {
          $scope.pagination[$scope.type].currentPage = pageNo;
        };

        $scope.initSettings = function(userQuestion, searchUrl) {
            $scope.userQuestion=userQuestion;
            $scope.searchUrl=searchUrl;
            $scope.getQuestions();
        };

        $scope.getPath = function(qid){
          return $window.Routing.generate('dwl_lcdd_question', { id: qid });
        };

        $scope.setType = function(type){
            $scope.type=type;
        };

        $scope.setPage = function (pageNo) {
            $scope.pagination[$scope.type].currentPage = pageNo;
        };

        $scope.getQuestions = function(){

            var req = {
              method: 'POST',
              url: $scope.searchUrl,
              data: {
                format: 'json',
                question:$scope.userQuestion
              }
            }

            $http(req).then(function(response){

              $scope.pagination['qualified'].currentPage = 1;
              $scope.pagination['qualified'].totalItems = 0;
              $scope.pagination['unqualified'].currentPage = 1;
              $scope.pagination['unqualified'].totalItems = 0;

              for (var i = 0; i < response.data.qs.length; i++) {

                if(response.data.qs[i].qualified) {
                  $scope.pagination['qualified'].items[$scope.pagination['qualified'].items.length] = response.data.qs[i];
                } else {
                  $scope.pagination['unqualified'].items[$scope.pagination['unqualified'].items.length] = response.data.qs[i];
                }

              }

              $scope.pagination['qualified'].totalItems = $scope.pagination['qualified'].items.length;
              $scope.pagination['unqualified'].totalItems = $scope.pagination['unqualified'].items.length;

              $scope.questions = response.data.qs;

            }, function(response){
              $log.log(response);
            });

        };

        $scope.userSearch = function(){
            $scope.getQuestions();
        };

    }]);
jQuery(document).ready(function(){
  angular.bootstrap(document.getElementsByClassName('dwl-search-page'), ['search']);
});
