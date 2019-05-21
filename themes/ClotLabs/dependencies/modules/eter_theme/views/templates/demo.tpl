<div id="etertheme-demo">
	<div class="left">
		<img class="logo img-responsive" src="{$shop.logo} " alt="eterlabs">
	</div>
	<div class="center">
		<p>{l s='This is a demo site and all orders have not been executed' mod='eter_theme'}</p>
	</div>
	<div class="right">
		{if $demo_url}
			<a class="buy-it" href="{$demo_url}">{l s='Know more' mod='eter_theme'}</a>
		{/if}
	</div>
</div>
{literal}
	<script type="text/javascript">
		document.body.classList.add('demo-active');
	</script>
{/literal}