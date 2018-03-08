jQuery(document).ready(function() {

    var $form = jQuery('#js-cfconditions-form');
    var $alert = jQuery('#js-cfconditions-alert');

    // Validate the fields.
    $form.parsley({
        uiEnabled: false
    });

    jQuery('#js-cfconditions-btn-continue').on('click', function(event){
       event.preventDefault();

        var agree = $form.parsley().validate();

        if (agree) {
            window.location = jQuery(this).attr('href');
        } else {
            $alert.show();
        }

    });
});