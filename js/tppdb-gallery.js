(function ($) {
    $('.tppdb-gallery').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1]
        },
        callbacks: {
            markupParse: function(template, values, item) {
                $(template).find('.report-btn').remove();
                $(template).prepend('<button class="report-btn btn btn-danger" title="Report Image" data-src="' + item.src + '" data-toggle="modal" data-target="#reportImageModal"><i class="fa fa-ban"</button>');
                $('#reportImageModal').off().on('show.bs.modal', setImageSrc);
            }
        }
    });

    var modalHtml = '<div class="modal fade" id="reportImageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
    modalHtml += '<div class="modal-dialog">';
    modalHtml += '<div class="modal-content">';
    modalHtml += '<div class="modal-header">';
    modalHtml += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    modalHtml += '<h4 class="modal-title">Report Image</h4>';
    modalHtml += '</div>';
    modalHtml += '<form id="reportForm">';
    modalHtml += '<div class="modal-body" style="overflow: auto;">';
    modalHtml += '<input type="hidden" id="imageSrc" name="imageSrc" value="" />';
    modalHtml += '<div class="col-md-4">';
    modalHtml += '<div class="checkbox"><label><input type="checkbox" name="poorQuality" /> Poor quality</label></div>';
    modalHtml += '<div class="checkbox"><label><input type="checkbox" name="nudity" /> Nudity</label></div>';
    modalHtml += '<div class="checkbox"><label><input type="checkbox" name="offensive" /> Offensive</label></div>';
    modalHtml += '</div>';
    modalHtml += '<div class="col-md-8">';
    modalHtml += '<div class="form-group"><label for="message">Message</label><textarea class="form-control" id="message" name="message"></textarea></div>';
    modalHtml += '</div>';
    modalHtml += '</div>';
    modalHtml += '<div class="modal-footer">';
    modalHtml += '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
    modalHtml += '<button type="submit" class="btn btn-danger">Report Image</button>';
    modalHtml += '</div>';
    modalHtml += '</form>';
    modalHtml += '</div><!-- /.modal-content -->';
    modalHtml += '</div><!-- /.modal-dialog -->';
    modalHtml += '</div><!-- /.modal -->';

    $('body').prepend(modalHtml);

    var setImageSrc = function (e) {
        $.magnificPopup.close();
        document.getElementById('imageSrc').value = e.relatedTarget.dataset.src;
        $('#reportForm').on('submit', submitReport);
    };

    var submitReport = function (e) {
        e.preventDefault();
        var $form = $(this);
        $.post(
            '/wp-admin/admin-ajax.php',
            {
                action: 'tppdb_image_report',
                data: $form.serialize()
            },
            function (resData) {
            $form.find('.modal-body').html('<p>' + resData + '</p>');
            setTimeout(function () {
                $('#reportImageModal').modal('hide');
            }, 3000);
        });
    };
})(jQuery);