<div class="edited_comment">{$aLang.plugin.editcomment.msg_last_edited}
    <time datetime="{date_format date=$oComment->getEditDate() format='c'}">{date_format|lower date=$oComment->getEditDate() format="j F Y, H:i"}</time>
</div>