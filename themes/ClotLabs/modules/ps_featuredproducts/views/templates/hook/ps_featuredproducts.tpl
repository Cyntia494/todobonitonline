<section class="top-products featured-products clearfix">
	<div class="title-fill">
		<a href="{$allProductsLink}">
        	<h3><span class="fill"></span><span class="tap"></span>{l s='Featured Products' mod='ps_featuredproducts'}<span class="fill"></span></h3>
		</a>
    </div>
	<div class="products feature-carousel owl-carousel owl-theme">
		{foreach from=$products item="product"}
			{include file="catalog/_partials/miniatures/product.tpl" product=$product addcart=false}
		{/foreach}
	</div>
</section>
