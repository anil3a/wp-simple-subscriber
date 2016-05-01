jQuery(document).ready( function($){

  $( '.wpss-subscribe-ajaxrequest' ).on("click", function(e) {
    e.preventDefault();
    $(".form-request-message").html(" ");

    var wpss_firstname = $(".wpss-subscriber-form #wpss__first_name").val();
    var wpss_lastname = $(".wpss-subscriber-form #wpss__last_name").val();
    var wpss_emailaddress = $(".wpss-subscriber-form #wpss__emailaddress").val();

    var expressionEmail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    
    if( expressionEmail.test(wpss_emailaddress)  ) {

      $.ajax({
        url: ajax_object.ajaxurl,
        type: 'POST',
        data:{
          action: 'wpss_subscribe_action',
          wpss_firstname: wpss_firstname,
          wpss_lastname: wpss_lastname,
          wpss_emailaddress: wpss_emailaddress,
          ajaxrequest: true
        },
        success: function( data ){
          if( data ){
              $(".form-request-message").html('<span class="'+data.type+'">'+data.response+'</span>');
          }
        }
      });

    } else {

      $(".form-request-message").html('<span class="error">Invalid email address</span>');
    }

  });


});