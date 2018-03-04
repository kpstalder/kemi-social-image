jQuery( document ).ready( function( $ ) {
  if(jQuery('#create-new-csv').length > 0 ){
    jQuery('#image-preview').on('click', function(e){
      console.log('click');
    });
    jQuery('#create-new-csv').on('click', function(e){
      e.preventDefault();
      var data = {
        'action': 'convert_csv',
        'csv': jQuery(this).attr('csv')
      };
      jQuery.ajax({
        type: "POST",
        dataType: 'html',
        data: data,
        url: ajaxurl,
        beforeSend: function( xhr ) {
          jQuery('#ajax-loader').show();
        },
        success: function (response) {
          jQuery('#ajax-loader').hide();
          jQuery('#csv-status').prepend(response);
        }
      });
    });
  }
});
