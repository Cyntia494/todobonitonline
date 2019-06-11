/**
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
 */
import $ from 'jquery';

function initRmaItemSelector() {
  $('#order-return-form table thead input[type=checkbox]').on('click', function() {
    var checked = $(this).prop('checked');
    $('#order-return-form table tbody input[type=checkbox]').each(function(_, checkbox) {
      $(checkbox).prop('checked', checked);
      $(checkbox).change();
    });
  });

  $('body').on('change','#order-return-form table tbody input[type=checkbox]',function(e) {
  	var id_order_detail = $(this).data('id_order_detail');
  	var id_customization = $(this).data('id_customization');
  	var clase = "#_desktop_return_qty_"+id_order_detail;
  	if (id_customization) {
  		clase = clase+'_'+id_customization;
  	}
  	if ($(this).is(':checked')) {
  		$(clase).show();
  	} else {
  		$(clase).hide();
  	}
  })
}

function setupCustomerScripts() {
  if ($('body#order-detail')) {
    initRmaItemSelector();
  }
}

$(document).ready(setupCustomerScripts);
