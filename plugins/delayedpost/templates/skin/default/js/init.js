/*
  Delayed post plugin
  (P) Rafrica.net Studio, 2010 - 2012
  http://we.rafrica.net/
*/

var ls = ls || {};

ls.delayedpost = (function ($) {

    this.MainFormDateFieldID = '#delayedpost_topic_date_add',

        // ---

        this.InitChanges = function () {
            $('select.DelayedPostSelectItem', $('#SidebarDateSelect')).map(function () {
                $(this).bind('change', function () {
                    ls.delayedpost.UpdateSidebarFieldFromSelectFields();
                    ls.delayedpost.SetCurrentDateActivity();
                });
            });
        }

    // ---

    this.GetSelectValue = function (SelectID) {
        return $(SelectID) [0].options [$(SelectID) [0].selectedIndex].value;
    }

    // ---

    this.UpdateSidebarFieldFromSelectFields = function () {
        NewDateValue = this.GetSelectValue('#DP_YearDateSelect') + "-" +
            this.GetSelectValue('#DP_MonthDateSelect') + "-" +
            this.GetSelectValue('#DP_DayDateSelect') + " " +
            this.GetSelectValue('#DP_TimeHourSelect') + ":" +
            this.GetSelectValue('#DP_TimeMinuteSelect') + ":00";
        $(this.MainFormDateFieldID).val(NewDateValue);
    }

    // ---

    this.SetCurrentDateActivity = function () {
        if ($.trim($(this.MainFormDateFieldID).val()) == '') {
            $('#SidebarDateSelect').css('opacity', '0.5');
            $('#DP_Second_Title').html(DelayedPost_Msg_TimeNotSelected);
        } else {
            $('#SidebarDateSelect').css('opacity', '1');
            $('#DP_Second_Title').html('');
        }
    }

    // ---

    this.ClearDateSelect = function (aThisDiv) {
        $(this.MainFormDateFieldID).val('');
        $('#DP_SidebarForm') [0].reset();
        this.SetCurrentDateActivity();
    }

    return this;

}).call(ls.delayedpost || {}, jQuery);
