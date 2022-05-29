			{hook run='content_end'}
		</div> <!-- /content -->


		{if !$noSidebar && $sidebarPosition != 'left'}
			{include file='sidebar.tpl'}
		{/if}
	</div> <!-- /wrapper -->


	<footer id="footer">
		<div class="copyright">
			{hook run='copyright'}
		</div>

		<div class="pixtheme">
			<ul>
		    	<li>Разработан от &mdash; <a href="http://pixetheme.com">PixeTheme.com <img src="{cfg name='path.static.skin'}/images/pixtheme.png" alt="pixtheme.com" /></a></li>
		    	<li class="deniart">Спасибо &mdash; <a href="http://deniart.ru">deniart</a></li>
			</ul>
		</div>

		{hook run='footer_end'}
	</footer>

</div> <!-- /container -->

{include file='toolbar.tpl'}

{hook run='body_end'}

</body>
</html>