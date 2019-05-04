{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{extends file='catalog/listing/product-list.tpl'}

{block name='cms_title'}
	<div class="block-category-container">
	    <div class="block-category card-block">
	        <!--<img src="{$urls.img_cat_url}{$category.id}.jpg" class="category-images" alt="{$category.name}">-->
	       	<div class="description">
				<div class="top-p-title">
					<div class="container description-wrapper">
						{if $page.page_name == 'category' || $page.page_name == 'prices-drop' || $page.page_name == 'new-products' || $page.page_name == 'best-sales'}
							<h1 class="strong">{$category.name}</h1>
							<div class="category-description">{$category.description|strip_tags}</div>
						{else}
							<span class="strong">{l s='Search results for:' d='ShopThemeCatalog'}</span> 
							{$search_string}
						{/if}
					</div>
				</div>
	       </div>
	    </div>
	</div>
{/block}
