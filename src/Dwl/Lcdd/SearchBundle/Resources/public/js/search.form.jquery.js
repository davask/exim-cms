var postForm = function($form, callback) {
  /*
   * Get all form values
   */
  var values = {};
  $.each( $form.serializeArray(), function(i, field) {
    values[field.name] = field.value;
  });

  /*
   * Throw the form values to the server!
   */
  $.ajax({
    type        : $form.attr( 'method' ),
    url         : $form.attr( 'action' ),
    data        : values,
    success     : function(data) {
      callback( data );
    }
  });
};
jQuery(document).ready(function() {
    var forms = [
        '[ name="' + lcdd.form.name + '"]'
    ];

    $( forms.join(',') ).submit( function( e ){
        e.preventDefault();

        postForm( $(this), function( response ){
            var c = 'text-warning';
            if (response.success === true) {
                c = 'text-success';
            }
            jQuery('.result', e.currentTarget).noty({
                text: response.message,
                timeout: 2000,
                type: response.success ? 'success':'warning'
            });
        });

        return false;
    });
});
