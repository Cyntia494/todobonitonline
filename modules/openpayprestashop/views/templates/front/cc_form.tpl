{*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div id="card-container" class="payment_module">
    <div class="openpay-form-container" >
        <div class="row mt30 mb10">
            <div class="col-md-12 store-image">
                <h3 class="openpay_title">{l s='Tarjetas aceptadas' mod='openpayprestashop'}</h3>
                <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/credit_cards.png">
                <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/debit_cards.png">
            </div>                
        </div>

        <div id="openpay-ajax-loader">
            <p>{l s='Estamos registrando tu pago, por favor espera.' mod='openpayprestashop'}</p>
            <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/ajax-loader.gif" alt="" />             
        </div>
        <br>
        <form action="{$action}" id="openpay-payment-form" method="post" class="openpay-payment-form">            
            
            <div class="openpay-payment-errors" style="display: {if isset($openpay_error)}block{else}none{/if};">
                {if isset($openpay_error)}{$openpay_error|escape:'htmlall':'UTF-8'}{/if}
            </div>            

            <div class="row">
                <div class="col-md-12">
                    <label>{l s='Nombre del tarjetahabiente' mod='openpayprestashop'}</label>
                    <input type="text" autocomplete="off" id="holder_name" data-openpay-card="holder_name" class="form-control" placeholder="{l s='Como se muestra en la tarjeta' mod='openpayprestashop'}" />
                </div>
                <div class="col-md-12">
                    <label>{l s='Numero de tarjeta' mod='openpayprestashop'}</label>
                    <input type="text" autocomplete="off" id="card_number" data-openpay-card="card_number" class="form-control" placeholder="•••• •••• •••• ••••" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                    <label>{l s='Fecha de expiracion' mod='openpayprestashop'}</label>
                    <select id="expiration_month" data-openpay-card="expiration_month" class="openpay-card-expiry-month">
                        <option value="01">{l s='Enero' mod='openpayprestashop'}</option>
                        <option value="02">{l s='Febrero' mod='openpayprestashop'}</option>
                        <option value="03">{l s='Marzo' mod='openpayprestashop'}</option>
                        <option value="04">{l s='Abril' mod='openpayprestashop'}</option>
                        <option value="05">{l s='Mayo' mod='openpayprestashop'}</option>
                        <option value="06">{l s='Junio' mod='openpayprestashop'}</option>
                        <option value="07">{l s='Julio' mod='openpayprestashop'}</option>
                        <option value="08">{l s='Agosto' mod='openpayprestashop'}</option>
                        <option value="09">{l s='Septeembre' mod='openpayprestashop'}</option>
                        <option value="10">{l s='Octubre' mod='openpayprestashop'}</option>
                        <option value="11">{l s='Noviembre' mod='openpayprestashop'}</option>
                        <option value="12">{l s='Diciembre' mod='openpayprestashop'}</option>
                    </select>
                    <span> / </span>
                    <select id="expiration_year" data-openpay-card="expiration_year" class="openpay-card-expiry-year">
                        {assign var='startyear' value=$smarty.now|date_format:"%y"}
                        {assign var='endyear' value=($smarty.now|date_format:"%y" + 10)}

                        {for $i=$startyear to $endyear}
                            <option value="{$i|escape:'htmlall':'UTF-8'}">20{$i|escape:'htmlall':'UTF-8'}</option>
                        {/for}
                    </select>

                </div>

                <div class="col-md-6">
                    <label>{l s='Codigo de seguridad' mod='openpayprestashop'}</label>
                    <input id="cvv2" type="password" size="4" autocomplete="off" data-openpay-card="cvv2" class="form-control" placeholder="CVV" />
                </div>

                <div class="col-md-6">
                    <br />
                    <a href="javascript:void(0)" class="" style="border: none;" data-toggle="popover" data-content="{l s='MasterCard and VISA present this three-digit code on the back of the card.' mod='openpayprestashop'}" >
                        <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/cvc_back.png">
                    </a>
                    <a href="javascript:void(0)" class="openpay-card-cvc-info" style="border: none;" data-toggle="popover" data-content="{l s='American Express presents this three-digit code on the front of the card.' mod='openpayprestashop'}">
                        <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/cvc_front.png">
                    </a>
                </div>
            </div>
            {if $show_months_interest_free }        
                <div class="row">
                    <div class="col-md-6">
                        <label>{l s='Meses sin intereses' mod='openpayprestashop'}</label>
                        <select name="interest_free" id="interest-free" style="width: 100%;">
                            <option value="1">{l s="Pago de contado" mod='openpayprestashop'}</option>
                            {foreach $months_interest_free as $interest_free}
                                <option value="{$interest_free}">{$interest_free} meses</option>
                            {/foreach}
                        </select>
                    </div>
                    <div id="total-monthly-payment" class="col-md-6 hidden">        
                        <label>{l s="Pago mensual" mod='openpayprestashop'}</label>
                        <p class="openpay-total">$<span id="monthly-payment">{$total}</span> MXN</p>
                    </div>
                </div>
            {/if}
        </form>
    </div>
</div>
    
<script type="text/javascript">

    $(document).ready(function() {
        
        $('[data-toggle="popover"]').popover({
            trigger: 'hover',
            'placement': 'top'
        });
        
        $('#card_number').cardNumberInput();
        
        var total = {$total};                 
        var months = parseInt($("#interest-free").val());
        var monthly_payment = 0;       
        monthly_payment = total/months;
        var formatted = monthly_payment.toFixed(2);
        $("#monthly-payment").text(formatted);
        
        if (months > 1) {
            $("#total-monthly-payment").removeClass('hidden');
        } else {
            $("#total-monthly-payment").addClass('hidden');
        }

        $("#interest-free").change(function() {      
            monthly_payment = 0;
            months = parseInt($(this).val());     

            if (months > 1) {
                $("#total-monthly-payment").removeClass('hidden');
            } else {
                $("#total-monthly-payment").addClass('hidden');
            }

            monthly_payment = total/months;
            monthly_payment = monthly_payment.toFixed(2);

            $("#monthly-payment").text(monthly_payment);
        });

        var openpay_public_key = "{$pk|escape:'htmlall':'UTF-8'}";
        var openpay_merchant_id = "{$id|escape:'htmlall':'UTF-8'}";
        var mode = "{$mode|escape:'htmlall':'UTF-8'}";

        OpenPay.setId(openpay_merchant_id);
        OpenPay.setApiKey(openpay_public_key);

        if(mode === "0"){
            OpenPay.setSandboxMode(true);
        }

        $("#payment-confirmation > .ps-shown-by-js > button").click(function(event) {            
            var myPaymentMethodSelected = $(".payment-options").find("input[data-module-name='openpayprestashop']").is(":checked");
            if (myPaymentMethodSelected){
                event.preventDefault();
                
                //antifraudes
                OpenPay.deviceData.setup("openpay-payment-form", "device_session_id");
                
                $(this).prop('disabled', true); /* Disable the submit button to prevent repeated clicks */
                $('.openpay-payment-errors').hide();            
                $('#openpay-ajax-loader').show();    
                $('#openpay-payment-form').hide();
                return openpayFormHandler();
            }
        });
        
        //var submitBtn = $("#checkout-payment-step").find("[type=submit]").filter(':last');
        //submitBtn.click(function(event) {    
            //event.preventDefault();
            //submitBtn.prop('disabled', true); /* Disable the submit button to prevent repeated clicks */
            //$('.openpay-payment-errors').hide();            
            //$('#openpay-ajax-loader').show();    
            //$('#openpay-payment-form').hide();
            //return openpayFormHandler();
        //});

    });

    function openpayFormHandler() {
        var holder_name = jQuery('#holder_name').val();
        var card = jQuery('#card_number').val();
        var cvc = jQuery('#cvv2').val();
        var year = jQuery('#expiration_year').val();
        var month = jQuery('#expiration_month').val();

        var data = {
            holder_name: holder_name,
            card_number: card.replace(/ /g, ''),
            expiration_month: month || 0,
            expiration_year: year || 0,
            cvv2: cvc
        };

        OpenPay.token.create(data, success_callbak, error_callbak);

        return false;
    }

    var success_callbak = function(response) {
        $('.openpay-payment-errors').hide();
        var token_id = response.data.id;
        $('#openpay-payment-form').append('<input type="hidden" name="openpay_token" value="' + escape(token_id) + '" />');
        $('#openpay-payment-form').get(0).submit();
    };

    var error_callbak = function(response) {

        var msg = "";
        switch (response.data.error_code) {
            case 1000:
            case 1004:
            case 1005:
                msg = "{l s='Service not available.' mod='openpayprestashop'}";
                break;

            case 1001:
                msg = "{l s='The fields do not have the correct format, or the request does not have fields are required.' mod='openpayprestashop'}";
                break;

            case 2005:
                msg = "{l s='The expiration date has already passed.' mod='openpayprestashop'}";
                break;

            case 2006:
                msg = "{l s='The CVV2 security code is required.' mod='openpayprestashop'}";
                break;

            default: //Demás errores 400
                msg = "{l s='The request could not be processed.' mod='openpayprestashop'}";
                break;
        }
                
        var submitBtn = $("#payment-confirmation > .ps-shown-by-js > button");

        $('.openpay-payment-errors').fadeIn(1000);
        $('.openpay-payment-errors').text('ERROR ' + response.data.error_code + '. ' + response.data.description).fadeIn(1000);                
        submitBtn.prop('disabled', false);
        $('#openpay-payment-form').show();
        $('#openpay-ajax-loader').hide();        
    };

</script>    