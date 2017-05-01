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
    .constant('euiHost', lcdd.search.request)
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

        $scope.$watch('indexVM.query.value()', function(newVal, oldVal){
          $log.log(newVal.substring(0, newVal.length-1));
          $scope.userQuestion = newVal.substring(0, newVal.length-1);
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

        $scope.showQuestion = function(slug) {
          return Routing.generate('dwl_lcdd_get_question', { slug: slug });
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
            currentPage: 1,
            itemsPerPage: 20,
            items: []
          },
          unqualified : {
            currentPage: 1,
            itemsPerPage: 20,
            items: [],
          }
        };

        $scope.start = function(type) {
          return (($scope.pagination[type].currentPage - 1) * $scope.pagination[type].itemsPerPage);
        };
        $scope.end = function(type) {
          return $scope.start(type) + $scope.pagination[type].itemsPerPage;
        };

        $scope.setPage = function (pageNo) {
          $scope.pagination[$scope.type].currentPage = pageNo;
        };

        $scope.initSettings = function(userQuestion, searchUrl, isSpeaker) {
            $scope.userQuestion=userQuestion;
            $scope.searchUrl=searchUrl;
            $scope.isSpeaker=isSpeaker;
            $scope.getQuestions();
        };

        $scope.getPath = function(slug){
          return $window.Routing.generate('dwl_lcdd_get_question', { slug: slug });
        };

        $scope.editPath = function(id){
          return $window.Routing.generate('question_edit', { id: id });
        };

        $scope.updatePath = function(id){
          return $window.Routing.generate('question_qualify', { id: id });
        };

        $scope.setType = function(type){
            $scope.type=type;
        };

        $scope.$watch('categories', function(newVal, oldVal) {
            $scope.showAllCategories = true;
            if(typeof(newVal) != 'undefined') {
              for (var slug in newVal) {
                if(newVal[slug]) {
                  $scope.showAllCategories = false;
                }
              }
            }
        }, true);

        $scope.$watch('tags', function(newVal, oldVal){
            $scope.showAllTags = true;
            if(typeof(newVal) != 'undefined') {
              for (var slug in newVal) {
                if(newVal[slug]) {
                  $scope.showAllTags = false;
                }
              }
            }
        }, true);

        var isPropExists = function (prop, needle, haystack){
            for (var i=0; i < haystack.length; i++) {
                if (haystack[i][prop] === needle) {
                    return true;
                }
            }
            return false;
        }

        $scope.filterCategories = function(question) {

          var showQuestion = true;

          if(!$scope.showAllCategories) {

            if(!question.categories.length) {
              showQuestion = false;
            } else {
              for (var slug in $scope.categories) {
                if(!isPropExists('slug', slug, question.categories) && $scope.categories[slug]) {
                    showQuestion = false;
                }
              }
            }

          }

          return showQuestion;

        };

        $scope.dateDiff = function (date) {

          var diff = [];
          var questionDate = new Date(date.date);
          var now = new Date();
          var timeSince = Math.abs(now.getTime() - questionDate.getTime()) / 1000;

          // calculate (and subtract) whole days
          var days = Math.floor(timeSince / 86400);
          timeSince -= days * 86400;
          if(days) {
            diff.push(days+' jour'+(days>1?'s':''));
          } else {
            diff.push('Aujourd\'hui');
          }

          // calculate (and subtract) whole hours
          // var hours = Math.floor(timeSince / 3600) % 24;
          // timeSince -= hours * 3600;
          // if(hours) {
          //   diff.push(hours+' heure'+(hours>1?'s':''));
          // }

          // calculate (and subtract) whole minutes
          // var minutes = Math.floor(timeSince / 60) % 60;
          // timeSince -= minutes * 60;
          // if(minutes) {
          //   diff.push(minutes+' minute'+(minutes>1?'s':''));
          // }

          // what's left is seconds
          // var seconds = Math.floor(timeSince % 60);  // in theory the modulus is not required
          // if(seconds) {
          //   diff.push(seconds+' seconde'+(seconds>1?'s':''));
          // }

          return diff.join(', ');

        };

        $scope.filterTags = function(question) {

          var showQuestion = true;

          if(!$scope.showAllTags) {

            if(!question.civilTags.length && !question.legalTags.length) {
              showQuestion = false;
            } else {

              var qTags = question.civilTags.slice(0);
              for (var il = 0; il < question.legalTags.length; il++) {
                if(!isPropExists('slug', question.legalTags[il].slug, qTags)) {
                  qTags.push(question.legalTags[il]);
                }
              }

              for (var slug in $scope.tags) {
                if(!isPropExists('slug', slug, qTags) && $scope.tags[slug]) {
                    showQuestion = false;
                }
              }
            }

          }

          return showQuestion;

        };

        $scope.filterUnqualified = function(question) {

          var showQuestion = true;

          if(typeof(question.qualified_question) != 'undefined' && question.qualified_question.id != null) {
              showQuestion = false;
          }

          return showQuestion;

        };

        $scope.getQuestions = function(){

            var req = {
              method: 'GET',
              url: $scope.searchUrl,
              data: {
                format: 'json',
                question:$scope.userQuestion
              }
            }

            $http(req).then(function(response){

              for (var i = 0; i < response.data.qs.length; i++) {

                if(response.data.qs[i].qualified) {
                  $scope.pagination['qualified'].items[$scope.pagination['qualified'].items.length] = response.data.qs[i];
                } else {
                  $scope.pagination['unqualified'].items[$scope.pagination['unqualified'].items.length] = response.data.qs[i];
                }

              }

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

angular
    .module('article', ['ngSanitize', 'ui.bootstrap', 'elasticui', 'ui.select', 'dwl.legi'])
    .config(function($interpolateProvider){
        $interpolateProvider
            .startSymbol('{[{')
            .endSymbol('}]}');
    })
    .filter('tagsReplace',function() {
        return function(input) {
            function unique(arr) {
                var u = {}, a = [];
                for(var i = 0, l = arr.length; i < l; ++i){
                    if(!u.hasOwnProperty(arr[i])) {
                        a.push(arr[i]);
                        u[arr[i]] = 1;
                    }
                }
                return a;
            }
            var tags = unique(input.split('|'));
            if (input) {
                return '<li>'+tags.join('</li><li>')+'</li>';
            }
        }
    })
    .filter('propsFilter', function() {
      return function(items, props) {
        var out = [];

        if (angular.isArray(items)) {
          var keys = Object.keys(props);

          items.forEach(function(item) {
            var itemMatches = false;

            for (var i = 0; i < keys.length; i++) {
              var prop = keys[i];
              var text = props[prop].toLowerCase();
              if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
                itemMatches = true;
                break;
              }
            }

            if (itemMatches) {
              out.push(item);
            }
          });
        } else {
          // Let the output be the input untouched
          out = items;
        }

        return out;
      };
    })
    .constant('euiHost', legi.search.request)
    .controller("articleCtrl", ['$scope', '$window', '$log', '$http', function($scope, $window, $log, $http) {

      $scope.placeholder="Rechercher un numero d'article";
      $scope.userQuery="";
      $scope.selected=[];
      $scope.type="";
      $scope.tags=[];

      if (typeof $window['legalTaggle'] == "undefined") {
        $window['legalTaggle'] = new $window.Taggle('legalTags', {
          'additionalTagListClasses': 'list-inline',
          'bootstrapInput': true,
          'placeholder': "Saisissez un mot-cle...",
          'additionalTagListLiClasses': 'col-xs-12',
          'additionalTagListLiInputClasses': 'col-xs-12',
          'onTagRemove': function (event, tag) {
            $window.jQuery('dwl-legi-tags[type="legal"] [data-value="'+tag+'"]').show();
          },
          'hiddenInputName': 'dwl_lcdd_searchbundle_question[legalTags][]'
        });
      }
      if (typeof $window['civilTaggle'] == "undefined") {
        $window['civilTaggle'] = new $window.Taggle('civilTags', {
          'additionalTagListClasses': 'list-inline',
          'bootstrapInput': true,
          'placeholder': "Saisissez un mot-cle...",
          'additionalTagListLiClasses': 'col-xs-12',
          'additionalTagListLiInputClasses': 'col-xs-12',
          'onTagRemove': function (event, tag) {
            $window.jQuery('dwl-legi-tags[type="civil"] [data-value="'+tag+'"]').show();
          },
          'hiddenInputName': 'dwl_lcdd_searchbundle_question[civilTags][]'
        });
      }
      $scope.addSavedTags = function(type, tags) {
        $scope.type=type;
        $scope.tags=tags;
        $window[type+'Taggle'].add(tags);
      };
      $scope.addTag = function(tag, type) {
        $window[type+'Taggle'].add(tag);
        $window.jQuery('dwl-legi-tags[type="'+type+'"] [data-value="'+tag+'"]').hide();
      };

      $scope.getLegiByIds = function(){
        var legiIds = $scope.getLegiIds();
        for (var i = 0; i < legiIds.length; i++) {
          $http({
            method: 'POST',
            url: legi.search.request+'/'+legi.search.index+'/_search',
            data: {
              "query": {
                "match": {
                  "DWL.TEXT.uniqueid":{
                      "query":legiIds[i]
                  }
                }
              }
            }
          }).then(function successCallback(response) {
            $scope.selected.push(response.data.hits.hits[0]);
          });
        }
      }
      $scope.getLegiIds = function(){
        var legiIdsString = jQuery('#dwl_lcdd_searchbundle_question_legiIds').val();
        legiIds = legiIdsString.split('|');
        if (legiIds[0] == "") {
          legiIds = [];
        }
        return legiIds;
      };
      $scope.setLegiIds = function(legiIds){
        var legiIdsString = legiIds.join('|');
        jQuery('#dwl_lcdd_searchbundle_question_legiIds').val(legiIdsString);
        return true;
      };
      $scope.addLegiIds = function(item, model){
        var legiIds = $scope.getLegiIds();
        legiIds.push(item._source.DWL.TEXT.uniqueid);
        $scope.setLegiIds(legiIds);
        return true;
      };
      $scope.removeLegiIds = function(item, model){
        var legiIds = $scope.getLegiIds();
        var uniqueIdIndex = legiIds.indexOf(item._source.DWL.TEXT.uniqueid);
        legiIds.splice(uniqueIdIndex, 1);
        $scope.setLegiIds(legiIds);
        return true;
      };
    }]).directive('artUiSelect', function() {
      return {
        require: 'uiSelect',
        link: function(scope, element, attrs, $select) {
          scope.$watch('$select.search', function(newVal, oldVal){
            scope.$parent.userQuery=$select.search;
          });
          scope.$watch('$parent.selected', function(newVal, oldVal){
            $select.selected=newVal;
          });
        }
      };
    }).directive('artEuiSelect', function() {
      return {
        link: function(scope, element, attrs, querystring) {
          scope.$watch('userQuery', function(newVal, oldVal){
            scope.querystring=scope.userQuery;
          });
        }
      };
    });
jQuery(document).ready(function(){
  angular.bootstrap(document.getElementsByClassName('dwl-article-form'), ['article']);
});
