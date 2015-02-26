(function ($) {
    var $resultContainer = $('.search-results');
    var activeTab = 'pageant';

    var getPageantData = function () {
        var obj = {
            stages: [],
            ages: []
        };

        $('fieldset.stages').find('input[type=checkbox]').each(function () {
            if ($(this).is(':checked')) {
                obj.stages.push(this.name);
            }
        });

        $('fieldset.ages').find('input[type=checkbox]').each(function () {
            if ($(this).is(':checked')) {
                obj.ages.push(this.name);
            }
        });

        return 'stages=' + obj.stages.toString() + '&ages=' + obj.ages.toString();
    };

    var getProfileData = function () {
        var obj = {
            roles: [],
            areas: []
        };

        $('fieldset.roles').find('input[type=checkbox]').each(function () {
            if ($(this).is(':checked')) {
                obj.roles.push(this.name);
            }
        });

        $('fieldset.expertise').find('input[type=checkbox]').each(function () {
            if ($(this).is(':checked')) {
                obj.areas.push(this.name);
            }
        });

        return 'roles=' + obj.roles.toString() + '&areas=' + obj.areas.toString();
    };

    var getCoachData = function () {
        var areas = [];

        $('fieldset.expertise').find('input[type=checkbox]').each(function () {
            if ($(this).is(':checked')) {
                areas.push(this.name);
            }
        });

        return areas.toString();
    };

    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        activeTab = $(e.target).text().toLowerCase();
    });

    $('#pageant-search').on('submit', function (evt) {
        evt.preventDefault();

        $('#searchCollapse').collapse('hide');
        $('#resultsCollapse').collapse('show');

        $resultContainer.html('<h4>Finding your ' + activeTab + '...</h4>');

        var data;

        switch (activeTab) {
            case 'pageant':
                data = getPageantData();
                break;
            case 'profile':
                data = getProfileData();
                break;
            case 'coach':
                data = getCoachData();
                break;
            case 'director':
                data = getDirectorData();
                break;
        }

        $.post(
            this.action,
            {
                'action': 'tppdb_' + activeTab + '_search',
                'data': data
            },
            function (res) {
                $resultContainer.html(res);
            }
        )
    })
})(jQuery);