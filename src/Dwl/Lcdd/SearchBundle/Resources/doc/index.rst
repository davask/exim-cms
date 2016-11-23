Add the following script to your footer scipt in order to make the search block workable :
``
exim.lcdd.search.vendor.javascript:
  - '//code.angularjs.org/1.2.16/angular.js'
  - '//code.angularjs.org/1.2.25/angular-sanitize.js'
  - '/bundles/dwllcddsearch/vendor/elastic.js/dist/elastic.min.js'

copy to exim.theme.vendor_front.js:

``

``

see lcdd_elastic_request

``
