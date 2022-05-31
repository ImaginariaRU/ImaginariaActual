var ls = ls || {};

ls.complaint = (function () {
    this.sendComplaint = function () {
        var form = $('#form_complaint');
        var url = aRouter['complaint'] + 'ajax_complaint/';
        form.find('button').attr('disabled', true);
        ls.ajaxSubmit(url, form, function (result) {
            $('#form_complaint').find('button').attr('disabled', false);
            if (result.bStateError) {
                ls.msg.error(null, result.sMsg);
            } else {
                $("#window_complaint").jqmHide();
                $('#complaint-button').hide();
                ls.msg.notice(null, result.sMsg);
            }
        });
        return false;
    };

    return this;
}).call(ls.complaint || {}, jQuery);

jQuery(document).ready(function ($) {
    $('#window_complaint').clone().appendTo('body');
    $('#complaint-button').on('click', function () {
        $('#form_complaint_topic_id').val(parseInt($(this).data('topic-id')));
        $("#window_complaint").jqm().jqmShow();
    });
});