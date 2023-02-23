$(document).ready(function () {
    let $table = $('.js-lm-table');
    $table.find('.js-deactivated-campaign').on('click', function (e) {
        e.preventDefault();

        if (confirm("Êtes-vous sur de vouloir désactiver cette campagne ?") === true) {
            $(this).find('.fa-deactivated')
                .removeClass('fa-check')
                .addClass('fa-spinner')
                .addClass('fa-spin')
            ;

            let deactivatedUrl = $(this).data('url');
            let $row = $(this).closest('tr');
            $.ajax({
                url: deactivatedUrl,
                method: 'POST',
                success: function () {
                    $(this).find('.fa-deactivated')
                        .addClass('fa-hexagon-check')
                    ;
                }
            });
        }
    });
});
