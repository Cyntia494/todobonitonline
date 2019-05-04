{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{block name='header_banner'}
  <div class="header-banner">
    {hook h='displayBanner'}
  </div>
{/block}
{block name='header_nav'}
  <nav class="header-nav hidden-sm-down">
    <div class="container">
        <div class="row">
          <div class="col-md-8 col-xs-12">
            {hook h='displayNav1'}
          </div>
          <div class="col-md-4 right-nav">
            {hook h='displayNav2'}
          </div>
        </div>
    </div>
  </nav>
{/block}

{block name='header_top'}
  <div class="header-top etertheme-headertopcolor">
    <div class="container">
      <div class="top-content">
        <div class="hamburguer mobilemenu hidden-md-up">
          <i class="fas fa-bars"></i>
          <i class="fal fa-times hide"></i>
        </div>
        <a  class="mainlogo" href="{$urls.base_url}">
          <img class="logo img-responsive" src="{$shop.logo}" alt="{$shop.name}">
        </a>
        {hook h='displayTop'}
      </div>
    </div>
  </div>
  <div class="displayNavFullWidth">
    <div class="container menu-full">
      {hook h='displayMenuFullWidth'}
    </div>
  </div>
{/block}
<div class="lines-header">
  <div class="line1"></div>
  <div class="line2"></div>
  <div class="line3"></div>
</div>