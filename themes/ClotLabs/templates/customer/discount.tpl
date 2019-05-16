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
  {if $cart_rules}

  <div class="my-account-content">
    {include file='customer/_partials/menu.tpl'}
    <div class="content">
      <h2>
        {l s='Your vouchers' d='Shop.Theme.Customeraccount'}
      </h2>
      {foreach from=$cart_rules item=cart_rule}
        <div class="table-voucher">    
          <div class="container-voucher">  
            <h4>{l s='Code' d='Shop.Theme.Checkout'}</h4>
            <p scope="row">{$cart_rule.code}</p>

            <h4>{l s='Description' d='Shop.Theme.Checkout'}</h4>
            <p>{$cart_rule.name}</p>

            <h4>{l s='Quantity' d='Shop.Theme.Checkout'}</h4>
            <p>{$cart_rule.quantity_for_user}</p>

            <h4>{l s='Value' d='Shop.Theme.Checkout'}</h4>
            <p>{$cart_rule.value}</p>

            <h4>{l s='Minimum' d='Shop.Theme.Checkout'}</h4>
            <p>{$cart_rule.voucher_minimal}</p>

            <h4>{l s='Cumulative' d='Shop.Theme.Checkout'}</h4>
            <p>{$cart_rule.voucher_cumulable}</p>

            <h4>{l s='Expiration date' d='Shop.Theme.Checkout'}</h4>
            <p>{$cart_rule.voucher_date}</p>
          </div>
        </div>
      {/foreach}
    </div>
  </div>
  {else}
  <div class="my-account-content">
    {include file='customer/_partials/menu.tpl'}
    <div class="content">
      <h2>
        {l s='Your vouchers' d='Shop.Theme.Customeraccount'}
      </h2>
      <div class="no-orders">
        <div class="icon"></div>
        <p>{l s='You do not have voucher yet' d='Shop.Theme.Customer'}</p>
      </div>
    </div>
  </div>
  {/if}
{/block}
