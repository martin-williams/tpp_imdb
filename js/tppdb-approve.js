(function ($) {
    var jsonToUrlParam = function (obj) {
        return Object.keys(obj).map(function (key) {
            return encodeURIComponent(key) + '=' + encodeURIComponent(obj[key]);
        }).join('&');
    };

    var submitRequest = function (data, callback) {
        if (!data.status) {
            return;
        }

        $.post(
            '/wp-admin/admin-ajax.php',
            {
                action: 'tppdb_submit_request',
                data: jsonToUrlParam(data)
            },
            callback
        )
    };

    $('.approve-link, .deny-link').on('click', function (e) {
        e.preventDefault();
        var $row = $(this).parents('tr');
        var $rowBtns = $row.find('button');
        var data = $row.data();
        $rowBtns.prop('disabled', true);

        if ($(this).hasClass('approve-link')) {
            data.status = 'approved';
        } else if ($(this).hasClass('deny-link')) {
            data.status = 'denied';
        }

        submitRequest(data, function (res) {
            var $tdText = $row.find('td:first-child');
            $tdText.html($tdText.text() + ' - <b>'+data.status.toUpperCase()+'</b>');
        });
    });
})(jQuery);