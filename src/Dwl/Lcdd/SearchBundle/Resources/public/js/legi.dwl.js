var elasticui;
(function (elasticui) {
    var widgets;
    (function (widgets) {
        var directives;
        (function (directives) {
            // The widgets show how to create reusable components on top of ElasticUI.
            // You can also directly use the directive.template html in your front-end (see docs/widgets.md for more info)
            var LegiArticleDirective = (function () {
                function LegiArticleDirective($parse) {
                    var directive = {};
                    directive.restrict = 'E';
                    directive.scope = true;
                    directive.link = {
                        'pre': function (scope, element, attrs) {
                            elasticui.util.AngularTool.setupBinding($parse, scope, attrs, ["field", "highlights"]);
                        }
                    };
                    // TODO: should be debounced
                    var queryMatch = 'ejs.MatchQuery(field, ( querystring.length > 0 ? \'Article \'+querystring : \'\' ))';
                    // var queryMatch = 'ejs.WildcardQuery(field, \'Article \'+querystring+\'*\')';
                    directive.template = '\
<input type="text" class="dwl-article-form-article-input form-control" placeholder="{[{placeholder}]}" \
    eui-query="' + queryMatch + '" ng-model="querystring" \
    eui-highlight="ejs.Highlight(highlights).preTags(\'<b>\').postTags(\'</b>\')" \
    eui-enabled="true" \
    />\
';
                    return directive;
                }
                LegiArticleDirective.$inject = ['$parse'];
                return LegiArticleDirective;
            })();
            directives.LegiArticleDirective = LegiArticleDirective;
            directives.directives.directive('legiArticle', LegiArticleDirective);

        })(directives = widgets.directives || (widgets.directives = {}));
    })(widgets = elasticui.widgets || (elasticui.widgets = {}));
})(elasticui || (elasticui = {}));

var dwlLegi;
(function (dwlLegi) {
    var directives;
    (function (_directives) {
        _directives.directives = angular.module('dwlLegi.directives', []);
    })(directives = dwlLegi.directives || (dwlLegi.directives = {}));
})(dwlLegi || (dwlLegi = {}));
var dwlLegi;
(function (dwlLegi) {
    var directives;
    (function (directives) {
        // The widgets show how to create reusable components on top of dwlLegi.
        // You can also directly use the directive.template html in your front-end (see docs/widgets.md for more info)
        var dwlLegiTagsDirective = (function () {
            function dwlLegiTagsDirective($parse) {
                var directive = {};
                directive.restrict = 'E';
                directive.scope = {
                    "selected": "=",
                    "type": "@"
                };
                directive.link = function (scope, element, attrs) {
                    scope.$parent.addSavedTags(attrs['type'], attrs['tags'].split('|'));
                    scope.addTag = scope.$parent.addTag;
                };

                directive.template = '\
<div style="font-size:12px;" ng-if="type == \'legal\'">\
    suggestions de mot-cl&eacute;s \
    <div ng-repeat="doc in selected track by $index">\
        <ul class="list-inline">\
            <li><b>{[{doc._source.DWL.TEXT.TITLE.code}]} - {[{doc._source.DWL.TEXT.TITLE.short}]}:</b></li> \
            <li data-value="{[{tag}]}" class="btn btn-default tag-{[{$parent.$index}]}-{[{$index}]}" ng-repeat="tag in doc._source.DWL.TEXT.TAGS.content.split(\'|\') | orderBy: \'+\' track by $index" ng-click="addTag(tag, type)">\
                <span>{[{ tag }]}</span>\
                <span style="font-size: 14px;line-height: 1.4;" class="close">&nbsp;+</span>\
            </li>\
        </ul>\
    </div>\
</div>\
';
                return directive;
            }
            dwlLegiTagsDirective.$inject = ['$parse'];
            return dwlLegiTagsDirective;
        })();
        directives.dwlLegiTagsDirective = dwlLegiTagsDirective;
        directives.directives.directive('dwlLegiTags', dwlLegiTagsDirective);

    })(directives = dwlLegi.directives || (dwlLegi.directives = {}));
})(dwlLegi || (dwlLegi = {}));
angular.module('dwl.legi', ['dwlLegi.directives']);
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
  angular.bootstrap(document.getElementsByClassName('dwl-article-form-elements'), ['article']);
});
