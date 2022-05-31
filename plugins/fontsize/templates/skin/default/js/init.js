/*
  Fontsize plugin
  (P) PSNet, 2008 - 2013
  http://psnet.lookformp3.net/
  http://livestreet.ru/profile/PSNet/
  http://livestreetcms.com/profile/PSNet/
  http://livestreetguide.com/developer/PSNet/
*/

jQuery(document).ready(function ($) {

    $('.Fontsize-Controls').bind('click.fontsize', function () {
        Step = $(this).attr('data-fontsize-action') == 'up' ? Fontsize_Step : -Fontsize_Step;
        $(this).closest('.topic-content').css('font-size', parseInt($(this).closest('.topic-content').css('font-size')) + Step);
    });

});
