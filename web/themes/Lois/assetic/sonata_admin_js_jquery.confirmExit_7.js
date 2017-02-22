/*!
* jQuery confirmExit plugin
* https://github.com/dunglas/jquery.confirmExit
*
* Copyright 2012 Kévin Dunglas <dunglas@gmail.com>
* Released under the MIT license
* http://www.opensource.org/licenses/mit-license.php
*/
(function(a){a.fn.confirmExit=function(){a(this).attr("data-original",a(this).serialize());a(this).on("submit",function(){a(this).removeAttr("data-original")});return a(this)};a(window).on("beforeunload",function(d){var f=d||window.event,c=window.SONATA_TRANSLATIONS.CONFIRM_EXIT,b=false;a("form[data-original]").each(function(){if(a(this).attr("data-original")!==a(this).serialize()){b=true;return}});if(b){if(f){f.returnValue=c}return c}})})(jQuery);