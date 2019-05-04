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
{block name='cart_detailed_product'}
  <div class="cart-overview js-cart" data-refresh-url="{url entity='cart' params=['ajax' => true, 'action' => 'refresh']}">
    {if $cart.products}
      <div class="cart-detailed-container">
          <div class="header">
            <div class="Products">
              {l s='Products' d='Shop.Theme.Checkout'}
            </div>

            <div class="content">
              <div class="space"></div>
              <div class="Products-price">
                  {l s='Price' d='Shop.Theme.Checkout'}
                </div>
              <div class="Quantity">     
                {l s='Quantity' d='Shop.Theme.Checkout'}
              </div>
              <div class="Subtotal">
                {l s='Subtotal' d='Shop.Theme.Checkout'}
              </div>
            </div>
          </div>
          <ul class="cart-items">
            {foreach from=$cart.products item=product}
              <li class="cart-item">
                {block name='cart_detailed_product_line'}
                  {include file='checkout/_partials/cart-detailed-product-line.tpl' product=$product}
                {/block}
              </li>
              {if $product.customizations|count >1}<hr>{/if}
            {/foreach}
          </ul>
          {block name='hook_shopping_cart'}
              {hook h='displayShoppingCart'}
          {/block}
      </div>
      <div class="cart-summary-container">
        {block name='cart_summary'}
          <div class="card cart-summary">
              <div class="col-md-12">
                  {block name='cart_voucher'}
                      {include file='checkout/_partials/cart-voucher.tpl'}
                   {/block}
                  {block name='cart_totals'}
                      {include file='checkout/_partials/cart-detailed-totals.tpl' cart=$cart}
                  {/block}
                  {block name='cart_actions'}
                      {include file='checkout/_partials/cart-detailed-actions.tpl' cart=$cart}
                  {/block}
                  {hook h='displayExpressCheckout'}
              </div>
          </div>
      {/block}
      </div>
    {else}
    <div class="content-empty-cart">
        <div class="cart-empty">
            <div class="empty">
                <i class="fas fa-shopping-cart"></i>
                <span class="ups">{l s='Upps!' d='Shop.Theme.Checkout'}</span><br>
                <span class="no-item">{l s='There are no more items in your cart' d='Shop.Theme.Checkout'}</span>
            </div>
        </div>
        <div class="bottom-quick">
            <a type="button" href="{$urls.pages.index}" class="btn btn-secondary" data-dismiss="modal">{l s='Continue shopping' d='Shop.Theme.Actions'}</a>
        </div>
    </div>
    {/if}
  </div>

{/block}
