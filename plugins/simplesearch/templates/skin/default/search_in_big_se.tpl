{if $oConfig->GetValue("plugin.simplesearch.Show_Links_For_Other_SE") and $aReq.q}
    <!-- Simplesearch plugin -->
    <div class="SearchResultsAreEmpty">
        <h2 class="page-header">{$aLang.plugin.simplesearch.Find_In_Other_SE}
            &laquo;{$aReq.q|escape:'html'}&raquo; {$aLang.plugin.simplesearch.Find_In_Other_SE_In}</h2>

        <a href="http://www.google.ru/search?ie=UTF-8&amp;hl=ru&amp;q={$aReq.q|escape:'html'}&amp;as_sitesearch={$oConfig->GetValue("path.root.web")}"
           target="_blank" rel="external nofollow">Google</a>,
        <a href="http://yandex.ru/yandsearch?text={$aReq.q|escape:'html'}&amp;site={$oConfig->GetValue("path.root.web")}"
           target="_blank" rel="external nofollow">Yandex</a>,
        <a href="http://www.bing.com/search?q={$aReq.q|escape:'html'}+site%3A{$oConfig->GetValue("path.root.web")}"
           target="_blank" rel="external nofollow">Bing</a>.
    </div>
    <!-- /Simplesearch plugin -->
{/if}
