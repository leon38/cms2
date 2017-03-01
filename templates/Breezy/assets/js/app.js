/**
 * Created by DCA on 15/09/2016.
 */
var swiper = new Swiper('.swiper-container', {
    nextButton: '.swiper-button-next',
    prevButton: '.swiper-button-prev',
    spaceBetween: 30,
    effect: 'fade',
    autoplay: 10000,
    grabCursor: true,
    loop: true,
    pagination: '.swiper-pagination',
    paginationClickable: true
});

$('.goto-top').click(function(){
    $('html, body').animate({scrollTop : 0},800);
    return false;
});

$(window).on("scroll",function(){
    var scrollTop = $(window).scrollTop();
    if(scrollTop > 800) {
        if(!$(".goto-top").hasClass("on")){
            $(".goto-top").addClass("on");
        }
    }
    else if (scrollTop <= 800) {
        if($(".goto-top").hasClass("on")){
            $(".goto-top").removeClass("on");
        }
    }

    var hauteur = $('.post-featured-item').height();
    hauteur = 70*hauteur/100;
    var scrollTopBlur = scrollTop - 295;
    var rapport = 1 / hauteur;
    var opacity = scrollTopBlur * rapport;
    $('.blurred').css({opacity: opacity});
});

var masonry_layout = $('.grid-layout');

$(window).load(function(){

    // Grid Layout
    if($('.grid-layout').length>0){

        masonry_layout.masonry({
            columnWidth: '.post-item',
            itemSelector: '.post-item',
            transitionDuration: 0
        }).parents('.grid-container').addClass("open");
    }

});

$(window).resize(function(){
    setTimeout(function(){
        masonry_layout.masonry('reloadItems');
    },100);
});

var gallery = "";
var nb_slide = 0;
var modal = "";

$(document).ready(function() {

    $('.main-navigation > .nav-menu').slicknav({
        prependTo:'.menu-mobile',
        label:'',
        allowParentLinks: true
    });

    // Search
    $('body').on('click', '.top-search-area a', function ( e ) {
        e.preventDefault();
        var top_search_link = $(this);
        $('body').addClass("search-open");
        setTimeout(function(){
            top_search_link.parents(".main-navigation-wrapper").find(".search-form-area .search").focus();
        },100);
    });

    $('body').on('click', '.search-form-area .close-btn', function ( e ) {
        e.preventDefault();
        $('body').removeClass("search-open");
    });

    $('.modal').find('.close').on('click', function() {
        $(this).parent('.modal').css({display: 'none'});
    });

    $('*[data-toggle="modal"]').on('click', function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        gallery = $(this).attr('rel');
        modal = $('#'+target);
        if (gallery != '') {
            nb_slide = $('a[rel='+gallery+']').length;
            if (modal.find('.arrows').length == 0) {
                modal.find('.modal-inner').append('<div class="arrows"><a href="javascript:prev()" class="arrow back"><i class="fa fa-chevron-circle-left"></i></a><a href="javascript:next()" class="arrow next"><i class="fa fa-chevron-circle-right"></i></a></div>');
            }
        }
        modal.find('.modal-content').attr('src', $(this).data('url'));
        modal.css({display: 'block'});
    });
});

function addLike(comment_id) {

    var comments_ids = readCookie('comment');
    if (comments_ids != null) {
        comments_ids = decodeURI(comments_ids);
    }
    var comments_ids_array = new Array();

    if (comments_ids !== null) {
        comments_ids_array = comments_ids.split(" ");
    }

    var comment_id_str = comment_id.toString();

    if ((comments_ids_array.length > 0 && comments_ids_array.indexOf(comment_id_str) == -1) || comments_ids === null) {
        var linkCount = $('#link-comment-'+comment_id+' > .like');
        var oldLikes = parseInt(linkCount.html());
        linkCount.html('<i class="fa fa-spinner fa-pulse"></i>');
        $.ajax({
            url: '/add/like/'+comment_id,
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                if (data.status == "SUCCESS") {
                    linkCount.html(data.likes);
                    setCookie('comment', comments_ids+comment_id+' ', '31');
                }
            }
        });
    }
}

function addLikeContent(content_id) {

    var contents_ids = readCookie('content');
    if (contents_ids != null) {
        contents_ids = decodeURI(contents_ids);
    }
    var content_ids_array = new Array();

    if (contents_ids !== null) {
        content_ids_array = contents_ids.split(" ");
    }

    var content_id_str = content_id.toString();

    if ((content_ids_array.length > 0 && content_ids_array.indexOf(content_id_str) == -1) || contents_ids === null) {
        var linkCount = $('#link-comment-'+content_id+' > .like');
        var oldLikes = parseInt(linkCount.html());
        linkCount.html('<i class="fa fa-spinner fa-pulse"></i>');
        $.ajax({
            url: '/add/like/content/'+content_id,
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                if (data.status == "SUCCESS") {
                    linkCount.html(data.likes);
                    setCookie('comment', contents_ids+content_id+' ', '31');
                }
            }
        });
    }
}

function setCookie(c_name, value, exdays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString()) + "; path=/";
    document.cookie = c_name + "=" + c_value;
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function next() {
    var src = $('.modal > .modal-inner > img').attr('src');
    var current_elem = $('a[data-url="'+src+'"]');
    var index_link = $('a[rel="'+gallery+'"]').index(current_elem);
    var next_index = 0;
    if (index_link < (nb_slide-1)) {
        next_index = index_link+1;
    }
    var next_sibling = $('a[rel="'+gallery+'"]').eq(next_index);
    modal.find('.modal-content').attr('src', next_sibling.data('url'));
}

function prev() {
    var src = $('.modal > .modal-inner > img').attr('src');
    var current_elem = $('a[data-url="'+src+'"]');
    var index_link = $('a[rel="'+gallery+'"]').index(current_elem);
    var prev_index = index_link -1;
    if (index_link == 0) {
        prev_index = nb_slide-1;
    }
    var prev_sibling = $('a[rel="'+gallery+'"]').eq(prev_index);
    modal.find('.modal-content').attr('src', prev_sibling.data('url'));
}

function loadjsfile(filename, defer){
    var fileref=document.createElement('script');
    fileref.setAttribute("type","text/javascript");
    fileref.setAttribute("src", filename);
    if (defer) {
        fileref.setAttribute("defer", "defer");
    }

    if (typeof fileref!="undefined") {
        document.body.appendChild(fileref);
    }
}