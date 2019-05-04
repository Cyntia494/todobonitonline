{*
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="block_newsletter ">
  <img src="{$urls.theme_assets}/images/newsletter.jpg">
  <div class="form-newsletter">
    <div class="container">
      <div class="news-form">
        <h3 class="newsletter-title">{l s='Subscribe to our newsletter' d='Shop.Theme.Newsletter'}</h3>
        <div class="newsletter-form">
          <form action="{$urls.pages.index}#footer" method="post">
              <div class="mesage">
                  {if $msg}
                    <p class="alert {if $nw_error}alert-danger{else}alert-success{/if}">
                      {$msg}
                    </p>
                  {/if}
              </div>
              <div class="form-inputs">
                <input
                  name="email"
                  class="newsletter-email"
                  type="text"
                  value="{$value}"
                  placeholder="{l s='Email' d='Shop.Forms.Labels'}"
                >
                {if $conditions}
                  <p>{$conditions}</p>
                {/if}
                <button class="btn btn-secondary newsletter-button" name="submitNewsletter" type="submit">
                  Suscribirse
                </button>
                <input type="hidden" name="action" value="0">
                <div class="clearfix"></div>
              </div>
          </form>
        </div>
         {hook h='displaySocial'}
      </div>
    </div>
  </div>
</div>
