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
  {block name='order_infos'}
    <div class="my-account-content">
      {block name='order_menu'}
        {include file='customer/_partials/menu.tpl'}
      {/block}
      <div class="content">
          <h2>
            {l s='Order details' d='Shop.Theme.Customeraccount'}
          </h2>
          <div class="account-content">
            <div id="order-infos">
              <!-- Order Reference -->
              <div class="box">
                <div class="row">
                  <div class="col-xs-{if $order.details.reorder_url}9{else}12{/if}">
                    <strong>
                      {l s='Order Reference %reference% - placed on %date%' d='Shop.Theme.Customeraccount' sprintf=['%reference%' => $order.details.reference, '%date%' => $order.details.order_date]}
                    </strong>
                    {if $order.details.invoice_url}
                      <br>
                      <a href="{$order.details.invoice_url}">
                        {l s='Download your invoice as a PDF file.' d='Shop.Theme.Customeraccount'}
                      </a>
                    {/if}
                  </div>
                  {if $order.details.reorder_url}
                    <div class="col-xs-3 text-xs-right">
                      <a href="{$order.details.reorder_url}" class="button-primary">{l s='Reorder' d='Shop.Theme.Actions'}</a>
                    </div>
                  {/if}
                </div>
                <div class="clearfix"></div>
              </div>
              <!-- Order Address -->
              {block name='addresses'}
                <div class="addresses">
                  <div class="address invoice-address">
                    <article id="invoice-address" class="box">
                      <h3>{l s='Invoice address %alias%' d='Shop.Theme.Checkout' sprintf=['%alias%' => $order.addresses.invoice.alias]}</h3>
                      <address>{$order.addresses.invoice.formatted nofilter}</address>
                    </article>
                  </div>

                  {if $order.addresses.delivery}
                    <div class="address delivery-address">
                      <article id="delivery-address" class="box">
                        <h3>{l s='Delivery address %alias%' d='Shop.Theme.Checkout' sprintf=['%alias%' => $order.addresses.delivery.alias]}</h3>
                        <address>{$order.addresses.delivery.formatted nofilter}</address>
                      </article>
                    </div>
                  {/if}
                </div>
                <div class="clearfix"></div>
              {/block}
              <!-- Order Address -->
              {block name='payment'}
                <div class="payments">
                  <div class="payment payment-method">
                    <article id="invoice-address" class="box">
                      <h3>{l s='Payment method' d='Shop.Theme.Checkout'}</h3>
                      <div>{$order.details.payment}</div>
                    </article>
                  </div>

                  {if $order.addresses.delivery}
                    <div class="payment delivery-method">
                      <article id="delivery-method" class="box">
                        <h3>{l s='Carrier' d='Shop.Theme.Checkout'}</h3>
                        <div>{$order.carrier.name}</div>
                      </article>
                    </div>
                  {/if}
                </div>
                <div class="clearfix"></div>
              {/block}
              {if $order.details.recyclable || $order.details.gift_message}
                <div class="box">
                  <ul>
                    {if $order.details.recyclable}
                      <li>
                        {l s='You have given permission to receive your order in recycled packaging.' d='Shop.Theme.Customeraccount'}
                      </li>
                    {/if}

                    {if $order.details.gift_message}
                      <li>{l s='You have requested gift wrapping for this order.' d='Shop.Theme.Customeraccount'}</li>
                      <li>{l s='Message' d='Shop.Theme.Customeraccount'} {$order.details.gift_message nofilter}</li>
                    {/if}
                  </ul>
                </div>
              {/if}
            </div>
            
            {if $order.follow_up}
              <div class="box">
                <p>{l s='Click the following link to track the delivery of your order' d='Shop.Theme.Customeraccount'}</p>
                <a href="{$order.follow_up}">{$order.follow_up}</a>
              </div>
            {/if}

            

            {block name='order_history'}
              <section id="order-history" class="box">
                <h3>{l s='Follow your order\'s status step-by-step' d='Shop.Theme.Customeraccount'}</h3>
                <table class="table table-striped table-bordered table-labeled hidden-xs-down">
                  <thead class="thead-default">
                    <tr>
                      <th>{l s='Date' d='Shop.Theme'}</th>
                      <th>{l s='Status' d='Shop.Theme'}</th>
                    </tr>
                  </thead>
                  <tbody>
                    {foreach from=$order.history item=state}
                      <tr>
                        <td>{$state.history_date}</td>
                        <td>
                          <span class="label label-pill {$state.contrast}" style="color:{$state.color}">
                            {$state.ostate_name}
                          </span>
                        </td>
                      </tr>
                    {/foreach}
                  </tbody>
                </table>
                <div class="hidden-sm-up history-lines">
                  {foreach from=$order.history item=state}
                    <div class="history-line">
                      <div class="date">{$state.history_date}</div>
                      <div class="state">
                        <span class="label label-pill {$state.contrast}" style="color:{$state.color}">
                          {$state.ostate_name}
                        </span>
                      </div>
                    </div>
                  {/foreach}
                </div>
              </section>
            {/block}

            {$HOOK_DISPLAYORDERDETAIL nofilter}

            {block name='order_detail'}
              {if $order.details.is_returnable}
                {include file='customer/_partials/order-detail-return.tpl'}
              {else}
                {include file='customer/_partials/order-detail-no-return.tpl'}
              {/if}
            {/block}

            {block name='order_carriers'}
              {if $order.shipping}
                <div class="box shipping-table">
                  <h3>{l s='Shipping details' d='Shop.Theme.Customeraccount'}</h3>
                  <table class="table table-striped table-bordered hidden-sm-down">
                    <thead class="thead-default">
                      <tr>
                        <th>{l s='Date' d='Shop.Theme'}</th>
                        <th>{l s='Carrier' d='Shop.Theme.Checkout'}</th>
                        <th>{l s='Weight' d='Shop.Theme.Checkout'}</th>
                        <th>{l s='Shipping cost' d='Shop.Theme.Checkout'}</th>
                        <th>{l s='Tracking number' d='Shop.Theme.Checkout'}</th>
                      </tr>
                    </thead>
                    <tbody>
                      {foreach from=$order.shipping item=line}
                        <tr>
                          <td>{$line.shipping_date}</td>
                          <td>{$line.carrier_name}</td>
                          <td>{$line.shipping_weight}</td>
                          <td>{$line.shipping_cost}</td>
                          <td>{$line.tracking}</td>
                        </tr>
                      {/foreach}
                    </tbody>
                  </table>
                  <div class="hidden-md-up shipping-lines">
                    {foreach from=$order.shipping item=line}
                      <div class="shipping-line">
                        <ul>
                          <li>
                            <strong>{l s='Date' d='Shop.Theme'}</strong> {$line.shipping_date}
                          </li>
                          <li>
                            <strong>{l s='Carrier' d='Shop.Theme.Checkout'}</strong> {$line.carrier_name}
                          </li>
                          <li>
                            <strong>{l s='Weight' d='Shop.Theme.Checkout'}</strong> {$line.shipping_weight}
                          </li>
                          <li>
                            <strong>{l s='Shipping cost' d='Shop.Theme.Checkout'}</strong> {$line.shipping_cost}
                          </li>
                          <li>
                            <strong>{l s='Tracking number' d='Shop.Theme.Checkout'}</strong> {$line.tracking}
                          </li>
                        </ul>
                      </div>
                    {/foreach}
                  </div>
                </div>
              {/if}
            {/block}

            {block name='order_messages'}
              {include file='customer/_partials/order-messages.tpl'}
            {/block}
          </div>
        </div>
    </div>
  {/block}
{/block}
