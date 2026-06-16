/* Gift Wrap toggle — instant cart fee update via AJAX. */
jQuery(function ($) {

    $(document).on('change', '#gw_gift_wrap', function () {

        var enabled  = $(this).is(':checked') ? 'yes' : 'no';
        var $totals  = $('.cart_totals');

        // Visual lock while we recalculate (blockUI ships with the WC cart page).
        if ($.fn.block) {
            $totals.block({
                message: null,
                overlayCSS: { background: '#fff', opacity: 0.6 }
            });
        }

        $.ajax({
            type: 'POST',
            url: gwData.ajax_url,
            data: {
                action: 'gw_toggle',
                gift_wrap: enabled,
                nonce: gwData.nonce
            },
            success: function (res) {
                if (res && res.success && res.data && res.data.html) {
                    // Swap in the freshly rendered totals (with/without the fee row).
                    $('.cart_totals').replaceWith(res.data.html);
                    $(document.body).trigger('updated_cart_totals');
                } else {
                    $totals.unblock();
                }
            },
            error: function () {
                $totals.unblock();
            }
        });
    });
});
