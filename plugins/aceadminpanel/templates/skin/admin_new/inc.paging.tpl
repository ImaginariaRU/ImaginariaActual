{if $aPaging AND $aPaging.iCountPage>1}
    <div class="pagination pagination-centered">
        <ul>
            {if $aPaging.iCurrentPage>1}
                <li><a href="{$aPaging.sBaseUrl}/{$aPaging.sGetParams}"
                       title="{$aLang.paging_first}">&laquo;</a></li>
            {else}
                <li class="active"><span>&laquo;</span></li>
            {/if}

            {foreach from=$aPaging.aPagesLeft item=iPage}
                <li><a href="{$aPaging.sBaseUrl}/page{$iPage}/{$aPaging.sGetParams}">{$iPage}</a></li>
            {/foreach}

            <li class="active"><span>{$aPaging.iCurrentPage}</span></li>

            {foreach from=$aPaging.aPagesRight item=iPage}
                <li><a href="{$aPaging.sBaseUrl}/page{$iPage}/{$aPaging.sGetParams}">{$iPage}</a></li>
            {/foreach}


            {if $aPaging.iCurrentPage<$aPaging.iCountPage}
                <li><a href="{$aPaging.sBaseUrl}/page{$aPaging.iCountPage}/{$aPaging.sGetParams}"
                       title="{$aLang.paging_last}">&raquo;</a></li>
            {else}
                <li class="active"><span>&raquo;</span></li>
            {/if}
        </ul>
    </div>
{/if}