/**
 * Created by DCA on 19/08/2016.
 */
$(document).ready(function() {
    $('.modal.modal-gallery').on('shown.bs.modal', function () {
       $.ajax({
           url: Routing.generate('admin_media_gallery'),
           success: function(msg) {
               $('.modal.modal-gallery .modal-body').html(msg);
               var images = $('#tc_bundle_contentbundle_content_fieldValuesTemp_galerie-photo_gallery').val();
               images = images.split(",");
               for(var i = 0 ; i < images.length ; i++) {
                   $('.thumbnail[data-id='+images[i]+']').toggleClass('active');
               }
           }
       })
    });

    var list = $('#tc_bundle_contentbundle_content_fieldValuesTemp_galerie-photo_gallery').val();
    var images = list.split(",");
    $.ajax({
        url: Routing.generate('admin_media_gallery_images'),
        data: "images="+list,
        type: "post",
        success: function(msg) {
            $('.gallery').html(msg).append('<div class="clearfix"></div>');
        }
    })

});

var selectMedia = function (elem) {
    elem = $(elem);
    var id = elem.data('id');
    elem.toggleClass('active');
    var list = $('#tc_bundle_contentbundle_content_fieldValuesTemp_galerie-photo_gallery').val();
    if (elem.hasClass('active')) {
        if (list != "")
            list += ","+id;
        else
            list += ""+id;
    } else {
        list = removeValue(list, id, ",")
    }
    $('#tc_bundle_contentbundle_content_fieldValuesTemp_galerie-photo_gallery').val(list);
}

var saveGallery = function() {
    $('.gallery').html('');
    var images = $('#tc_bundle_contentbundle_content_fieldValuesTemp_galerie-photo_gallery').val();
    images = images.split(",");
    for(var i = 0 ; i < images.length ; i++) {
        var url = $('.thumbnail[data-id='+images[i]+']').data('url');
        $('.gallery').append('<div class="col-md-2 thumb"><div class="thumbnail"><img class="img-responsive" src="'+url+'" /></div></div>');
    }
    $('.gallery').append('<div class="clearfix"></div>');
    $('.modal.modal-gallery').modal('hide');
}

var removeValue = function(list, value, separator) {
    separator = separator || ",";
    var values = list.split(separator);
    for(var i = 0 ; i < values.length ; i++) {
        if(values[i] == value) {
            values.splice(i, 1);
            return values.join(separator);
        }
    }
    return list;
}