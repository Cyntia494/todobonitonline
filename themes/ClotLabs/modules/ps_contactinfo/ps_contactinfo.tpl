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

<div class="block-contact links wrapper">
  <div class="contact">
    <h4 class="block-contact-title">{$contact_infos.company}</h4>
      <div class="address">
        {if $contact_infos.address.address1 && $contact_infos.address.postcode }
          <p>{$contact_infos.address.address1},{$contact_infos.address.postcode}</p>
        {/if}
        <p>
          {if $contact_infos.address.city}
            {$contact_infos.address.city},
          {/if}
          {if $contact_infos.address.country}
            {$contact_infos.address.country}
          {/if}
        </p>
        {if $contact_infos.phone}
          <p>
            {$contact_infos.phone}
          </p>
        {/if}
        {if $contact_infos.email}
          <p>
            {$contact_infos.email}
          </p>
        {/if}
      </div>
  </div>
</div>
