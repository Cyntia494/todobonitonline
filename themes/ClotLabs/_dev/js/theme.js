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
import 'expose?Tether!tether';
import 'bootstrap/dist/js/bootstrap.min';
import 'flexibility';
import 'bootstrap-touchspin';

import '../css/theme';
import './responsive';
import './checkout';
import './customer';
import './listing';
import './product';
import './cart';

import DropDown from './components/drop-down';
import Form from './components/form';
import ProductMinitature from './components/product-miniature';
import ProductSelect from './components/product-select';
import TopMenu from './components/top-menu';

import prestashop from 'prestashop';
import EventEmitter from 'events';

import './lib/bootstrap-filestyle.min';
import './lib/jquery.scrollbox.min';

import './components/block-cart';

// "inherit" EventEmitter
for (var i in EventEmitter.prototype) {
  prestashop[i] = EventEmitter.prototype[i];
}

$(document).ready(() => {
  let dropDownEl = $('.js-dropdown');
  const form = new Form();
  let topMenuEl = $('.js-top-menu ul[data-depth="0"]');
  let dropDown = new DropDown(dropDownEl);
  let topMenu = new TopMenu(topMenuEl);
  let productMinitature = new ProductMinitature();
  let productSelect  = new ProductSelect();
  dropDown.init();
  form.init();
  topMenu.init();
  productMinitature.init();
  productSelect.init();
});

