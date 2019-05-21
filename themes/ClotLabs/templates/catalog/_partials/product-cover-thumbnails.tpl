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
<div class="images-container">
    {block name='product_cover'}
        <div id="product-gallery">
            <div class="product-gallery eagle-gallery  {if isset($isQuickView) && isQuickView}img350{else}img500{/if} in as1">
                <div class="owl-carousel mini-slider">
                    {if count($product.images) > 0}
                        {foreach from=$product.images item=image}
                            <img src="{$image.bySize.home_default.url}" 
                                data-medium-img="{$image.bySize.large_default.url}" 
                                onerror="this.src='{$urls.img_prod_url}{$language.iso_code}-default-home_default.jpg'"
                                data-big-img="{$image.bySize.large_default.url}" 
                                data-title="{$image.legend}" 
                                alt="{$image.legend}">
                        {/foreach}
                    {else}
                        <img src="{$urls.img_prod_url}{$language.iso_code}-default-home_default.jpg" 
                                data-medium-img="{$urls.img_prod_url}{$language.iso_code}-default-home_default.jpg" 
                                onerror="this.src='{$urls.img_prod_url}{$language.iso_code}-default-home_default.jpg'"
                                data-big-img="{$urls.img_prod_url}{$language.iso_code}-default-home_default.jpg" 
                                data-title="{$urls.img_prod_url}{$language.iso_code}-default-home_default.jpg" 
                                alt="{$product.name}">
                    {/if}
                </div>
            </div>
        </div>
    {/block}
</div>

{hook h='displayAfterProductThumbs'}
