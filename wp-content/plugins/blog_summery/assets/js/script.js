jQuery(document).ready(function ($) {

    $('#post_summery_btn').on('click', function () {
        $('.summery_loader').css('display','flex');
        let postId = $(this).data('id');

        $.ajax({
            url: blogsummery.ajax_url,
            type: 'POST',
            data: {
                action: 'get_post_summary',
                nonce: blogsummery.nonce,
                post_id: postId
            },
            beforeSend: function () {
                $('#post_summery_result').show().html('Loading...');
            },
            success: function (response) {
                $('.summery_loader').css('display','none');
                $('#post_summery_result').html(response.data.summary);
            },
            error: function () {
                $('.summery_loader').css('display','none');
                $('#post_summery_result').html('Something went wrong.');
            }
        });

    });

});