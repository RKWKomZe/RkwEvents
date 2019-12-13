// function for clearing data of additional users in reservation
jQuery('a.tx-rkwevents-delete').on( "click", function(event) {

    if (jQuery(this).data('person')) {

        event.preventDefault();

        jQuery('input:text.tx-rkwevents-person-' + $(this).data('person')).each(function (index, element) {
            jQuery(element).val('');
        });

        jQuery('select.tx-rkwevents-person-' + $(this).data('person') + ' option').each(function (index, element) {
            jQuery(element).removeAttr('selected');
            if (jcf)
                jcf.refresh(jQuery(element).parent());
        });
    }
});

//  function to prevent double click
jQuery('a.prevent-double-click').on('click', function(event) {

    var $anchor = $(event.target.closest('a'));

    if ($anchor.hasClass('disabled')) {
        event.preventDefault();
    }

    $anchor.addClass('disabled');

});