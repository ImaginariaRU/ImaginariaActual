{assign 'sTopicDateAdd' ''}
{if $_aRequest}
    {if isset($_aRequest.topic_date_add)}{assign 'sTopicDateAdd' $_aRequest.topic_date_add}{/if}
{/if}

<!-- Delayed post plugin -->
<input type="text" id="delayedpost_topic_date_add" name="delayedpost_topic_date_add" value="{$sTopicDateAdd}"
       readonly="readonly"/>
<link rel="stylesheet" type="text/css"
      href="{$oConfig->GetValue("path.root.web")}/plugins/delayedpost/templates/skin/default/css/style.css"/>
<!-- /Delayed post plugin -->
