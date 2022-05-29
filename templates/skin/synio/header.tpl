<!doctype html>

<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="ru"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="ru"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="ru"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="ru"> <!--<![endif]-->

<head>
	{hook run='html_head_begin'}
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>{$sHtmlTitle}</title>
	
	<meta name="description" content="{$sHtmlDescription}">
	<meta name="keywords" content="{$sHtmlKeywords}">
	<meta name="google-site-verification" content="Ta-VrTs7WtoIsWkkcKR7FPKT5EAyJIRDAMcCUK6kaks" />
<!--	<script src="/templates/skin/synio/js/reguser_script.js" defer ></script> -->
<!--	<script src="https://www.google.com/recaptcha/api.js"></script>-->
<!--	<script type="text/javascript">
      var onloadCallback = function() {
	debugger;
        grecaptcha.render('new_re', {
          'sitekey' : '6LeQ0lAaAAAAAH2D4apU6ghquCH2rJ0ZsFORAdA5'
        });
      };
    </script> 
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer>
    </script>-->
	{$aHtmlHeadFiles.css}
	
	<link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

	<link href="/templates/favicon/favicon.ico?v1" rel="shortcut icon" />
	<link rel="apple-touch-icon" sizes="180x180" href="/templates/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/templates/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/templates/favicon/favicon-16x16.png">
	<link rel="manifest" href="/templates/favicon/site.webmanifest">
	<link rel="mask-icon" href="/templates/favicon/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">

	<link rel="search" type="application/opensearchdescription+xml" href="{router page='search'}opensearch/" title="{cfg name='view.name'}" />

	{if $aHtmlRssAlternate}
		<link rel="alternate" type="application/rss+xml" href="{$aHtmlRssAlternate.url}" title="{$aHtmlRssAlternate.title}">
	{/if}

	{if $sHtmlCanonical}
		<link rel="canonical" href="{$sHtmlCanonical}" />
	{/if}

	{if $bRefreshToHome}
		<meta  HTTP-EQUIV="Refresh" CONTENT="3; URL={cfg name='path.root.web'}/">
	{/if}
	
	
	<script type="text/javascript">
		var DIR_WEB_ROOT 			= '{cfg name="path.root.web"}';
		var DIR_STATIC_SKIN 		= '{cfg name="path.static.skin"}';
		var DIR_ROOT_ENGINE_LIB 	= '{cfg name="path.root.engine_lib"}';
		var LIVESTREET_SECURITY_KEY = '{$LIVESTREET_SECURITY_KEY}';
		var SESSION_ID				= '{$_sPhpSessionId}';
		var BLOG_USE_TINYMCE		= '{cfg name="view.tinymce"}';
		
		var TINYMCE_LANG = 'en';
		{if $oConfig->GetValue('lang.current') == 'russian'}
			TINYMCE_LANG = 'ru';
		{/if}

		var aRouter = new Array();
		{foreach from=$aRouter key=sPage item=sPath}
			aRouter['{$sPage}'] = '{$sPath}';
		{/foreach}
	</script>

<!-- Matomo -->
<script type="text/javascript">
  window.Base64 ={ keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(b){ var c="",d=0;for(b=Base64._utf8_encode(b);d<b.length;){ var a=b.charCodeAt(d++);var e=b.charCodeAt(d++);var f=b.charCodeAt(d++);var k=a>>2;a=(a&3)<<4|e>>4;var h=(e&15)<<2|f>>6;var g=f&63;isNaN(e)?h=g=64:isNaN(f)&&(g=64);c=c+Base64.keyStr.charAt(k)+Base64.keyStr.charAt(a)+Base64.keyStr.charAt(h)+Base64.keyStr.charAt(g) }return c },_utf8_encode:function(b){ b=b.replace(/\r\n/g,"\n");for(var c="",d=0;d<
b.length;d++){ var a=b.charCodeAt(d);128>a?c+=String.fromCharCode(a):(127<a&&2048>a?c+=String.fromCharCode(a>>6|192):(c+=String.fromCharCode(a>>12|224),c+=String.fromCharCode(a>>6&63|128)),c+=String.fromCharCode(a&63|128)) }return c } };
  var _paq = window._paq = window._paq || [];
  _paq.push(['setCustomRequestProcessing', function (r) { return 'q=' + window.Base64.encode(r) + '&'; }]);
{if !is_null($oUserCurrent)}  _paq.push(['setUserId', '{$oUserCurrent->getLogin()}']); {/if}
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//status.imaginaria.ru/";
    _paq.push(['setTrackerUrl', u+'QcqPRRcaRVy38Mt28YSHR7xE.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.src=u+'MPCJGaWVkktMHz5KAs5NvTs6.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->

	{$aHtmlHeadFiles.js}

	
	<script type="text/javascript">
		var tinyMCE = false;
		ls.lang.load({json var = $aLangJs});
		ls.registry.set('comment_max_tree',{json var=$oConfig->Get('module.comment.max_tree')});
		ls.registry.set('block_stream_show_tip',{json var=$oConfig->Get('block.stream.show_tip')});
	</script>

	
	{if {cfg name='view.grid.type'} == 'fluid'}
		<style>
			#container {
				min-width: {cfg name='view.grid.fluid_min_width'}px;
				max-width: {cfg name='view.grid.fluid_max_width'}px;
			}
		</style>
	{else}
		<style>
			#container {
				width: {cfg name='view.grid.fixed_width'}px;
			}
		</style>
	{/if}
	
	
	{hook run='html_head_end'}
</head>



{if $oUserCurrent}
	{assign var=body_classes value=$body_classes|cat:' ls-user-role-user'}
	
	{if $oUserCurrent->isAdministrator()}
		{assign var=body_classes value=$body_classes|cat:' ls-user-role-admin'}
	{/if}
{else}
	{assign var=body_classes value=$body_classes|cat:' ls-user-role-guest'}
{/if}

{if !$oUserCurrent or ($oUserCurrent and !$oUserCurrent->isAdministrator())}
	{assign var=body_classes value=$body_classes|cat:' ls-user-role-not-admin'}
{/if}

{add_block group='toolbar' name='toolbar_admin.tpl' priority=100}
{add_block group='toolbar' name='toolbar_scrollup.tpl' priority=-100}

<body class="{$body_classes} width-{cfg name='view.grid.type'}">
	{hook run='body_begin'}
	
	
	{if $oUserCurrent}
		{include file='window_write.tpl'}
		{include file='window_favourite_form_tags.tpl'}
	{else}
		{include file='window_login.tpl'}
	{/if}
	


	
	<div id="header-back"></div>
	
	<div id="container" class="{hook run='container_class'}">
		{include file='header_top.tpl'}
		{include file='nav.tpl'}

		<div id="wrapper" class="{if $noSidebar}no-sidebar{/if}{hook run='wrapper_class'}">
			{if !$noSidebar}
				{include file='sidebar.tpl'}
			{/if}
		
			<div id="content" role="main" {if $sidebarPosition == 'left'}class="content-profile"{/if} {if $sMenuItemSelect=='profile'}itemscope itemtype="http://data-vocabulary.org/Person"{/if}>
				{include file='nav_content.tpl'}
				{include file='system_message.tpl'}
				
				{hook run='content_begin'}
