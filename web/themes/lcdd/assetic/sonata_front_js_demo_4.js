$(document).ready(function(){$('form[data-name="sonata-ajax"]').on("submit",function(c){c.preventDefault();var b=$(this);$.ajax({type:b.attr("method"),url:b.attr("action"),data:b.serialize(),success:function(e){var d=b.attr("data-target");if(e){$("#"+d).html(e)}}})});if($(".basket")){var a=false;$(".basket input[type=number]").on("change",function(b){a=true});$(".basket input[type=checkbox]").on("change",function(b){a=true});$(".sonata-basket-nextstep").on("click",function(b){if(a){if(!confirm(basket_update_confirmation_message)){b.preventDefault()}}})}});