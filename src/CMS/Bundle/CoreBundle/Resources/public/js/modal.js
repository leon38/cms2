/**
 * Created by DCA on 23/08/2016.
 */
var modalback = $('<div class="modal-back fade in"></div>');
var modal = $('.modal');

$('[data-toggle="modal"]').on('click', function() {
    $('body').append(modalback);
    var $this = $(this);
    var url = $this.data('url');
    modal = $('#' + $this.data('target'));
    if (typeof url != "undefined" && url != "") {
        modal.find('.modal-body').html('<img src="'+url+'" class="img-responsive" />');
    }
    modal.addClass('in');
});

modal.on('click', function() {
    $(this).removeClass('in');
    modalback.remove();
});

