(function ($) {
    var $resultContainer = $('.search-results');
    $('#pageant-search').on('submit', function (evt) {
        evt.preventDefault();

        $('#searchCollapse').collapse('hide');
        $('#resultsCollapse').collapse('show');

        $resultContainer.html('<h4>Finding your pageant...</h4>');

        var data = {
            stages: [],
            ages: []
        };
        $('fieldset.stages').find('input[type=checkbox]').each(function () {
            if ($(this).is(':checked')) {
                data.stages.push(this.name)
            }
        });

        $('fieldset.ages').find('input[type=checkbox]').each(function () {
            if ($(this).is(':checked')) {
                data.ages.push(this.name);
            }
        });

        $.post(
            this.action,
            {
                'action': 'tppdb_pageant_search',
                'data': 'stages=' + data.stages.toString() + '&ages=' + data.ages.toString()
            },
            function (res) {
                $resultContainer.html(res);
            }
        )
    })
})(jQuery);