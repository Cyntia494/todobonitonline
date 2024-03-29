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

{block name='page_title'}
{/block}

{block name='notifications'}
{/block}

{block name='page_content'}
    <div class="section-login">
      <div class="login">
            <h2>{l s='Guest Order Tracking' d='Shop.Theme.Customeraccount'}</h2>
            <div class="loginpage">
                <form id="guestOrderTrackingForm" action="{$urls.pages.guest_tracking}" method="get">
                  <header>
                    <p>{l s='To track your order, please enter the following information:' d='Shop.Theme.Customeraccount'}</p>
                  </header>
                  {block name='notifications'}
                    {include file='_partials/notifications.tpl'}
                  {/block}
                  <section class="form-fields">

                    <div class="form-group">
                      <label class="col-md-12 form-control-label required float-left">
                        {l s='Order Reference:' d='Shop.Forms.Labels'}
                      </label>
                      <div class="col-md-12">
                        <input
                          class="form-control"
                          name="order_reference"
                          type="text"
                          size="8"
                          value="{if isset($smarty.request.order_reference)}{$smarty.request.order_reference}{/if}"
                        >
                        <div class="form-control-comment">
                          {l s='For example: QIIXJXNUI or QIIXJXNUI#1' d='Shop.Theme.Customeraccount'}
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-md-12 form-control-label required float-left">
                        {l s='Email:' d='Shop.Forms.Labels'}
                      </label>
                      <div class="col-md-12">
                        <input
                          class="form-control"
                          name="email"
                          type="email"
                          value="{if isset($smarty.request.email)}{$smarty.request.email}{/if}"
                        >
                      </div>
                    </div>

                  </section>

                  <footer class="form-footer text-xs-left col-md-12 clearfix">
                    <button class="btn btn-primary" type="submit">
                      {l s='Send' d='Shop.Theme.Actions'}
                    </button>
                  </footer>
                </form>
            </div>
        </div>
    </div>
{/block}
