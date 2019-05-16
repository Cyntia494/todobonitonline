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
{block name='product_miniature_item'}
  <article class="itemproduct js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
        <div class="product-container">
            {block name='product_flags'}
                <ul class="new-product-flags">
                    {foreach from=$product.flags item=flag}
                        {if $flag.type == "new"}
                            <li class="{$flag.type}"><span>{$flag.label}</span></li>
                        {/if}
                    {/foreach}
                    {if $product.discount_type === 'percentage'}
                        <li class="discount"><span>%</span></li>
                    {/if}
                </ul>  
            {/block}
            {block name='product_thumbnail'}
                <a href="{$product.url}" class="thumbnail product-thumbnail">
                    {if $product.cover}
                        <img
                          src = "{$product.cover.bySize.home_default.url}"
                          alt = "{$product.cover.legend}"
                          onerror="this.src='{$urls.img_prod_url}{$language.iso_code}-default-home_default.jpg'"
                          data-full-size-image-url = "{$product.cover.large.url}"
                        >
                        {else}
                        <img
                          src = "{$urls.img_prod_url}{$language.iso_code}-default-home_default.jpg"
                          alt = "{$product.name}"
                          onerror="this.src='{$urls.img_prod_url}{$language.iso_code}-default-home_default.jpg'"
                          data-full-size-image-url = "{$product.cover.large.url}"
                        >
                    {/if}
                </a>
            {/block}
            <div class="product-description">
                {block name='quick_view'}
                    <a class="quick-view" href="#" data-link-action="quickview">
                        {l s='Quick View' d='Shop.Theme.Catalog'}
                    </a>
                {/block}
                {block name='product_name'}
                    <h4 class="h3 product-title" itemprop="name">
                        <a href="{$product.url}">{$product.name}</a>
                    </h4>
                {/block}
                {block name='product_reviews'}
                  {hook h='displayProductListReviews' product=$product}
                {/block}
                {block name='product_price_and_shipping'}
                    {if $product.show_price}
                        <div class="product-price-and-shipping">
                            {if $product.has_discount}
                                {hook h='displayProductPriceBlock' product=$product type="old_price"}
                                <span class="regular-price">{$product.regular_price}</span>
                            {/if}
                            {hook h='displayProductPriceBlock' product=$product type="before_price"}
                            <span itemprop="price" class="price">{$product.price}</span>
                            {hook h='displayProductPriceBlock' product=$product type='unit_price'}
                            {hook h='displayProductPriceBlock' product=$product type='weight'}
                           
                        </div>
                    {/if}
                {/block}
                {block name='product_review'}
                    {hook h='DisplayShowProductsStars' product=$product}
                {/block}
                {block name='product_add_cart'}
                    {if (isset($addcart) && $addcart) || !isset($addcart)}
                      {hook h='DisplayListingAddToCart' product=$product}
                    {/if}
                {/block}
            </div>
        </div>
  </article>
{/block}
