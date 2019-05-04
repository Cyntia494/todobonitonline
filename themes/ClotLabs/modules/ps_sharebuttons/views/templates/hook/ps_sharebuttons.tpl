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

{block name='social_sharing'}
  {if $social_share_links}
    <div class="socialsharing">
      <span>{l s='Share' d='Shop.Theme.Actions'}</span>
      <ul>
        {foreach from=$social_share_links item='social_share_link'}
          <li class="share-button">
            <a href="{$social_share_link.url}" title="{$social_share_link.label}" target="_blank">
                {if $social_share_link.class == "facebook"}
                  <i class="fab fa-facebook-f"></i>
                {elseif $social_share_link.class == "twitter"}
                  <i class="fab fa-twitter"></i>
                {elseif $social_share_link.class == "instagram"}
                  <i class="fab fa-instagram"></i>
                {elseif $social_share_link.class == "rss"}
                  <i class="fal fa-rss"></i>
                {elseif $social_share_link.class == "youtube"}
                  <i class="fab fa-youtube"></i>
                {elseif $social_share_link.class == "googleplus"}
                  <i class="fab fa-google-plus-g"></i>
                {elseif $social_share_link.class == "pinterest"}
                  <i class="fab fa-pinterest-p"></i>
                {elseif $social_share_link.class == "vimeo"}
                  <i class="fab fa-vimeo-v"></i>
                {/if}
            </a>
          </li>
        {/foreach}
      </ul>
    </div>
  {/if}
{/block}
