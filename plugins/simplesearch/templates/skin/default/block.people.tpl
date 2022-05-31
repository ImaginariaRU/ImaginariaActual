<!-- Simplesearch plugin -->
<div class="block SS_Peoplesearch">
    <header class="block-header sep">
        <h3>{$aLang.plugin.simplesearch.People_Search}</h3>
    </header>
    <div class="block-content">
        <form action="{router page='search'}people/" method="GET" class="search">
            <input type="text" value="" name="q" placeholder="{$aLang.plugin.simplesearch.Search_Field_Tip}"
                   class="input-text"/>
            <input type="submit" value="" title="{$aLang.search_submit}" class="input-submit icon icon-search"/>
            <label><input type="checkbox" value="1" checked="checked"
                          name="strict"/>{$aLang.plugin.simplesearch.People_Search_Strict_Search}</label>
        </form>

        <small>
            {$aLang.plugin.simplesearch.People_Search_Tip}
        </small>
    </div>
</div>
<!-- /Simplesearch plugin -->
