<nav id="userbar" class="clearfix">
	<form action="{router page='search'}topics/" class="search">
		<input type="text" placeholder="{$aLang.search}" maxlength="255" name="q" class="input-text">
		<input type="submit" value="" title="{$aLang.search_submit}" class="input-submit icon icon-search">
	</form>


	<ul class="nav social">
		<li class="step"><a href="#"><img src="{cfg name='path.static.skin'}/icons/facebook.png" alt=""/></a></li>
		<li class="step"><a href="#"><img src="{cfg name='path.static.skin'}/icons/vk.png" alt=""/></a></li>
		<li class="step"><a href="#"><img src="{cfg name='path.static.skin'}/icons/twitter.png" alt=""/></a></li>
		<li class="step">{$aLang.social_info}</li>
	</ul>

	{hook run='userbar_nav'}

	<ul class="nav nav-userbar">
		{if $oUserCurrent}
			<li class="nav-userbar-username">
				<a href="{$oUserCurrent->getUserWebPath()}" class="username">
					<img src="{cfg name='path.static.skin'}/images/profile.png" />
				</a>
			</li>
	
			{hook run='userbar_item'}
			<li><a href="{router page='login'}exit/?security_ls_key={$LIVESTREET_SECURITY_KEY}"><img src="{cfg name='path.static.skin'}/images/exit.png" /></a></li>
		{else}
			{hook run='userbar_item'}
			<li><a href="{router page='login'}" class="js-login-form-show"><img src="{cfg name='path.static.skin'}/images/enter.png" alt=""/></a></li>
			<li><a href="{router page='registration'}" id="reg" class="js-registration-form-show"><img src="{cfg name='path.static.skin'}/images/reg.png" alt=""/></a></li>
		{/if}
	</ul>
</nav>


<header id="header" role="banner">
	{hook run='header_banner_begin'}
	{include file='nav.tpl'}
    
	{if $oUserCurrent}
	<div class="post"><a href="{router page='topic'}add/" class="write" id="modal_write_show"><img src="{cfg name='path.static.skin'}/icons/post.png" alt="" />  {$aLang.topic_create}</a></div>
	{else}
	<div class="post">
	<a href="{router page='topic'}add/" class="js-login-form-show"><img src="{cfg name='path.static.skin'}/icons/post.png" alt="" /> {$aLang.topic_create}</a>
	<p class="add-ab">{$aLang.add_info}</p>
	</div>
	{/if}
	<hgroup class="site-info">
		<h2 class="site-description">{cfg name='view.description'}</h2>
		<h1 class="site-name"><a href="{cfg name='path.root.web'}"><img src="{cfg name='path.static.skin'}/images/logo.png" alt="{cfg name='view.description'}"/></a></h1>
	</hgroup>
	{hook run='header_banner_end'}
</header>
<div class="clear"></div>