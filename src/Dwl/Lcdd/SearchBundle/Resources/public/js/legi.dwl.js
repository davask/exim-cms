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
