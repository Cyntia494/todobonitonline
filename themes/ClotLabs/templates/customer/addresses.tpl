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
    {if $customer.addresses}
        <div class="my-account-content">
            {include file='customer/_partials/menu.tpl'}
            <div class="content">
                <div class="content-header">
                    <h2 class="title">
                        {l s='Your addresses' d='Shop.Theme.Customeraccount'}
                    </h2>
                    <h2 class="new-address hidden-sm-down">
                        <a href="{$urls.pages.address}" data-link-action="add-address">
                            <i class="material-icons">&#xE145;</i>
                            <span>{l s='Create new address' d='Shop.Theme.Actions'}</span>
                        </a>
                    </h2>
                </div>
                <div class="account-content">
                    {foreach $customer.addresses as $address}
                        <div class="address-list-item">
                            {block name='customer_address'}
                                {include file='customer/_partials/block-address.tpl' address=$address}
                            {/block}
                        </div>
                    {/foreach}
                </div>
                <h2 class="new-address hidden-md-up">
                    <a href="{$urls.pages.address}" data-link-action="add-address">
                        <i class="material-icons">&#xE145;</i>
                        <span>{l s='Create new address' d='Shop.Theme.Actions'}</span>
                    </a>
                </h2>
            </div>
        </div>
    {else}
        <div class="my-account-content">
            {include file='customer/_partials/menu.tpl'}
            <div class="content">
                <h2>
                    {l s='Your addresses' d='Shop.Theme.Customeraccount'}
                </h2>
                <div class="no-orders">
                    <div class="icon"></div>
                    <p>{l s='You do not have addresses yet' d='Shop.Theme.Customer'}</p>
                </div>
            </div>
        </div>
    {/if}
{/block}
