<nav id="nav">
	<ul class="nav nav-main">
		<li {if $sMenuHeadItemSelect=='blog'}class="active"{/if}><a href="{cfg name='path.root.web'}">{$aLang.topic_title}</a></li>
		<li {if $sMenuHeadItemSelect=='blogs'}class="active"{/if}><a href="{router page='blogs'}">{$aLang.blogs}</a></li>
		<li {if $sMenuHeadItemSelect=='people'}class="active"{/if}><a href="{router page='people'}">{$aLang.people}</a></li>
		<li {if $sMenuHeadItemSelect=='stream'}class="active"{/if}><a href="{router page='stream'}">{$aLang.stream_menu}</a></li>

		{hook run='main_menu_item'}
	</ul>
	{hook run='main_menu'}
</nav>

<ul class="filter-menu">

		<li {if $sMenuItemSelect=='index'}class="active-top"{/if}>
			<a href="{cfg name='path.root.web'}/">{$aLang.blog_menu_all}</a> {if $iCountTopicsNew>0}<small>+{$iCountTopicsNew}</small>{/if}
		</li>

		<li {if $sMenuItemSelect=='blog'}class="active-top"{/if}>
			<a href="{router page='blog'}">{$aLang.blog_menu_collective}</a> {if $iCountTopicsCollectiveNew>0}<small>+{$iCountTopicsCollectiveNew}</small>{/if}
		</li>

		<li {if $sMenuItemSelect=='log'}class="active-top"{/if}>
			<a href="{router page='personal_blog'}">{$aLang.blog_menu_personal}</a> {if $iCountTopicsPersonalNew>0}<small>+{$iCountTopicsPersonalNew}</small>{/if}
		</li>

		{if $oUserCurrent}
			<li {if $sMenuItemSelect=='feed'}class="active-top"{/if}>
				<a href="{router page='feed'}">{$aLang.userfeed_title}</a>
			</li>
		{/if}

		{hook run='menu_blog'}

</ul>