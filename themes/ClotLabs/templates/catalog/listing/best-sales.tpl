{*
 * This file allows you to customize your best-sales page.
 * You can safely remove it if you want it to appear exactly like all other product listing pages
 *}
{extends file='catalog/listing/product-list.tpl'}
{block name='cms_title'}
	<div class="block-category-container">
	    <div class="block-category card-block">
	       	<div class="description">
				<div class="top-p-title">
					<div class="container description-wrapper">
						<h1 class="strong">{l s="Best Sales" d='ShopThemeCatalog'}</h1>
						<div class="category-description">{l s="The Best Sales products" d='ShopThemeCatalog'}</div>
					</div>
				</div>
	       </div>
	    </div>
	</div>
{/block}
