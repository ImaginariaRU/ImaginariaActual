<!DOCTYPE html>
<html lang="ru">
<head>
    <title>{$sHtmlTitle}</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="description" content="{$sHtmlDescription}"/>
    <meta name="keywords" content="{$sHtmlKeywords}"/>
    {if $oConfig->GetValue("plugin.urlredirect.Meta_Robots")}
        <meta name="robots" content="{$oConfig->GetValue("plugin.urlredirect.Meta_Robots")}"/>
    {/if}
    <link rel="stylesheet" type="text/css" href="{$aTemplateWebPathPlugin.urlredirect}css/redirect.css?UPD=1.0"/>
    <link href="{cfg name='path.static.skin'}/images/favicon.ico" rel="shortcut icon"/>
    <link rel="search" type="application/opensearchdescription+xml" href="{router page='search'}opensearch/"
          title="{cfg name='view.name'}"/>
    <script type="text/javascript"
            src="{$oConfig->GetValue("path.root.engine")}/assets/jquery/jquery.js"></script>
    {if $URL and $oConfig->GetValue("plugin.urlredirect.Time_For_Auto_Going")>=0}
        <meta http-equiv="Refresh" content="{$oConfig->GetValue("plugin.urlredirect.Time_For_Auto_Going")}; URL={$URL}">
    {/if}
</head>
<body>
<!-- URL Redirect plugin -->
<div class="URLRedirect_Page">
