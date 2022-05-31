<!-- Simplesearch plugin -->
<script>
    jQuery(document).ready(function ($) {
        ls.autocomplete.add(
            $('#search-header-form input.input-text, #SS_SearchField, form.search > input.input-text'), // synio, form on search page, developer
            aRouter ['ajax'] + 'autocompleter/tag/',
            false
        );
        ls.autocomplete.add($('div.block.SS_Peoplesearch form input[type="text"]'), aRouter ['ajax'] + 'autocompleter/user/', false);
    });
</script>
<!-- /Simplesearch plugin -->
