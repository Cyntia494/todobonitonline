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
{extends file='customer/page.tpl'}

{block name='page_content'}
  <div class="my-account-content">
      {include file='customer/_partials/menu.tpl'}
      <div class="content">
        <h2>{l s='Order history' d='Shop.Theme.Customeraccount'}</h2>
        <div class="account-content">
          {if $orders}  
            {foreach from=$orders item=order}
              <div class="table-order">    
                <div class="container-order">
                 
                  <p><strong>{l s='Reference' d='Shop.Theme.Checkout'}</strong><br>{$order.details.reference}</p>
                  <p><strong>{l s='Date' d='Shop.Theme.Checkout'}</strong><br> {$order.details.order_date}</p>
                  <p><strong>{l s='Total price' d='Shop.Theme.Checkout'}</strong><br> {$order.totals.total.value}</p>
                  <p><strong>{l s='Payment' d='Shop.Theme.Checkout'}</strong><br> {$order.details.payment}</p>
                 
                  <span
                    class="label label-pill {$order.history.current.contrast}"
                    style="color:{$order.history.current.color}"
                  >
                  <span class="status"> <h4>{l s='Status' d='Shop.Theme.Checkout'}</h4></span> <span class="message">{$order.history.current.ostate_name}</span>
                  </span><br>
                </div>
                <div class="container-butom">
                  <div class="reorder">
                    {if $order.details.reorder_url}
                        <a href="{$order.details.reorder_url}" title="{l s='Reorder' d='Shop.Theme.Actions'}">
                          <p>{l s='Re-order' d='Shop.Theme.Checkout'}</p>
                        </a>
                    {/if}
                  </div>
                  <div class="order-detail">
                    <a href="{$order.details.details_url}" data-link-action="view-order-details">
                      <div class="overlay"> 
                        <p class="details">{l s='See more' d='Shop.Theme.Checkout'}</p>
                      </div>
                    </a>
                  </div> 
                </div>
              </div>        
            {/foreach}

          {else}
            <div class="no-orders">
              <div class="icon"></div>
              <p>{l s='You do not have orders yet' d='Shop.Theme.Customer'}</p>
            </div>
          {/if}
        </div>
      </div>
  </div>
{/block}
