{*
* WWWWWWW@#WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
* WWWWWWWWW#*=WWWWWWWWWWWWWWWWWWWW=@WWWWWWWWWWWWWWWWWWW
* WWWWWWWWWWW#++=@WWWWW#**#WWWWWWWWWWWWWWWWWWWWWWWWWWWW
* WWWWWWWWWWWWW#+++=@WWW@#WWWWWWWWWWWWWWWWW**@WWWWWWWWW
* WWWWWWWWWWWWWWW#++++*@WWWWWWWWWWWWW@WWWWWWWWWWWWWWWWW
* WWWWWWWWWWWWWWWWW#+++++*@WWWWW@@WWWWWW@WWWWWWWWWWWWWW
* WWWWWWWWWWWWWW@WWWW#++++++*#WWWWW@@WWWWWW@WWWWWWWWWWW
* WWWWWWWW***@WWW@@WWWW#+++++++*#WWWWW@@WWWWWW@WWWWWWWW
* WWWWWWWW@=#WWWW@@@@WWWW=+++++++++=WWWWW@@@WWWWWWWWWWW
* WWWWWWWWWWWWWW@@@WWWWWWWW=++++++++++=WWWWW@@@WWWWWWWW
* WWWWWWWWWWWWW@@WWWWWW@WWW@+++++++++++++=@WWWWWWWWWWWW
* WWWWWWWWWWWW@WWWWWW@WWWW=+++++++++++++++++*@WWWWWWWWW
* WWWWWWWWWWWWWWWWW@WWWW=++++++++++++++++++++++#WWWWWWW
* WWWWWWWWWWWWWWW@WWWW=+++++++++++++++++++++++*@WWWWWWW
* WWWWWWW=@WWWW@WWWW=+++++++++++++++++++++++*#WWWWWWWWW
* WWWWWWWWWWW@WWWW=+++++++++++++++++++++++*#WWWWWWWWWWW
* WWWWWWWWWWWWWW=+++++++++++++++++++++++*#WWWWWWWWWWWWW
* WWWWWWWWWWWW#+++++++++++++++++++++++*#WWW@@WWWWWWWWWW
* WWWWWWWWWW#+++++++++++++++++++++++*#WWW@@WWWWWWWWWWWW
* WWWWWWWW@+++++++++++++++++++++++*#WWW@@WWWWWWWWWWWWWW
* WWWWWWWW@++++++++++++++++++++++#WWW@@WWWW@WWWWWWWWWWW
* WWWWWWWWWW#*++++++++++++++++*#WWWW@WWWW@@WWWWWWWWWWWW
* WWWWWWWWWWWWW#+++++++++++++*WWWW@WWWW@@@WWWWWWWWWWWWW
* WWWWWWWWWW@@WWWW#++++++++++*WWWWWWW@@@@WWW***WWWWWWWW
* WWWWWWWWWWWWW@@WWWW=*+++++***@WWWWWWW@@WWW@=@WWWWWWWW
* WWWWWWWWWW@WWWWW@@WWWW=*******#WWWWWWWW@WWWWWWWWWWWWW
* WWWWWWWWWWWWW@WWWWW@@WWWW=*****=WWWWWWWWWWWWWWWWWWWWW
* WWWWWWWWWWWWWWWW@WWWWW@@WWWW=****@WWWWWWWWWWWWWWWWWWW
* WWWWWWWWW#@WWWWWWWW@WWWWWWW@@W@=**#WWWWWWWWWWWWWWWWWW
* WWWWWWWWWWWWWWWWWWWWWWWWWWW=#WWWW@==WWWWWWWWWWWWWWWWW
*
* @package Eter_Blogs
* @author ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*}
{extends file='layouts/layout-full-width.tpl'}
{block name='head' append}
	<meta property="og:type" content="article">
	<meta property="og:title" content="{$blog.name}">
	<meta property="og:site_name" content="{$shop.name}">
	<meta property="og:url" content="{$blog.url}">
	<meta property="og:description" content="{$blog.details}">
	<meta property="og:image" content="{$blog.banner}">
	<meta property="fb:app_id" content="{$app_id}" />
	{/block}
	{block name='head_seo_title'}
	{$shop.name} - {$blog.name}
	{/block}
	{block name='head_seo_description'}
	{$blog.details}
	{/block}
	{block name='breadcrumb'}{/block}
	{block name='content'}
	<div class="blog-header" style="background-image: url({$blog.banner})">
		<div class="overlay">
			<h1>{$blog.name}</h1>
		</div>
	</div>
	<div class="container">
		<div class="blog-breadcrumbs hidden-sm-down">
			{block name='breadcrumb-body'}
				{include file='_partials/breadcrumb.tpl'}
			{/block}
		</div>
		<div class="blog-contentainer">
			<div class="blog-content">{$blog.html nofilter}</div>
			<div class="fb-comments" data-href="{$blog.url}" data-width="100%"
				data-numposts="15"></div>
			{literal}
			<div id="fb-root"></div>
			<script>
				  (function(d, s, id) {
				    var js, fjs = d.getElementsByTagName(s)[0];
				    if (d.getElementById(id)) return;
				    js = d.createElement(s); js.id = id;
				    js.src = 'https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v3.2&appId={/literal}{$app_id}{literal}&autoLogAppEvents=1';
				    fjs.parentNode.insertBefore(js, fjs);
				  }(document, 'script', 'facebook-jssdk'));
				</script>
			{/literal}
		</div>
		<div class="blog-related">
			{$relateds nofilter}
			{$products nofilter}
		</div>
	</div>
{/block}