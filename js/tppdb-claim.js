(function ($) {
    var $modal = $('<div>')
        .addClass('modal fade')
        .attr({
            id: 'requestModal',
            role: 'dialog'
        })
        .append($('<div>').addClass('modal-dialog')
            .append($('<div>').addClass('modal-content'))
    ).appendTo('body');

    $('<div>')
        .addClass('modal-header')
        .append($('<a>')
            .addClass('close')
            .attr({
                'data-dismiss': 'modal',
                'href': '#'
            })
            .append($('<i>').addClass('fa fa-times')))
        .append($('<h4>')
            .addClass('modal-title')
            .text('Request to claim this profile')
    ).appendTo($modal.find('.modal-content'));

    $('<div>')
        .addClass('modal-body')
        .append($('<p>')
            .text('Claim this profile and begin editing your information on The Pageant Planet. Add pictures, interesting facts, biographical information, and more. Claiming a profile requires an approval from our team.')
        )
        .append($('<form>')
            .attr('id','requestForm')
            .append('<input type="hidden" name="postId" />')
            .append('<input type="hidden" name="userId" />')
            .append('<input type="hidden" name="postType" />')
        )
        .appendTo($modal.find('.modal-content'));

    $('<div>')
        .addClass('modal-footer')
        .append($('<button>')
            .addClass('btn btn-default')
            .attr({
                'type:': 'button',
                'id': 'cancelClaimBtn',
                'data-dismiss': 'modal'
            })
            .html('<i class="fa fa-times"></i> Cancel')
        )
        .append($('<button>')
            .addClass('btn btn-primary')
            .attr({
                'type': 'button',
                'id': 'confirmRequest'
            })
            .html('<i class="fa fa-user"></i> Request to Claim')
    ).appendTo($modal.find('.modal-content'));

    $modal.off().on('show.bs.modal', function (e) {
        var form = document.getElementById('requestForm');
        form.elements['postId'].value = e.relatedTarget.dataset.postId;
        form.elements['userId'].value = e.relatedTarget.dataset.userId;
        form.elements['postType'].value = e.relatedTarget.dataset.postType;

        $('#confirmRequest').on('click', function () {
            this.disabled = true;
            submitRequest(function () {
                e.relatedTarget.parentNode.innerHTML = 'Pending Review';
            });
        });
    });

    var submitRequest = function (cb) {
        $(this).prop('disabled', true).find('.fa').removeClass('fa-user-plus').addClass('fa-spinner fa-spin');

        var $form = $('#requestForm');
        $.post(
            '/wp-admin/admin-ajax.php',
            {
                action: 'tppdb_claim_request',
                data: $form.serialize()
            },
            function () {
                $modal.find('.modal-body')
                    .html('<p>Thank you for your message. We will review your request and respond shortly.</p>');
                $('#cancelClaimBtn').text('Close Window');
                $('#submitBtn').find('.fa').removeClass('fa-spin fa-spinner').addClass('fa-user-plus');
                if (cb && typeof cb == 'function') cb();
            }
        );
    }
})(jQuery);