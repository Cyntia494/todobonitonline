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

$(document).ready(function () {
  createProductSpin();
  createInputFile();
  coverImage();
  imageScrollBox();

  prestashop.on('updatedProduct', function (event) {
    createInputFile();
    coverImage();
    if (event && event.product_minimal_quantity) {
      const minimalProductQuantity = parseInt(event.product_minimal_quantity, 10);
      const quantityInputSelector = '#quantity_wanted';
      let quantityInput = $(quantityInputSelector);

      // @see http://www.virtuosoft.eu/code/bootstrap-touchspin/ about Bootstrap TouchSpin
      quantityInput.trigger('touchspin.updatesettings', {min: minimalProductQuantity});
    }
    imageScrollBox();
    $($('.tabs .nav-link.active').attr('href')).addClass('active').removeClass('fade');
    $('.js-product-images-modal').replaceWith(event.product_images_modal);
  });

  $('body').on('click','.products-detail-tab-link',function(e){
    e.preventDefault();
    $('.products-detail-tab-link').removeClass('active');
    $(this).addClass('active');
    $('.tab-pane').addClass('fade').removeClass('active');
    $($(this).attr('href')).addClass('active').removeClass('fade');
  });

  

  function coverImage() {
    $('.product-gallery').eagleGallery({
      miniSliderArrowPos: 'inside',
      changeMediumStyle: true,
      autoPlayMediumImg: false,
      openGalleryStyle: 'transform',
      hideOneThumb: false,
      bottomControlLine: true,
      default: 'light',
      miniSlider: {
        itemsCustom: [[0, 1],[250, 4], [450, 4], [650, 5], [850, 5]],
        margin: 9,
        afterInit: function($elem) {
          $elem.find('.owl-item:first-child').addClass('active');
        }
      },
    });
  }

  function imageScrollBox()
  {
    if ($('#main .js-qv-product-images li').length > 2) {
      $('#main .js-qv-mask').addClass('scroll');
      $('.scroll-box-arrows').addClass('scroll');
        $('#main .js-qv-mask').scrollbox({
          direction: 'h',
          distance: 113,
          autoPlay: false
        });
        $('.scroll-box-arrows .left').click(function () {
          $('#main .js-qv-mask').trigger('backward');
        });
        $('.scroll-box-arrows .right').click(function () {
          $('#main .js-qv-mask').trigger('forward');
        });
    } else {
      $('#main .js-qv-mask').removeClass('scroll');
      $('.scroll-box-arrows').removeClass('scroll');
    }
  }

  function createInputFile()
  {
    $('.js-file-input').on('change',(event)=>{
      $('.js-file-name').text($(event.currentTarget).val());
    });
  }

  function createProductSpin()
  {
    let quantityInput = $('#quantity_wanted');
    quantityInput.TouchSpin({
      verticalbuttons: true,
      verticalupclass: 'material-icons touchspin-up',
      verticaldownclass: 'material-icons touchspin-down',
      buttondown_class: 'btn btn-touchspin js-touchspin',
      buttonup_class: 'btn btn-touchspin js-touchspin',
      min: parseInt(quantityInput.attr('min'), 10),
      max: 1000000
    });

    quantityInput.on('change', function (event) {
      let $productRefresh = $('.product-refresh');
      $(event.currentTarget).trigger('touchspin.stopspin');
      $productRefresh.trigger('click', {eventType: 'updatedProductQuantity'});
      event.preventDefault();

      return false;
    });
  }
});
