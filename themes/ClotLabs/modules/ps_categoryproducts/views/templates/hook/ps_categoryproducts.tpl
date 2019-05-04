<section class="product-accessories clearfix">
    <div class="title-fill">
        <h3><span class="fill"></span><span class="tap"></span>
          {if $products|@count == 1}
            {l s='%s other product in the same category:' sprintf=[$products|@count] d='Shop.Theme.Catalog'}
          {else}
            {l s='%s other products in the same category:' sprintf=[$products|@count] d='Shop.Theme.Catalog'}
          {/if}
          <span class="fill"></span>
        </h3>
    </div>
      <div class="products feature-carousel owl-carousel owl-theme">
        {foreach from=$products item="product_accessory"}
          {block name='product_miniature'}
            {include file='catalog/_partials/miniatures/product.tpl' product=$product_accessory addcart=false}
          {/block}
        {/foreach}
      </div>
</section>