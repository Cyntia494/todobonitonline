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
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if $homeslider.slides}
  <div id="hero-section">
        <div class="slideshow_container">
          <ul class="herohome owl-carousel owl-theme slidehome" id="slider4">
            {foreach from=$homeslider.slides item=slide name='homeslider' key=index}
              <li style="background-image: url({$slide.image_url})" data-hash="slide{$slide.id_slide}">
                <div class="herocontent">
                  <div class="hero">
                    <div class="container">
                      {if $slide.title || $slide.description}
                        <div class="hero-overlay">
                          <p class="bullets"><span class="circle1"></span><span class="circle2"></span></p>
                          <p class="sub-title">{$slide.legend|escape}</p>
                          <h3 class="title">{$slide.title}</h3>
                           <a href="{$slide.url}" class="button-slide">{l s='More information' d='Shop.Theme.Global'}</a>
                        </div>
                      {/if}
                    </div>
                  </div>
                </div>
              </li>
            {/foreach}
          </ul>
        </div>
      <div class="lines2">
        <div class="line1"></div>
        <div class="line2"></div>
        <div class="line3"></div>
      </div>
  </div>
{/if}