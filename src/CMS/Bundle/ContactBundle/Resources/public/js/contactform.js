/**
 * Created by DCA on 12/01/2017.
 */
var type_tag = "";
$(document).ready(function() {
    $('.marqueurs').hide();
    $('#tag').on('change', function() {

        if ($(this).val() != "") {
            type_tag = $(this).val();
            $('.marqueurs').hide();
            $('#'+type_tag).show();
        }
    });

    $('.marqueurs .btn').on('click', function() {
        console.log($(this).val());
        generateTag(type_tag);
    });
});

var generateTag = function(type_tag) {
    var tag = "["+type_tag+" ";
    if ($('#'+type_tag+'_name').length) {
        tag += $('#' + type_tag + '_name').val();
    }
    if ($('#'+type_tag+'_required').length) {
        tag += ($('#' + type_tag + '_required').prop('checked') == true) ? "*" : "";
    }
    if ($('#'+type_tag+'_value').length) {
        var value = $('#' + type_tag + '_value').val();
        tag += (value != "") ? " value:"+value : "";
    }
    if ($('#'+type_tag+'_id').length) {
        var id_tag = $('#' + type_tag + '_id').val();
        tag += (id_tag != "") ? " id:" + id_tag : "";
    }
    if ($('#'+type_tag+'_class').length) {
        var class_tag = $('#' + type_tag + '_class').val();
        tag += (class_tag != "") ? " class:" + class_tag : "";
    }
    if ($('#'+type_tag+'_size').length) {
        var size = $('#' + type_tag + '_size').val();
        tag += (size != "") ? " size:" + size : "";
    }
    if ($('#'+type_tag+'_maxlength').length) {
        var maxlength = $('#' + type_tag + '_maxlength').val();
        tag += (maxlength != "") ? " maxlength:" + maxlength : "";
    }

    console.log($('#'+type_tag+'_placeholder').length);
    if ($('#'+type_tag+'_placeholder').length) {
        var placeholder = $('#' + type_tag + '_placeholder').val();
        tag += (placeholder != "") ? " placeholder:" + placeholder.replace(" ", "_") : "";
    }
    tag += "]";
    $('#'+type_tag+'_tag').val(tag);
    return tag;
}