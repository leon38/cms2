/**
 * Created by DCA on 30/11/2016.
 */

$('.sidebar-wrapper ul.nav li').each(function() {
    if ($(this).hasClass('active')) {
        $(this).parents('li').addClass('active');
        $(this).parents('div.collapse').addClass('in');
    }
});