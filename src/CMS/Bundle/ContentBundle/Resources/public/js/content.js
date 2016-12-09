/**
 * Created by DCA on 08/12/2016.
 */
$(document).ready(function() {

    $('.count_chars').on('keyup', function() {
        $(this).parent('.form-group').children('.help').html($(this).val().length + " caractères");
    });

    $('.count_chars').each(function() {
        $(this).parent('.form-group').children('.help').html($(this).val().length + " caractères");
    });

    $('table.sortable tbody').sortable({
        connectWith: ".sortable-handle",
        update: function (event, ui) {
            var ids = [];
            console.log('update');
            $('table.sortable tbody tr.sortable-handle').each(function() {
                ids.push($(this).data('id'));
            });
            $('form#form-order input[name="order"]').val(ids);
            $('form#form-order').submit();
        }
    });
});