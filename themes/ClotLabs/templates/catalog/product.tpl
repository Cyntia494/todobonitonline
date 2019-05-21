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
{extends file=$layout}

{block name='head_seo' prepend}
    <link rel="canonical" href="{$product.canonical_url}">
{/block}

{block name='head' append}
    <meta property="og:type" content="product">
    <meta property="og:url" content="{$urls.current_url}">
    <meta property="og:title" content="{$page.meta.title}">
    <meta property="og:site_name" content="{$shop.name}">
    <meta property="og:description" content="{$page.meta.description}">
    <meta property="og:image" content="{$product.cover.large.url}">
    <meta property="product:pretax_price:amount" content="{$product.price_tax_exc}">
    <meta property="product:pretax_price:currency" content="{$currency.iso_code}">
    <meta property="product:price:amount" content="{$product.price_amount}">
    <meta property="product:price:currency" content="{$currency.iso_code}">
    {if isset($product.weight) && ($product.weight != 0)}
        <meta property="product:weight:value" content="{$product.weight}">
        <meta property="product:weight:units" content="{$product.weight_unit}">
    {/if}
{/block}

{block name='content'}
    <section id="main" itemscope itemtype="https://schema.org/Product">
        <meta itemprop="url" content="{$product.url}">
        <div class="gallery">
            <a href="{$product_brand_url}" class="product-brand">
                <img src="{$manufacturer_image_url}" class="img manufacturer-logo" alt="{$product_manufacturer->name}">
            </a>
            {block name='page_content_container'}
                <section class="page-content" id="content">
                    {block name='page_content'}
                        {block name='product_cover_tumbnails'}
                            {include file='catalog/_partials/product-cover-thumbnails.tpl'}
                        {/block}
                        <div class="scroll-box-arrows">
                            <i class="material-icons left">&#xE314;</i>
                            <i class="material-icons right">&#xE315;</i>
                        </div>

                    {/block}
                </section>
            {/block}
        </div>
        <div class="details">
            <div class="product-title">
                {block name='page_header_container'}
                    {block name='page_header'}
                        <h1 class="h1" itemprop="name">{block name='page_title'}{$product.name}{/block}</h1>
                    {/block}
                {/block}

                {block name='product_review'}
                    {hook h='DisplayShowProductsStars' product=$product}
                {/block}

                <div class="title-details">
                    <div class="reference">
                        {block name='product_reference'}
                            {if isset($product.reference_to_display)}
                              <div class="product-reference">
                                <span class="sku">{l s='Sku' d='Shop.Theme.Catalog'}</span>:
                                <span itemprop="sku">{$product.reference_to_display}</span>
                              </div>
                            {/if}
                        {/block}
                    </div>
                    <div class="available">
                        {block name='product_availability'}
                            <span>
                              {if $product.show_availability && $product.availability_message}
                                {$product.availability_message}
                              {/if}
                            </span>
                        {/block}
                    </div>
                </div>

            </div>
            
            {block name='product_prices'}
                {include file='catalog/_partials/product-prices.tpl'}
            {/block}

            <div class="product-information">
                {block name='product_description_short'}
                    {if $product.description_short}
                      <div id="product-description-short-{$product.id}" class="product-description-short" itemprop="description">
                          {$product.description_short|strip_tags}
                      </div>
                    {/if}
                {/block}

                {if $product.is_customizable && count($product.customizations.fields)}
                    {block name='product_customization'}
                        {include file="catalog/_partials/product-customization.tpl" customizations=$product.customizations}
                    {/block}
                {/if}

                <div class="product-actions">
                    {block name='product_buy'}
                        <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
                            <input type="hidden" name="token" value="{$static_token}">
                            <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
                            <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id">

                            {block name='product_variants'}
                                {include file='catalog/_partials/product-variants.tpl'}
                            {/block}

                            {block name='product_pack'}
                            {if $packItems}
                              <section class="product-pack">
                                <h3 class="h4">{l s='This pack contains' d='Shop.Theme.Catalog'}</h3>
                                {foreach from=$packItems item="product_pack"}
                                    {block name='product_miniature'}
                                        {include file='catalog/_partials/miniatures/pack-product.tpl' product=$product_pack}
                                    {/block}
                                {/foreach}
                              </section>
                            {/if}
                            {/block}

                            {block name='product_discounts'}
                                {include file='catalog/_partials/product-discounts.tpl'}
                            {/block}

                            {block name='product_add_to_cart'}
                                {include file='catalog/_partials/product-add-to-cart.tpl'}
                            {/block}

                            {block name='product_additional_info'}
                                {include file='catalog/_partials/product-additional-info.tpl'}
                            {/block}

                            {block name='product_refresh'}
                            <input class="product-refresh ps-hidden-by-js" name="refresh" type="submit" value="{l s='Refresh' d='Shop.Theme.Actions'}">
                            {/block}
                        </form>
                    {/block}
                </div>
            </div>
            {block name='hook_display_reassurance'}
              {hook h='displayReassurance'}
            {/block}
            <div class="more-content">

                {block name='product_tabs'}
                {assign var="desc" value={$product.description|strip_tags:false|@trim}}
                  
                  <div class="tabs {if ({$desc|count_characters}==0) && !$product.attachments && !$product.extraContent} hide {/if}">
                    
                    <ul class="nav nav-tabs" role="tablist">

                      {if {$desc|count_characters}>0}
                        <li class="nav-item">
                           <a
                             class="products-detail-tab-link  nav-link {if ({$desc|count_characters})>0} active{/if}"
                             data-toggle="tab"
                             href="#description"
                             role="tab"
                             aria-controls="description"
                             {if $product.description} aria-selected="true"{/if}>{l s='Description' d='Shop.Theme.Catalog'}</a>
                        </li>
                      {/if}
                      <li class="nav-item">
                          <a class="products-detail-tab-link nav-link{if ({$desc|count_characters})<= 0} active{/if}"  href="#product-details">
                            {l s='Product Details' d='Shop.Theme.Catalog'}
                          </a>
                      </li>
                      {if $product.attachments}
                        <li class="nav-item">
                          <a
                            class="products-detail-tab-link  nav-link {if ({$desc|count_characters})==0 && $product.attachments} active{/if}"
                            data-toggle="tab"
                            href="#attachments"
                            role="tab"
                            aria-controls="attachments">{l s='Attachments' d='Shop.Theme.Catalog'}</a>
                        </li>
                      {/if}
                      
                      {foreach from=$product.extraContent item=extra key=extraKey}
                        <li class="nav-item">
                          <a
                            class="products-detail-tab-link  nav-link {if ({$desc|count_characters})==0 && !$product.attachments && $extraKey == 0}active{/if}"
                            data-toggle="tab"
                            href="#extra-{$extraKey}"
                            role="tab"
                            aria-controls="extra-{$extraKey}">{$extra.title}</a>
                        </li>
                      {/foreach}
                    </ul>
                    <div class="tab-content" id="tab-content">
                      
                      {if {$desc|count_characters}>0}
                        <a class="products-detail-tab-link nav-link{if $product.description} active{/if}"  href="#description">
                            {l s='Description' d='Shop.Theme.Catalog'}
                            <span class="pull-xs-right">
                                <i class="material-icons add">&#xE313;</i>
                                <i class="material-icons remove">&#xE316;</i>
                            </span>
                        </a>
                        <div class="tab-pane fade in{if $product.description} active{/if}" id="description" role="tabpanel">
                          {block name='product_description'}
                            <div class="product-description">{$product.description nofilter}</div>
                          {/block}
                        </div>
                      {/if}
                     
                      {block name='product_details'}
                          <a class="products-detail-tab-link nav-link {if ({$desc|count_characters})<= 0} active{/if}"  href="#product-details">
                              {l s='Product Details' d='Shop.Theme.Catalog'}
                              <span class="pull-xs-right">
                                  <i class="material-icons add">&#xE313;</i>
                                  <i class="material-icons remove">&#xE316;</i>
                              </span>
                          </a>
                          {include file='catalog/_partials/product-details.tpl'}
                      {/block}

                      {block name='product_attachments'}
                       {if $product.attachments}
                        <a class="products-detail-tab-link nav-link" href="#attachments">
                          {l s='Attachments' d='Shop.Theme.Catalog'}
                          <span class="pull-xs-right">
                              <i class="material-icons add">&#xE313;</i>
                              <i class="material-icons remove">&#xE316;</i>
                          </span>
                        </a>
                        <div class="tab-pane fade in{if $product.attachments && ({$desc|count_characters})==0} active{/if}" id="attachments" role="tabpanel">
                           <section class="product-attachments">
                             <p class="h5 text-uppercase">{l s='Download' d='Shop.Theme.Actions'}</p>
                             {foreach from=$product.attachments item=attachment}
                               <div class="attachment">
                                 <h4><a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">{$attachment.name}</a></h4>
                                 <p>{$attachment.description}</p
                                 <a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
                                   {l s='Download' d='Shop.Theme.Actions'} ({$attachment.file_size_formatted})
                                 </a>
                               </div>
                             {/foreach}
                           </section>
                         </div>
                       {/if}
                      {/block}

                      {foreach from=$product.extraContent item=extra key=extraKey}
                        <a class="products-detail-tab-link nav-link" href="#extra-{$extraKey}">
                          {$extra.title}
                          <span class="pull-xs-right">
                              <i class="material-icons add">&#xE313;</i>
                              <i class="material-icons remove">&#xE316;</i>
                          </span>
                        </a>
                        <div class="tab-pane fade in {$extra.attr.class} 
                        {if ({$desc|count_characters})==0 && !$product.attachments && $extraKey == 0}active{/if}" id="extra-{$extraKey}" role="tabpanel" {foreach $extra.attr as $key => $val} {$key}="{$val}"{/foreach}>
                          {$extra.content nofilter}
                        </div>
                      {/foreach}
                    </div>  
                  </div>
                {/block}
            </div>
        </div>
    </div>
    
    {block name='product_accessories'}
      {if $accessories}
        <section class="product-accessories clearfix">
            <div class="title-fill">
                <h3><span class="fill"></span><span class="tap"></span>{l s='Recommend' d='Shop.Theme.Catalog'}<span class="fill"></span></h3>
            </div>
              <div class="products feature-carousel owl-carousel owl-theme">
                {foreach from=$accessories item="product_accessory"}
                  {block name='product_miniature'}
                    {include file='catalog/_partials/miniatures/product.tpl' product=$product_accessory addcart=false}
                  {/block}
                {/foreach}
              </div>
        </section>
      {/if}
    {/block}

    {block name='product_footer'}
      {hook h='displayFooterProduct' product=$product category=$category}
    {/block}

    {block name='product_images_modal'}
      {include file='catalog/_partials/product-images-modal.tpl'}
    {/block}

    {block name='page_footer_container'}
      <footer class="page-footer">
        {block name='page_footer'}
          <!-- Footer content -->
        {/block}
      </footer>
    {/block}
  </section>

{/block}
