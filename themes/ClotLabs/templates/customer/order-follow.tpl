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

  {if $ordersReturn && count($ordersReturn)}

    <div class="my-account-content">
      {include file='customer/_partials/menu.tpl'}
      <div class="content">
        <h2>
          {l s='Merchandise returns' d='Shop.Theme.Customeraccount'}
        </h2>
        {foreach from=$ordersReturn item=return}
          <div class="table-return">    
            <div class="container-return">  

              <h4>{l s='Order' d='Shop.Theme.Customeraccount'}</h4>
              <p><a href="{$return.details_url}">{$return.reference}</a></p>

              <h4>{l s='Return' d='Shop.Theme.Customeraccount'}</h4>
              <p><a href="{$return.return_url}">{$return.return_number}</p>

              <h4>{l s='Package status' d='Shop.Theme.Customeraccount'}</h4>
              <p>{$return.state_name}</p>

              <h4>{l s='Date issued' d='Shop.Theme.Customeraccount'}</h4>
              <p>{$return.return_date}</p>

              <h4>{l s='Returns form' d='Shop.Theme.Customeraccount'}</h4>
              <p>
                {if $return.print_url}
                  <a href="{$return.print_url}">{l s='Print out' d='Shop.Theme.Actions'}</a>
                {else}
                  -
                {/if}
              </p>
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
          {l s='Merchandise returns' d='Shop.Theme.Customeraccount'}
        </h2>
        <div class="no-orders">
          <div class="icon"></div>
          <p>{l s='You do not have merchandise returns yet' d='Shop.Theme.Customer'}</p>
        </div>
      </div>
    </div>
  {/if}

{/block}
