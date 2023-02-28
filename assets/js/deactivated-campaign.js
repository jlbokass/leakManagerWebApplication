$(document).ready(function () {
    let $table = $('.js-lm-table');
    $table.find('.js-campaign-status').on('click', function (e) {
        e.preventDefault();

        let deactivatedUrl = $(this).data('campaign-status');
        let $row = $(this).closest('tr');
        $.ajax({
            url: deactivatedUrl,
            method: 'POST',
            success: function () {
                $(this).find('.fa-deactivated')
                    .addClass('fas fa-times')
                ;
            }
        });

        if (confirm("Êtes-vous sur de vouloir désactiver cette campagne ?") === true) {
            $(this).find('.fa-deactivated')
                .removeClass('fa-check')
                .addClass('fa-spinner')
                .addClass('fa-spin')
            ;
        }
    });
});





