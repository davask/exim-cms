var postForm = function(b, c) {
    var a = {};
    jQuery.each(b.serializeArray(), function(d, e) { a[e.name] = e.value });
    jQuery.ajax({ type: b.attr("method"), url: b.attr("action"), data: a, success: function(d) { c(d) } })
};
jQuery(document).ready(function() {
    var a = ['[ name="{{ questionForm.vars.full_name }}"]'];
    jQuery(a.join(",")).submit(function(b) {
        b.preventDefault();
        postForm($(this), function(c) { console.log(c) });
        return false
    })
});
