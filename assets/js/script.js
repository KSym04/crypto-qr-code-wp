jQuery(document).ready(function(){
    // reset all active state.
    function cqcwResetState() {
        var cqcwActiveBlockDOM = jQuery(document);
        cqcwActiveBlockDOM.find('.cqcw-block.active').toggleClass('active');
        cqcwActiveBlockDOM.find('.cqcw-block__button.active').toggleClass('active');
        cqcwActiveBlockDOM.find('.cqcw-block__dialog.active').toggleClass('active');
    }

    // open QR dialog box.
    jQuery('.cqcw-block__button').on('click',function(e){
        e.preventDefault();
        cqcwResetState();

        // initialize clicked on element.
        var cqcwDialogID = jQuery(this).attr('href');
        jQuery(this).toggleClass('active');
        jQuery(this).parent('.cqcw-block').toggleClass('active');
        jQuery(cqcwDialogID).toggleClass('active');
    });

    // close QR dialog box.
    jQuery('.cqcw-block__button-close').on('click',function(e){
        cqcwResetState();
    });

    // close QR dialog when browser resized.
    jQuery(window).resize(function(){
        if( jQuery('.cqcw-block').length > 0 ) {
            cqcwResetState();
        }
    });
});