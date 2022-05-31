<li class="complaint-layer">
    <button type="button" id="complaint-button" class="button button-primary" data-topic-id="{$oTopic->getId()}">
        {$aLang.plugin.complaint.button.complaint}
    </button>
</li>
{* Модальное окно после загрузки переместится в конец страницы *}
<div class="modal modal-complaint-window" id="window_complaint">
    <header class="modal-header">
        <h3>{$aLang.plugin.complaint.dialog_title}</h3>
        <a href="#" class="close jqmClose"></a>
    </header>
    <form method="POST" action="" enctype="multipart/form-data" id="form_complaint" onsubmit="return false;"
          class="modal-content">
        <input type="hidden" id="form_complaint_topic_id" name="topic_id" value="0">
        <p>
            <label for="complaint_text">{$aLang.plugin.complaint.complaint_text}</label>
            <textarea name="complaint_text" id="complaint_text" class="input-width-full"></textarea>
        </p>
        <button type="button" class="button button-primary" onclick="ls.complaint.sendComplaint();">
            {$aLang.plugin.complaint.button.submit}
        </button>
        <button type="submit" class="button jqmClose">
            {$aLang.plugin.complaint.button.cancel}
        </button>
    </form>
</div>