$(document).ready(function() {
  updateMenu();
  $(window).resize(function(){
    updateMenu();
  });
  $(document).scroll(function(){
    if($(document).scrollTop() > 80) {
      $("#header").addClass('fixed');
      $("body").addClass('fixed');
    } else {
      $("#header").removeClass('fixed');
      $("body").removeClass('fixed');
    }
  });


  $(".mobilemenu").click(function(e) {
    e.preventDefault();
    $(".headerlogin").removeClass('show');
    $("#search_widget").removeClass("showform");
    $(".minicart").removeClass('show');
    $(".menu-container").removeClass("hidden-by-search");
    $("#_desktop_top_menu").toggleClass("showmenu");
    if ($(".menu-_desktop_top_menu").hasClass("showmenu")) {
      $("body").addClass("mobilemenu");
    } else {
      $("body").removeClass("mobilemenu");
    }
  });

  $(".minicart .cart").click(function(e) {
    e.preventDefault();
    if($(document).width() < 1100) {
      $("#_desktop_top_menu").removeClass("showmenu");
      $("body").removeClass("mobilemenu");
      $(".headerlogin").removeClass('show');
      $("#search_widget").removeClass("showform");
      $(".minicart").toggleClass("show");
      if ($('.minicart .content').is(':visible')) {
        $("body").addClass("mobilemenu");
      } else {
        $("body").removeClass("mobilemenu");
      }
    }
  });

  $(".headerlogin .login").click(function(e) {
    e.preventDefault();
    if($(document).width() < 1100) {
      $(".minicart").removeClass('show');
      $("#_desktop_top_menu").removeClass("showmenu");
      $("body").removeClass("mobilemenu");
      $("#search_widget").removeClass("showsearch");
      $("#search_widget").removeClass("showform");
      $(".headerlogin").toggleClass("show");
      if ($('.headerlogin .content').is(':visible')) {
        $("body").addClass("mobilemenu");
      } else {
        $("body").removeClass("mobilemenu");
      }
    }
  });

  $(".mobilesearch").click(function(e) {
    e.preventDefault();
    $(".minicart").removeClass('show');
    $("#_desktop_top_menu").removeClass("showmenu");
    $("body").removeClass("mobilemenu");
    $(".headerlogin").removeClass('show');
    $("#search_widget").removeClass("showsearch");
    $("#search_widget").toggleClass("showform");
    $(".headerlogin,.minicart,.menu-container,.linklist").toggleClass("hidden-by-search");
  });

  $(".mobilemenu").click(function() {

    $(".hamburguer").toggleClass("showform");
  });

  $(".angle-down").click(function() {
    $("._desktop_language_selector").toggleClass("showform");
  });

  $(".angle-down-currency").click(function() {
    $("._desktop_currency_selector").toggleClass("showform");
  });
  
  $(".my-account-content .menu h2").click(function() {
    $(".my-account-content .menu").toggleClass("active");
  });

  $('.back-top').click(function(e){
    e.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, "slow");
  });

  $(".search-tablet").click(function() {
    $(".minicart").removeClass('show');
    $("body").removeClass("mobilemenu");
    $(".headerlogin").removeClass('show');
    $("#search_widget").toggleClass("showsearch");
    $(".menu-container").toggleClass("hidden-by-search");
    $(".no-button").toggleClass("hidden-by-search");
  });

  $(".close").click(function() {
    $("#search_widget").removeClass('showsearch');
    $(".menu-container").removeClass("hidden-by-search");
    $(".no-button").removeClass("hidden-by-search");
  });

  $('.anchor-menu div').click(function(e){
    if($(document).width() < 768) {
      e.preventDefault();
      var parent = $(this).closest('.option-menu');
      $(this).addClass('second')
      parent.toggleClass('selected');
    }
  });


  homeblock();
  $(window).resize(function(){
    homeblock();
  });


  if ($('.herohome').length) {
    $('.herohome').owlCarousel({
      loop:true,
      margin:10,
      dots: true,
      nav:true,
      autoplay:false,
      autoplayTimeout:5000,
      navText: ['<i class="material-icons">&#xE408;</i>','<i class="material-icons">&#xE409;</i>'],
      responsive:{
        0:{
            items:1
        }
      }
    });
  }

  if ($('.cl-comments').length) {
    $('.cl-comments').owlCarousel({
      loop:true,
      margin:10,
      nav:false,
      dots: false,
      navText: ['<i class="material-icons">&#xE408;</i>','<i class="material-icons">&#xE409;</i>'],
      responsive:{
        0:{
            items:1,
        },
        480:{
            items:2,
        },
        768:{
            items:4,
        },
        1200:{
            items:4,
        }
      }
    });
  }
  if ($(".manufacturers-list").length) {
    $(".manufacturers-list").owlCarousel({
      loop:true,
      margin:20,
      responsiveClass:true,
      dots: false,
      nav: false,
      responsive:{
        0:{
            items:2,
        },
        480:{
            items:2,
        },
        768:{
            items:3,
        },
        1200:{
            items:3,
        }
      }
    }); 
  }

  if($('.feature-carousel').length) {
    $('.feature-carousel').owlCarousel({
      loop:true,
      margin:0,
      nav:true,
      dots: false,
      navText: ['<i class="fas fa-angle-left"></i>','<i class="fas fa-angle-right"></i>'],
      responsive:{
          0:{
              items:2,
          },
          640:{
              items:2,
          },
          768:{
              items:3,
          },
          1200:{
              items:4,
          },
      }
    });
  }
});

var updateMenu = function() {
  var submenuwidth = 0;
  $('#top-menu > .content-menu > li').each(function(index) {
    $(this).removeClass('desktop-hide');
    var liwidth = $(this).width();
    submenuwidth += liwidth;
    if (liwidth == 0) {
      $(this).addClass('desktop-hide');
    } else if (submenuwidth > $('#top-menu').width()) {
      $(this).addClass('desktop-hide');
    } 
  });
}

var homeblock = function() {
  if ($(".blockslider").length) {
    var winWidth = (window.innerWidth > 0) ? window.innerWidth : screen.width;

    if(!$(".blockslider").data('owlCarousel')) {
      $(".blockslider").owlCarousel({
        loop:true,
        margin:20,
        responsiveClass:true,
        dots: true,
        nav: false,
        responsive:{
          0:{
              items:1,
          },
          640:{
              items:2,
          },
          768:{
              items:2,
          },
          1200:{
              items:3,
          }
        }
      });  
    }
  }
}