<div class="minicart" data-update="{url entity='module' name='eter_minicart' controller='updatecart'}">
	<div class="cart">
        <i class="fas fa-shopping-bag minicart-icon"></i>
        <span class="cart-products-count">{$cart.products_count}</span>
	</div>
	<div class="blockcart" style="display: none;" data-refresh-url="{$refresh_url}"></div>
	<div class="content">
		<div class="title">
			{l s='My cart' mod='eter_minicart'}
		</div>
		{if $cart.products_count > 0}
			<ul>
		        {foreach from=$cart.products item=product}
		          <li>
		          	<p class="product-name">{$product.name}</p>
					<img src="{$product.cover.medium.url}">
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
	                </div>
					<p class="product-price"><span class="unit-price">{$product.price}</span></p>
		          </li>
		        {/foreach}
	      	</ul>
	    {else}
	    	<div class="emty">
	    		<p><i class="material-icons shopping-cart">shopping_cart</i></p>
	    		<p class="emty-message">Upps!</p>
	    		<p class="emty-message2">No hay nada aun en tu carrito.</p>
	    	</div>
      	{/if}
      	{if $cart.products_count > 0}
	      	<div class="total">
	      		{l s='Total' mod='eter_minicart'} <span class="price"> {$cart.totals.total.value} </span>
	      	</div>
	      	<div class="buttons">
	      		<a href="{$cart_url}" class="gocart">{l s='Edit cart' mod='eter_minicart'}</a>
	      		<a href="{$urls.pages.order}" class="checkout">{l s='Checkout' mod='eter_minicart'}</a>
	      	</div>
      	{/if}
	</div>
</div>