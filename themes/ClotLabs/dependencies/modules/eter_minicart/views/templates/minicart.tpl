{*
* WWWWWWW@#WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
* WWWWWWWWW#*=WWWWWWWWWWWWWWWWWWWW=@WWWWWWWWWWWWWWWWWWW
* WWWWWWWWWWW#++=@WWWWW#**#WWWWWWWWWWWWWWWWWWWWWWWWWWWW
* WWWWWWWWWWWWW#+++=@WWW@#WWWWWWWWWWWWWWWWW**@WWWWWWWWW
* WWWWWWWWWWWWWWW#++++*@WWWWWWWWWWWWW@WWWWWWWWWWWWWWWWW
* WWWWWWWWWWWWWWWWW#+++++*@WWWWW@@WWWWWW@WWWWWWWWWWWWWW
* WWWWWWWWWWWWWW@WWWW#++++++*#WWWWW@@WWWWWW@WWWWWWWWWWW
* WWWWWWWW***@WWW@@WWWW#+++++++*#WWWWW@@WWWWWW@WWWWWWWW
* WWWWWWWW@=#WWWW@@@@WWWW=+++++++++=WWWWW@@@WWWWWWWWWWW
* WWWWWWWWWWWWWW@@@WWWWWWWW=++++++++++=WWWWW@@@WWWWWWWW
* WWWWWWWWWWWWW@@WWWWWW@WWW@+++++++++++++=@WWWWWWWWWWWW
* WWWWWWWWWWWW@WWWWWW@WWWW=+++++++++++++++++*@WWWWWWWWW
* WWWWWWWWWWWWWWWWW@WWWW=++++++++++++++++++++++#WWWWWWW
* WWWWWWWWWWWWWWW@WWWW=+++++++++++++++++++++++*@WWWWWWW
* WWWWWWW=@WWWW@WWWW=+++++++++++++++++++++++*#WWWWWWWWW
* WWWWWWWWWWW@WWWW=+++++++++++++++++++++++*#WWWWWWWWWWW
* WWWWWWWWWWWWWW=+++++++++++++++++++++++*#WWWWWWWWWWWWW
* WWWWWWWWWWWW#+++++++++++++++++++++++*#WWW@@WWWWWWWWWW
* WWWWWWWWWW#+++++++++++++++++++++++*#WWW@@WWWWWWWWWWWW
* WWWWWWWW@+++++++++++++++++++++++*#WWW@@WWWWWWWWWWWWWW
* WWWWWWWW@++++++++++++++++++++++#WWW@@WWWW@WWWWWWWWWWW
* WWWWWWWWWW#*++++++++++++++++*#WWWW@WWWW@@WWWWWWWWWWWW
* WWWWWWWWWWWWW#+++++++++++++*WWWW@WWWW@@@WWWWWWWWWWWWW
* WWWWWWWWWW@@WWWW#++++++++++*WWWWWWW@@@@WWW***WWWWWWWW
* WWWWWWWWWWWWW@@WWWW=*+++++***@WWWWWWW@@WWW@=@WWWWWWWW
* WWWWWWWWWW@WWWWW@@WWWW=*******#WWWWWWWW@WWWWWWWWWWWWW
* WWWWWWWWWWWWW@WWWWW@@WWWW=*****=WWWWWWWWWWWWWWWWWWWWW
* WWWWWWWWWWWWWWWW@WWWWW@@WWWW=****@WWWWWWWWWWWWWWWWWWW
* WWWWWWWWW#@WWWWWWWW@WWWWWWW@@W@=**#WWWWWWWWWWWWWWWWWW
* WWWWWWWWWWWWWWWWWWWWWWWWWWW=#WWWW@==WWWWWWWWWWWWWWWWW
*
* @package    Eter_Minicart
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*}
<div class="minicart" data-update="{url entity='module' name='eter_minicart' controller='updatecart'}">
	<div class="cart">
        <i class="material-icons shopping-cart">shopping_cart</i>
        <span class="cart-products-count">({$cart.products_count})</span>
		
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
					<img src="{$product.cover.medium.url}">
					<p class="product-name">{$product.name}</p>
		          	<p class="product-quantity">{l s='Quantity:' mod='eter_minicart'} {$product.quantity}</p>
					<p class="product-price">{l s='Price:' mod='eter_minicart'} {$product.price}</p>
		          </li>
		        {/foreach}
	      	</ul>
	    {else}
	    	<div class="emty">
	    		<p>
		    		<i class="material-icons shopping-cart">shopping_cart</i>
	        		<span class="cart-products-count">{l s='Empty' mod='eter_minicart'}</span>
	    		</p>
	    	</div>
      	{/if}
      	{if $cart.products_count > 0}
	      	<div class="total">
	      		{l s='Total' mod='eter_minicart'} {$cart.totals.total.amount}
	      	</div>
	      	<div class="buttons">
	      		<a href="{$cart_url}" class="gocart">{l s='Edit cart' mod='eter_minicart'}</a>
	      		<a href="{$urls.pages.order}" class="checkout">{l s='Checkout' mod='eter_minicart'}</a>
	      	</div>
      	{/if}
	</div>
</div>