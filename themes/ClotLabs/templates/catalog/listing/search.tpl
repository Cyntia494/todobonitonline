{*
 * This file allows you to customize your search page.
 * You can safely remove it if you want it to appear exactly like all other product listing pages
 *}
{extends file='catalog/listing/product-list.tpl'}
{block name='cms_title'}
	<div class="block-category-container">
	    <div class="block-category card-block">
	       	<div class="description">
				<div class="top-p-title">
					<div class="container description-wrapper">
						<h1 class="strong">{l s='Search results for:' d='ShopThemeCatalog'} {$search_string}</h1>
					</div>
				</div>
	       </div>
	    </div>
	</div>
{/block}
