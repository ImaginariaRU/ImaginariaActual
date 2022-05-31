/**
 * Spoiler :: jQuery
 */
$(document).ready(function () {
    $('.spoiler-title').click(function () {
        $(this).toggleClass('open');
        $(this).parent().children('div.spoiler-body').toggle('normal');
        return false;
    });
});