<script language="javascript">
    $(document).ready(
        function () {
            $("{$sSelector}").replaceText(/%username%/gi, '<a href="{$oUserCurrent->getUserWebPath()}" class="ls-user">{$oUserCurrent->getLogin()}</a>');
        }
    );
</script>