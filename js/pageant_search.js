(function ($) {
    $('#pageant-search').on('submit', function (evt) {
        evt.preventDefault();

        var data = [];
        $(this).serializeArray().forEach(function (term) {
            data.push(term.name);
        });

        $.post(
            this.action,
            {
                'action': 'tppdb_pageant_search',
                'data': data.toString()
            },
            function (res) {
                $('.search-results').html(res);
            }
        )
    })
})(jQuery);