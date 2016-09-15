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
});


$(window).load(function(){

    // Grid Layout
    if($('.grid-layout').length>0){
        var masonry_layout = $('.grid-layout');
        masonry_layout.masonry({
            columnWidth: '.post-item',
            itemSelector: '.post-item',
            transitionDuration: 0
        }).parents('.grid-container').addClass("open");

        $(window).resize(function(){
            setTimeout(function(){
                masonry_layout.masonry('reloadItems').masonry();
            },100);
        });
    }

})