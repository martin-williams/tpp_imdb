(function ($) {
    var $expanderBtn = $('#expanderBtn');
    var $bioWrapper = $('#bio-wrapper');

    var collapse = function () {
        $bioWrapper.removeClass('opened').addClass('closed');
        $expanderBtn.text('Read More');
    };

    var expand = function () {
        $bioWrapper.removeClass('closed').addClass('opened');
        $expanderBtn.text('Read Less');
    };

    if ($bioWrapper.height() <= 297) {
        $expanderBtn.hide();
        expand();
    } else {
        collapse();
    }

    $expanderBtn.on('click', function () {
        if ($bioWrapper.hasClass('closed')) {
            expand();
        } else if ($bioWrapper.hasClass('opened')) {
            collapse();
        }
    });
})(jQuery);