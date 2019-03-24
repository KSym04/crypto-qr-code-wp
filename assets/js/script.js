jQuery(document).ready(function(){
    jQuery('.cqcw-block__button').on('click',function(e){
        e.preventDefault();
        // reset all active state.
        var cqcwActiveBlockDOM = jQuery(document);
        cqcwActiveBlockDOM.find('.cqcw-block.active').toggleClass('active');
        cqcwActiveBlockDOM.find('.cqcw-block__button.active').toggleClass('active');
        cqcwActiveBlockDOM.find('.cqcw-block__dialog.active').toggleClass('active');

        // initialize clicked on element.
        var cqcwDialogID = jQuery(this).attr('href');
        jQuery(this).toggleClass('active');
        jQuery(this).parent('.cqcw-block').toggleClass('active');
        jQuery(cqcwDialogID).toggleClass('active');
    });
});