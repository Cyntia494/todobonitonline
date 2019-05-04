{if count($products) > 0}
	<div class="products-list">
		<h3>{l s='Top products' mod='eter_blog'}</h3>
		{foreach from=$products item="product"}
			<article class="itemproduct js-product-miniature"
				data-id-product="{$product.id_product}"
				data-id-product-attribute="{$product.id_product_attribute}" itemscope
				itemtype="http://schema.org/Product">
				<div class="product-container">
					{block name='product_thumbnail'}
					<a href="{$product.url}" class="thumbnail product-thumbnail">
						{if $product.cover}
						<img
							src="{$product.cover.bySize.home_default.url}"
							alt="{$product.cover.legend}"
							data-full-size-image-url="{$product.cover.large.url}">
						{else}
						<img
							src="{$urls.img_prod_url}{$language.iso_code}-default-home_default.jpg"
							alt="{$product.name}"
							data-full-size-image-url="{$product.cover.large.url}">
						{/if}
					</a>
					{/block}
					<div class="product-description">
						<div class="name-price">
							{block name='product_name'}
							<h4 class="h3 product-title" itemprop="name">
								<a href="{$product.canonical_url}">{$product.name|truncate:40:'...'}</a>
							</h4>
							{/block}
							{block name='product_price_and_shipping'}
							{if $product.show_price}
							<div class="product-price-and-shipping">
								<span itemprop="price" class="price {if $product.has_discount ==
									false}has-discount{/if}">{$product.price}</span>
							</div>
							{/if}
							{/block}
						</div>
						<div class="content-quick">
							{block name='quick_view'}
							<a class="quick-view listing-add-tocart" href="#"
								data-link-action="quickview">
								{l s='Quick View' d='Shop.Theme.Catalog'}
							</a>
							{/block}
						</div>
					</div>
				</div>
			</article>
		{/foreach}
	</div>
{/if}