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
{extends file='page.tpl'}

<!--{include file='_partials/notifications.tpl'}-->
{block name='displayLeftContent'}
  <img class="backgroundCoverLeft" src="{$urls.img_url}statics/clothes-dress-fashion.jpg">
{/block}
{block name='page_content'}
  <div class="my-account-content">

      <div class="content">
        <h3>{l s='My account' d='Shop.Theme.Customeraccount'}</h3>
        <div class="account-content">
          <div class="container-options-customer">
            <a href="{$urls.pages.identity}">
                <div class="option-section">
                    <div class="identity-icon">
                    </div>
                    <div class="text">
                        <strong>
                            {l s='Edit your personal information' d='Shop.Theme.Customeraccount'}
                        </strong>
                    </div>
                </div>
            </a>
          </div>
          <div class="container-options-customer">
            <a href="{$urls.pages.addresses}">
                <div class="option-section">
                    <div class="addresses-icon">
                    </div>
                    <div class="text">
                      <strong>
                      {l s='Manage your addresses' 
                      d='Shop.Theme.Customeraccount'}
                      </strong>
                    </div>
                </div>
            </a>
          </div>
          <div class="container-options-customer">
            <a href="{$urls.pages.history}">
                <div class="option-section">
                    <div class="history-icon">
                    </div>
                    <div class="text">
                        <strong>
                        {l s='Organize your orders and re-order' d='Shop.Theme.Customeraccount'}
                        </strong>
                    </div>
                </div>
            </a>
          </div>
          <div class="container-options-customer">
            <a href="{$urls.pages.order_slip}">
                <div class="option-section">
                      <div class="order_slip-icon">
                      </div>
                      <div class="text">
                          <strong>
                          {l s='Manage your credit slips' d='Shop.Theme.Customeraccount'}
                          </strong>
                      </div>
                </div>
            </a>
          </div>
          {if $configuration.voucher_enabled && !$configuration.is_catalog}
            <div class="container-options-customer">
              <a href="{$urls.pages.discount}">
                  <div class="option-section">
                        <div class="discount-icon">
                        </div>
                        <div class="text">
                            <strong>
                            {l s='Manage your vouchers' d='Shop.Theme.Customeraccount'}
                            </strong>
                        </div>
                  </div>
              </a>
            </div>
          {/if}
          {if $configuration.return_enabled && !$configuration.is_catalog}
            <div class="container-options-customer">
              <a href="{$urls.pages.order_follow}">
                  <div class="option-section">
                        <div class="order_follow-icon">
                        </div>
                        <div class="text">
                            <strong>
                            {l s='Return merchandise and know its status' d='Shop.Theme.Customeraccount'}
                            </strong>
                        </div>
                  </div>
              </a>
            </div>
          {/if}
        </div>
      </div>
  </div>
{/block}

