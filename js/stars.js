(function ($) {
    var $stars = $('.comment-form-rating i');
    var ratingInput = document.getElementById('rating');

    var currentRating = 0;

    var fillStar = function (el, fill) {
        fill = fill || false;
        $(el).removeClass('fa-star-o').addClass('fa-star');
        if (fill) {
            $stars.each(function (i) {
                if (i < $(el).index()) {
                    fillStar(this);
                }
            })
        }
    };

    var emptyStar = function (el, fill) {
        fill = fill || false;
        if (!$(el).hasClass('active')) {
            $(el).removeClass('fa-star').addClass('fa-star-o');
        }
        if (fill) {
            $stars.each(function (i) {
                if (i < $(el).index()) {
                    emptyStar(this);
                }
            })
        }
    };

    var setStar = function () {
        if ($(this).index() != currentRating) {
            currentRating = $(this).index();
            $stars.each(function (i, elem) {
                if (i <= currentRating) {
                    $(elem).addClass('active');
                    fillStar(elem);
                } else {
                    $(elem).removeClass('active');
                    emptyStar(elem);
                }
            });
        } else {
            currentRating = 0;
            $stars.removeClass('fa-star active').addClass('fa-star-o');
        }
        ratingInput.value = currentRating;
    };

    $stars.on({
        'mouseover': function () {fillStar(this, true)},
        'mouseout': function () {emptyStar(this, true)},
        'click': setStar
    }).css('cursor', 'pointer');
})(jQuery);