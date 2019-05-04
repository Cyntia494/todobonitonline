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
{extends file=$layout}
{block name='breadcrumb'}{/block}
{block name='content'}
    <section id="main">
        <div class="loading-cart">
            <img src="{$urls.theme_assets}images/loader.gif" alt="loader"/>
        </div>
        <div class="cart-grid">
            <!-- Left Block: cart product informations & shpping -->
            <div class="cart-grid-body">
                <!-- cart products detailed -->
                <div class="card cart-container">
                    <div class="card-block ">
                        <h1 class="h1">{l s='Shopping Cart' d='Shop.Theme.Checkout'}</h1>
                    </div>
                    
                    {block name='cart_overview'}
                        {include file='checkout/_partials/cart-detailed.tpl' cart=$cart}
                    {/block}
                    <!-- Right Block: cart subtotal & cart total -->
                    
                </div>
                <div>
                    
                </div>
                <!-- shipping informations -->
                {block name='hook_shopping_cart_footer'}
                    {hook h='displayShoppingCartFooter'}
                {/block}
            </div>
            
        </div>
    </section>
{/block}
