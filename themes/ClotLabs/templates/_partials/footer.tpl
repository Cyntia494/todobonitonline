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
<div class="footer-before">
  {block name='hook_footer_before'}
    {hook h='displayFooterBefore'}
  {/block}
</div>
<div class="footer-container">
  <div class="container">
    <div class="footer">
      <div class="content-footer">
      {block name='hook_footer'}
        {hook h='displayFooter'}
      {/block}
      </div>
    </div>
    
    
  </div>
  <div class="footer-after">
    {block name='hook_footer_after'}
      {hook h='displayFooterAfter'}
    {/block}
    <div class="footer-copyright">
      {block name='copyright_link'}
        <p class="copyright">
            {if isset($eter_copyright) && $eter_copyright}
                {$eter_copyright}
            {else}
                <a href="https://www.eterlabs.com/">
                    <i class="fas fa-heart"></i> - {l s='With love by eterlabs' d='Shop.Theme'}
                </a>
            {/if}
        </p>
      {/block}
    </div>
  </div>
</div>
