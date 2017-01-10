/**
 * Created by DCA on 24/06/2016.
 */


$('a[data-toggle=swal-delete]').on('click', function () {
    var id = $(this).data('id');
    swal({
        title: 'Suppression du média',
        showCancelButton: true,
        confirmButtonText: 'Valider',
        width: 600,
        showLoaderOnConfirm: true,
        allowOutsideClick: false
    }).then(function () {
        var path = Routing.generate('admin_media_delete', {id: id});
        $('#modal-delete').modal('hide');
        $('#thumb-' + id).fadeOut();
        $.ajax({
            url: path,
            type: "GET",
            dataType: "json"
        })

    })
});

$('a[data-toggle="swal-thumb"]').on('click', function() {
    swal({
        title: $(this).data('alt'),
        imageUrl: $(this).data('url'),
        imageClass: 'img-responsive',
        width: 800
    })
});


$('#modal-thumb').on('show.bs.modal', function (event) {
    var link = $(event.relatedTarget);
    var modal = $(this);
    $.ajax({
        url: Routing.generate('admin_media_edit', {id: link.data("id")}),
        type: "GET",
        beforeSend: function () {

        },
        success: function (msg) {
            modal.find('.modal-content .modal-title').html(link.data('alt'));
            modal.find('.modal-content .modal-body .row').html(msg);
        }
    })
});

$('#modal-thumb').on('hide.bs.modal', function (event) {
    var modal = $(this);
    modal.find('.modal-content .modal-title').html('');
    modal.find('.modal-content .modal-body .row').html('<div class="col-md-12"><div class="cssload-loader"><div class="cssload-inner cssload-one"></div><div class="cssload-inner cssload-two"></div><div class="cssload-inner cssload-three"></div></div></div>');
});


function resizeMedia() {
    $('.row.resized .col-md-6').hide().removeClass('hide').fadeIn();
    var last_response_len = false;
    $.ajax(Routing.generate('admin_media_resize'), {
        xhrFields: {
            onprogress: function (e) {
                var this_response, response = e.currentTarget.response;
                if (last_response_len === false) {
                    this_response = response;
                    last_response_len = response.length;
                }
                else {
                    this_response = response.substring(last_response_len);
                    last_response_len = response.length;
                }
                var value = JSON.parse(this_response);

                $('.row.resized .progress .progress-bar').attr('aria-valuenow', value.percent).css('width', value.percent + '%').html(value.percent + '%');
                $('.row.resized .content span.filename').html(value.filename);
            }
        }
    }).done(function () {
        setTimeout(function (data) {
            $('.row.resized .col-md-6').fadeOut();
        }, 5000)
    })
}

function updateMetas(elm) {
    var elm = $(elm);
    var parente = elm.parent();
    var datas = {}
    $('#media_info input:text, input:hidden').each(function () {
        datas[$(this).attr('name')] = $(this).val();
    });
    $.ajax({
        url: Routing.generate('admin_update_meta'),
        type: 'POST',
        data: datas,
        success: function (msg) {
            parente.parent().prepend('<div class="alert alert-success">Metas updated</div>');
        }
    });
    return false;
}

$('#lazy').on('click', function(event) {
    var page = Math.ceil($('.thumb').length / 6);
    var nb_left = nb_total - ((page+1)*6);
    $('#lazy').fadeOut();
    $.ajax(Routing.generate('admin_media_page', {'page': page}), {
        type: 'GET',
        beforeSend: function() {
            $('.loader').removeClass('hide').fadeIn(200);

        },
        success: function(msg) {
            $('.loader').fadeOut();
            $('.thumbnails').append(msg);
            if (nb_left < 0) {
                $('#lazy').prop('disabled', true).fadeIn();
            } else {
                $('#lazy').fadeIn();
            }
        }
    });
});