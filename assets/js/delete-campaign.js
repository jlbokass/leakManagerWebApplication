$(document).ready(function () {
    let $table = $('.js-lm-table');
    $table.find('.js-delete-campaign').on('click', function (e) {
        e.preventDefault();

        if (confirm("Êtes-vous sur de vouloir supprimer cette campagne ?") === true) {
            $(this).find('.fa')
                .removeClass('fa-trash-can')
                .addClass('fa-spinner')
                .addClass('fa-spin')
            ;

            let deleteUrl = $(this).data('url');
            let $row = $(this).closest('tr');
            $.ajax({
                url: deleteUrl,
                method: 'DELETE',
                success: function () {
                    $row.fadeOut();
                }
            });
        }
    });
});
