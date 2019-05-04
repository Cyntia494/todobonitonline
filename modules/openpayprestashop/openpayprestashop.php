<?php

/**
 * 2007-2015 PrestaShop
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
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2015 PrestaShop SA
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class OpenpayPrestashop extends PaymentModule
{

    private $error = array();
    private $validation = array();
    private $limited_currencies = array('MXN','USD');

    public function __construct() {
        if (!class_exists('Openpay', false)) {
            include_once(dirname(__FILE__).'/lib/Openpay.php');
        }

        $this->sandbox_url = 'https://sandbox-api.openpay.mx/v1';
        $this->url = 'https://api.openpay.mx/v1';

        $this->name = 'openpayprestashop';
        $this->tab = 'payments_gateways';
        $this->version = '2.8.0';
        $this->author = 'Openpay SAPI de CV';
        $this->module_key = '23c1a97b2718ec0aec28bb9b3b2fc6d5';

        parent::__construct();
        $warning = 'Are you sure you want uninstall this module?';
        $this->displayName = $this->l('Openpay');
        $this->description = $this->l('Acepta pagos con tarjeta de crédito con Openpay.');
        $this->confirmUninstall = $this->l($warning);
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->months_interest_free = array('3' => '3 meses', '6' => '6 meses', '9' => '9 meses', '12' => '12 meses', '18' => '18 meses');
    }

    /**
     * Openpay's module installation
     *
     * @return boolean Install result
     */
    public function install() {
        /* For 1.4.3 and less compatibility */
        $update_config = array(
            'PS_OS_CHEQUE' => 1,
            'PS_OS_PAYMENT' => 2,
            'PS_OS_PREPARATION' => 3,
            'PS_OS_SHIPPING' => 4,
            'PS_OS_DELIVERED' => 5,
            'PS_OS_CANCELED' => 6,
            'PS_OS_REFUND' => 7,
            'PS_OS_ERROR' => 8,
            'PS_OS_OUTOFSTOCK' => 9,
            'PS_OS_BANKWIRE' => 10,
            'PS_OS_PAYPAL' => 11,
            'PS_OS_WS_PAYMENT' => 12);

        foreach ($update_config as $u => $v) {
            if (!Configuration::get($u) || (int) Configuration::get($u) < 1) {
                if (defined('_'.$u.'_') && (int) constant('_'.$u.'_') > 0) {
                    Configuration::updateValue($u, constant('_'.$u.'_'));
                } else {
                    Configuration::updateValue($u, $v);
                }
            }
        }

        $ret = parent::install() && 
                $this->registerHook('payment') &&
                $this->registerHook('paymentOptions') &&
                $this->registerHook('displayHeader') &&
                $this->registerHook('displayPaymentTop') &&
                $this->registerHook('paymentReturn') &&
                $this->registerHook('displayMobileHeader') &&
                Configuration::updateValue('OPENPAY_MODE', 0) &&
                Configuration::updateValue('OPENPAY_MONTHS_INTEREST_FREE', '') &&
                Configuration::updateValue('OPENPAY_MINIMUM_AMOUNT', '1800') &&
                Configuration::updateValue('OPENPAY_CHARGE_TYPE', 'direct') &&                
                $this->installDb();

        return $ret;
    }

    /**
     * Openpay's module database tables installation
     *
     * @return boolean Database tables installation result
     */
    public function installDb() {

        $return = true;

        $return &= Db::getInstance()->Execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'openpay_customer` (
                `id_openpay_customer` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `openpay_customer_id` varchar(32) NULL,
                `id_customer` int(10) unsigned NOT NULL,
                `date_add` datetime NOT NULL,
                PRIMARY KEY (`id_openpay_customer`),
                KEY `id_customer` (`id_customer`)) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

        $return &= Db::getInstance()->Execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'openpay_transaction` (
                `id_openpay_transaction` int(11) NOT NULL AUTO_INCREMENT,
                `type` enum(\'card\',\'store\',\'bank_account\', \'bitcoin\') NOT NULL,
                `id_cart` int(10) unsigned NOT NULL,
                `id_order` int(10) unsigned NOT NULL,
                `id_transaction` varchar(32) NOT NULL,
                `amount` decimal(10,2) NOT NULL,
                `status` enum(\'paid\',\'unpaid\') NOT NULL,
                `currency` varchar(3) NOT NULL,
                `fee` decimal(10,2) NOT NULL,
                `mode` enum(\'live\',\'test\') NOT NULL,
                `date_add` datetime NOT NULL,
                `due_date` datetime NULL,
                `reference` varchar(50) NULL,
                `barcode` varchar(250) NULL,
                `clabe` varchar(50) NULL,
                PRIMARY KEY (`id_openpay_transaction`),
                KEY `idx_transaction` (`type`,`id_order`,`status`)) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

        return $return;
    }

    /**
     * Openpay's module uninstallation (Configuration values, database tables...)
     *
     * @return boolean Uninstall result
     */
    public function uninstall() {
        return parent::uninstall() &&                
                Configuration::deleteByName('OPENPAY_MONTHS_INTEREST_FREE') &&
                Configuration::deleteByName('OPENPAY_MINIMUM_AMOUNT');
    }

    /**
     * Hook to the top a payment page
     *
     * @param array $params Hook parameters
     * @return string Hook HTML
     */
    public function hookDisplayPaymentTop($params) {
        Logger::addLog('hookDisplayPaymentTop', 1, null, null, null, true);
        return;
    }

    public function hookDisplayMobileHeader() {
        return $this->hookHeader();
    }

    /**
     * Load Javascripts and CSS related to the Openpay's module
     * Only loaded during the checkout process
     *
     * @return string HTML/JS Content
     */
    public function hookHeader($params) {
        if (!$this->active) {
            return;
        }

        if (Tools::getValue('module') === 'onepagecheckoutps' ||
                Tools::getValue('controller') === 'order-opc' ||
                Tools::getValue('controller') === 'orderopc' ||
                Tools::getValue('controller') === 'order') {

            $this->context->controller->addCSS($this->_path.'views/css/openpay-prestashop.css');

            $this->context->controller->registerJavascript(
                    'remote-openpay-js', 'https://openpay.s3.amazonaws.com/openpay.v1.min.js', ['position' => 'bottom', 'server' => 'remote']
            );

            $this->context->controller->registerJavascript(
                    'remote-openpaydata-js', 'https://openpay.s3.amazonaws.com/openpay-data.v1.min.js', ['position' => 'bottom', 'server' => 'remote']
            );
        } else {
            Logger::addLog('NO hookHeader, Controller => '.Tools::getValue('controller'), 1, null, null, null, true);
        }
    }

    /**
     * Hook to the new PS 1.7 payment options hook
     *
     * @param array $params Hook parameters
     * @return array|bool
     * @throws Exception
     * @throws SmartyException
     */
    public function hookPaymentOptions($params) {

        if (version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
            return false;
        }
        /** @var Cart $cart */
        $cart = $params['cart'];
        if (!$this->active) {
            return false;
        }

        if (!$this->checkCurrency()) {
            return;
        }

        $externalOption = new PrestaShop\PrestaShop\Core\Payment\PaymentOption();
        $externalOption->setCallToActionText($this->l('Tarjeta de crédito-débito'))
                //->setLogo(Media::getMediaPath(_PS_MODULE_DIR_.$this->name.'/views/img/openpay-logo.png'))            
                ->setForm($this->generateForm($cart))
                ->setModuleName($this->name)
                ->setAdditionalInformation($this->context->smarty->fetch('module:openpayprestashop/views/templates/front/payment_infos.tpl'));

        return array($externalOption);
    }

    protected function generateForm($cart) {

        $pk = Configuration::get('OPENPAY_MODE') ? Configuration::get('OPENPAY_PUBLIC_KEY_LIVE') : Configuration::get('OPENPAY_PUBLIC_KEY_TEST');
        $id = Configuration::get('OPENPAY_MODE') ? Configuration::get('OPENPAY_MERCHANT_ID_LIVE') : Configuration::get('OPENPAY_MERCHANT_ID_TEST');

        $selected_months_interest_free = array();
        if (Configuration::get('OPENPAY_MONTHS_INTEREST_FREE') != null) {
            $selected_months_interest_free = explode(',', Configuration::get('OPENPAY_MONTHS_INTEREST_FREE'));
        }

        $show_months_interest_free = false;
        if (count($selected_months_interest_free) > 0 && ($cart->getOrderTotal() >= Configuration::get('OPENPAY_MINIMUM_AMOUNT'))) {
            $show_months_interest_free = true;
        }

        Logger::addLog('generateForm => '.$this->context->cookie->openpay_error, 1, null, null, null, true);

        if (!empty($this->context->cookie->openpay_error)) {
            $this->context->smarty->assign('openpay_error', $this->context->cookie->openpay_error);
            $this->context->cookie->__set('openpay_error', null);
        }

        $this->context->smarty->assign(array(
            'js_dir' => _PS_JS_DIR_,
            'pk' => $pk,
            'id' => $id,
            'mode' => Configuration::get('OPENPAY_MODE'),
            'nbProducts' => $cart->nbProducts(),
            'total' => $cart->getOrderTotal(),
            'module_dir' => $this->_path,
            'months_interest_free' => $selected_months_interest_free,
            'show_months_interest_free' => $show_months_interest_free,
            'action' => $this->context->link->getModuleLink($this->name, 'validation', array(), Tools::usingSecureMode()),
        ));

        return $this->context->smarty->fetch('module:openpayprestashop/views/templates/front/cc_form.tpl');
    }

    /**
     * Process a payment
     *
     * @param string $token Openpay Transaction ID (token)
     */
    public function processPayment($token = null, $device_session_id = null, $interest_free = null) {
        if (!$this->active) {
            return;
        }
        
        $mail_detail = '';
        $message_aux = '';
        $payment_method = 'card';
        $charge_type = Configuration::get('OPENPAY_CHARGE_TYPE');
        $cart = $this->context->cart;
        $display_name = $this->l('Openpay card payment');
        $pk = Configuration::get('OPENPAY_MODE') ? Configuration::get('OPENPAY_PRIVATE_KEY_LIVE') : Configuration::get('OPENPAY_PRIVATE_KEY_TEST');
        $id = Configuration::get('OPENPAY_MODE') ? Configuration::get('OPENPAY_MERCHANT_ID_LIVE') : Configuration::get('OPENPAY_MERCHANT_ID_TEST');

        Openpay::getInstance($id, $pk);
        Openpay::setProductionMode(Configuration::get('OPENPAY_MODE'));
        
        $charge_request = array(
            'method' => 'card',
            'currency' => $this->context->currency->iso_code,
            'source_id' => $token,
            'device_session_id' => $device_session_id,
            'amount' => $cart->getOrderTotal(),
            'description' => $this->l('PrestaShop Cart ID:').' '.(int) $cart->id,
        );

        if ($interest_free > 1) {
            $charge_request['payment_plan'] = array('payments' => (int) $interest_free);
        }

        if ($charge_type == '3d') {
            $charge_request['use_3d_secure'] = true;
            $charge_request['redirect_url'] = _PS_BASE_URL_.__PS_BASE_URI__.'module/openpayprestashop/confirm';
        }
        
        try {            
            $openpay_customer = $this->getOpenpayCustomer($this->context->cookie->id_customer);
            $charge = $openpay_customer->charges->create($charge_request);
               

            // Si tiene habilitado el 3D SECURE 
            if ($charge->payment_method && $charge->payment_method->type == 'redirect') {
                Tools::redirect($charge->payment_method->url);
            }

            $order_status = (int) Configuration::get('PS_OS_PAYMENT');

            $message_aux = $this->l(Tools::ucfirst($charge->card->type).' card:').' '.
                    Tools::ucfirst($charge->card->brand).' (Exp: '.$charge->card->expiration_month.'/'.$charge->card->expiration_year.')'."\n".
                    $this->l('Card number:').' '.$charge->card->card_number."\n";

            $message = $this->l('Openpay Transaction Details:')."\n\n".
                    $this->l('Transaction ID:').' '.$charge->id."\n".
                    $this->l('Payment method:').' '.Tools::ucfirst($payment_method)."\n".
                    $message_aux.
                    $this->l('Amount:').' $'.number_format($charge->amount, 2).' '.Tools::strtoupper($charge->currency)."\n".
                    $this->l('Status:').' '.($charge->status == 'completed' ? $this->l('Paid') : $this->l('Unpaid'))."\n".
                    $this->l('Processed on:').' '.date('Y-m-d H:i:s')."\n".
                    $this->l('Mode:').' '.(Configuration::get('OPENPAY_MODE') ? $this->l('Live') : $this->l('Test'))."\n";

            /* Create the PrestaShop order in database */
            $detail = array('{detail}' => $mail_detail);
            $this->validateOrder(
                    (int) $this->context->cart->id, (int) $order_status, $charge->amount, $display_name, $message, $detail, null, false, false
            );

            /** @since 1.5.0 Attach the Openpay Transaction ID to this Order */
            if (version_compare(_PS_VERSION_, '1.5', '>=')) {
                $new_order = new Order((int) $this->currentOrder);
                if (Validate::isLoadedObject($new_order)) {
                    $payment = $new_order->getOrderPaymentCollection();
                    if (isset($payment[0])) {
                        $payment[0]->transaction_id = pSQL($charge->id);
                        $payment[0]->save();
                    }
                }
            }

            /** Store the transaction details */            
            $fee = $charge->fee->amount + $charge->fee->tax;
            Db::getInstance()->insert('openpay_transaction', array(
                'type' => pSQL($payment_method),
                'id_cart' => (int) $this->context->cart->id,
                'id_order' => (int) $this->currentOrder,
                'id_transaction' => pSQL($charge->id),
                'amount' => (float) $charge->amount,
                'status' => pSQL($charge->status == 'completed' ? 'paid' : 'unpaid'),
                'fee' => (float) $fee,
                'currency' => pSQL($charge->currency),
                'mode' => pSQL(Configuration::get('OPENPAY_MODE') == 'true' ? 'live' : 'test'),
                'date_add' => date('Y-m-d H:i:s'),
                'due_date' => date('Y-m-d H:i:s'),
                'barcode' => null,
                'reference' => null,
                'clabe' => null
            ));
            

            // Update order_id from Openpay Charge
            $this->updateOpenpayCharge($charge->id, $this->currentOrder, $new_order->reference);

            /** Redirect the user to the order confirmation page history */
            $redirect = __PS_BASE_URI__.'index.php?controller=order-confirmation&id_cart='.(int) $this->context->cart->id.
                    '&id_module='.(int) $this->id.
                    '&id_order='.(int) $this->currentOrder.
                    '&key='.$this->context->customer->secure_key;

            Logger::addLog($redirect, 1, null, null, null, true);

            Tools::redirect($redirect);

            /** catch the openpay error */
        } catch (Exception $e) {
            // Si tiene habilitada la autenticación selectiva
            if ($charge_type == 'auth' && $e->getCode() == '3005') {
                $charge_request['use_3d_secure'] = true;
                $charge_request['redirect_url'] = _PS_BASE_URL_.__PS_BASE_URI__.'module/openpayprestashop/confirm';
                
                $openpay_customer = $this->getOpenpayCustomer($this->context->cookie->id_customer);
                $charge = $openpay_customer->charges->create($charge_request);
                
                Tools::redirect($charge->payment_method->url);
            }
            
            if (class_exists('Logger')) {
                Logger::addLog($this->l('Openpay - Payment transaction failed').' '.$e->getMessage(), 1, null, 'Cart', (int) $this->context->cart->id, true);
            }

            $this->error($e);
            //$this->context->cookie->__set('openpay_error', $e->getMessage());

            Tools::redirect('index.php?controller=order&step=1');
        }
    }

    /**
     * Check settings requirements to make sure the Openpay's module will work properly
     *
     * @return boolean Check result
     */
    public function checkSettings() {
        if (Configuration::get('OPENPAY_MODE')) {
            return Configuration::get('OPENPAY_PUBLIC_KEY_LIVE') != '' && Configuration::get('OPENPAY_PRIVATE_KEY_LIVE') != '';
        } else {
            return Configuration::get('OPENPAY_PUBLIC_KEY_TEST') != '' && Configuration::get('OPENPAY_PRIVATE_KEY_TEST') != '';
        }
    }

    /**
     * Check technical requirements to make sure the Openpay's module will work properly
     *
     * @return array Requirements tests results
     */
    public function checkRequirements() {
        $tests = array('result' => true);

        $tests['curl'] = array(
            'name' => $this->l('La extensión PHP cURL debe estar habilitada en tu servidor.'),
            'result' => extension_loaded('curl'));

        if (Configuration::get('OPENPAY_MODE')) {
            $tests['ssl'] = array(
                'name' => $this->l('SSL debe estar habilitado (antes de pasar a modo productivo)'),
                'result' => Configuration::get('PS_SSL_ENABLED') || (!empty($_SERVER['HTTPS']) && Tools::strtolower($_SERVER['HTTPS']) != 'off'));
        }

        $tests['php54'] = array(
            'name' => $this->l('Tu servidor debe de contar con PHP 5.4 o posterior.'),
            'result' => version_compare(PHP_VERSION, '5.4.0', '>='));

        $tests['configuration'] = array(
            'name' => $this->l('Deberás de registrarte en Openpay y configurar las credenciales (ID, llave privada y llave pública.)'),
            'result' => $this->getMerchantInfo());

        foreach ($tests as $k => $test) {
            if ($k != 'result' && !$test['result']) {
                $tests['result'] = false;
            }
        }

        return $tests;
    }

    /**
     * Display the Back-office interface of the Openpay's module
     *
     * @return string HTML/JS Content
     */
    public function getContent() {
        $this->context->controller->addCSS(array($this->_path.'views/css/openpay-prestashop-admin.css'));

        $errors = array();

        /** Update Configuration Values when settings are updated */
        if (Tools::isSubmit('SubmitOpenpay')) {

            $configuration_values = array(
                'OPENPAY_MODE' => Tools::getValue('openpay_mode'),
                'OPENPAY_MERCHANT_ID_TEST' => trim(Tools::getValue('openpay_merchant_id_test')),
                'OPENPAY_MERCHANT_ID_LIVE' => trim(Tools::getValue('openpay_merchant_id_live')),
                'OPENPAY_PUBLIC_KEY_TEST' => trim(Tools::getValue('openpay_public_key_test')),
                'OPENPAY_PUBLIC_KEY_LIVE' => trim(Tools::getValue('openpay_public_key_live')),
                'OPENPAY_PRIVATE_KEY_TEST' => trim(Tools::getValue('openpay_private_key_test')),
                'OPENPAY_PRIVATE_KEY_LIVE' => trim(Tools::getValue('openpay_private_key_live')),
                'OPENPAY_MONTHS_INTEREST_FREE' => implode(',', Tools::getValue('months_interest_free')),
                'OPENPAY_MINIMUM_AMOUNT' => Tools::getValue('openpay_minimum_amount_interest_free'),
                'OPENPAY_CHARGE_TYPE' => Tools::getValue('openpay_charge_type')
            );

            foreach ($configuration_values as $configuration_key => $configuration_value) {
                Configuration::updateValue($configuration_key, $configuration_value);
            }

            $mode = Configuration::get('OPENPAY_MODE') ? 'LIVE' : 'TEST';

            if (!$this->getMerchantInfo()) {
                $errors[] = 'Openpay keys are incorrect.';
                Configuration::deleteByName('OPENPAY_PUBLIC_KEY_'.$mode);
                Configuration::deleteByName('OPENPAY_MERCHANT_ID_'.$mode);
                Configuration::deleteByName('OPENPAY_PRIVATE_KEY_'.$mode);
            }
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->error[] = $error;
            }
        }

        $requirements = $this->checkRequirements();

        foreach ($requirements as $key => $requirement) {
            if ($key != 'result') {
                $this->validation[] = $requirement;
            }
        }

        if ($requirements['result']) {
            $validation_title = $this->l('Todos los chequeos fueron exitosos, ahora puedes comenzar a utilizar Openpay.');
        } else {
            $validation_title = $this->l('Al menos un problema fue encontrado para poder comenzar a utilizar Openpay. Por favor resuelve los problemas y refresca esta página.');
        }

        $selected_months_interest_free = array();
        if (Configuration::get('OPENPAY_MONTHS_INTEREST_FREE') != null) {
            $selected_months_interest_free = explode(',', Configuration::get('OPENPAY_MONTHS_INTEREST_FREE'));
        }

        $this->context->smarty->assign(array(
            'receipt' => $this->_path.'views/img/recibo.png',
            'openpay_form_link' => $_SERVER['REQUEST_URI'],
            'openpay_configuration' => Configuration::getMultiple(
                    array(
                        'OPENPAY_MODE',
                        'OPENPAY_MERCHANT_ID_TEST',
                        'OPENPAY_MERCHANT_ID_LIVE',
                        'OPENPAY_PUBLIC_KEY_TEST',
                        'OPENPAY_PUBLIC_KEY_LIVE',
                        'OPENPAY_PRIVATE_KEY_TEST',
                        'OPENPAY_PRIVATE_KEY_LIVE',
                        'OPENPAY_MINIMUM_AMOUNT',
                        'OPENPAY_CHARGE_TYPE'
                    )
            ),
            'openpay_ssl' => Configuration::get('PS_SSL_ENABLED'),
            'openpay_validation' => $this->validation,
            'openpay_error' => (empty($this->error) ? false : $this->error),
            'openpay_validation_title' => $validation_title,
            'months_interest_free' => $this->months_interest_free,
            'selected_months_interest_free' => $selected_months_interest_free
        ));

        return $this->display(__FILE__, 'views/templates/admin/configuration.tpl');
    }

    /**
     * Check availables currencies for module
     * 
     * @return boolean
     */
    public function checkCurrency() {
        return in_array($this->context->currency->iso_code, $this->limited_currencies);
    }

    public function getPath() {
        return $this->_path;
    }

    public function getOpenpayCustomer($customer_id) {
        $cart = $this->context->cart;
        $customer = new Customer((int) $cart->id_customer);

        $openpay_customer = Db::getInstance()->getRow('
                    SELECT openpay_customer_id
                    FROM '._DB_PREFIX_.'openpay_customer
                    WHERE id_customer = '.(int) $customer_id);

        if (!isset($openpay_customer['openpay_customer_id'])) {
            try {

                $address = Db::getInstance()->getRow('
                    SELECT *
                    FROM '._DB_PREFIX_.'address
                    WHERE id_customer = '.(int) $customer_id);

                $state = Db::getInstance()->getRow('
                    SELECT id_country, name
                    FROM '._DB_PREFIX_.'state
                    WHERE id_state = '.(int) $address['id_state']);


                $customer_data = array(
                    'requires_account' => false,
                    'name' => $customer->firstname,
                    'last_name' => $customer->lastname,
                    'email' => $customer->email,
                    'phone_number' => $address['phone'],
                );

                if (!$this->isNullOrEmpty($address['address1']) && !$this->isNullOrEmpty($address['postcode']) && !$this->isNullOrEmpty($address['city']) && !$this->isNullOrEmpty($state['name'])) {
                    $customer_data['address'] = array(
                        'line1' => $address['address1'],
                        'line2' => $address['address2'],
                        'postal_code' => $address['postcode'],
                        'city' => $address['city'],
                        'state' => $state['name'],
                        'country_code' => 'MX'
                    );
                    $string_array = http_build_query($customer_data, '', ', ');
                    Logger::addLog($this->l('Customer Address: ').$string_array, 1, null, 'Cart', (int) $this->context->cart->id, true);
                }

                $customer_openpay = $this->createOpenpayCustomer($customer_data);

                Db::getInstance()->Execute('
                        INSERT INTO '._DB_PREFIX_.'openpay_customer (id_openpay_customer, openpay_customer_id, id_customer, date_add)
                        VALUES (null, \''.pSQL($customer_openpay->id).'\', '.(int) $this->context->cookie->id_customer.', NOW())');

                return $customer_openpay;
            } catch (Exception $e) {
                if (class_exists('Logger')) {
                    Logger::addLog($this->l('Openpay - Can not create Openpay Customer'), 1, null, 'Cart', (int) $this->context->cart->id, true);
                }
            }
        } else {
            return $this->getCustomer($openpay_customer['openpay_customer_id']);
        }
    }

    public function getCustomer($customer_id) {
        $pk = Configuration::get('OPENPAY_MODE') ? Configuration::get('OPENPAY_PRIVATE_KEY_LIVE') : Configuration::get('OPENPAY_PRIVATE_KEY_TEST');
        $id = Configuration::get('OPENPAY_MODE') ? Configuration::get('OPENPAY_MERCHANT_ID_LIVE') : Configuration::get('OPENPAY_MERCHANT_ID_TEST');

        $openpay = Openpay::getInstance($id, $pk);
        Openpay::setProductionMode(Configuration::get('OPENPAY_MODE'));

        $customer = $openpay->customers->get($customer_id);
        return $customer;
    }

    public function createOpenpayCustomer($customer_data) {
        $pk = Configuration::get('OPENPAY_MODE') ? Configuration::get('OPENPAY_PRIVATE_KEY_LIVE') : Configuration::get('OPENPAY_PRIVATE_KEY_TEST');
        $id = Configuration::get('OPENPAY_MODE') ? Configuration::get('OPENPAY_MERCHANT_ID_LIVE') : Configuration::get('OPENPAY_MERCHANT_ID_TEST');

        $openpay = Openpay::getInstance($id, $pk);
        Openpay::setProductionMode(Configuration::get('OPENPAY_MODE'));

        try {
            $customer = $openpay->customers->add($customer_data);
            return $customer;
        } catch (Exception $e) {
            $this->error($e);
        }
    }

//    public function createOpenpayCharge($customer, $charge_request) {
//        $pk = Configuration::get('OPENPAY_MODE') ? Configuration::get('OPENPAY_PRIVATE_KEY_LIVE') : Configuration::get('OPENPAY_PRIVATE_KEY_TEST');
//        $id = Configuration::get('OPENPAY_MODE') ? Configuration::get('OPENPAY_MERCHANT_ID_LIVE') : Configuration::get('OPENPAY_MERCHANT_ID_TEST');
//
//        Openpay::getInstance($id, $pk);
//        Openpay::setProductionMode(Configuration::get('OPENPAY_MODE'));
//
//        try {
//            $charge = $customer->charges->create($charge_request);
//            return $charge;
//        } catch (Exception $e) {                        
//            if (Configuration::get('OPENPAY_CHARGE_TYPE') == 'auth' && $e->getCode() == '3005') {
//                
//            }
//            
//            return $this->error($e);
//        }
//    }

    public function updateOpenpayCharge($transaction_id, $order_id, $reference) {
        $customer = $this->getOpenpayCustomer($this->context->cookie->id_customer);
        $pk = Configuration::get('OPENPAY_MODE') ? Configuration::get('OPENPAY_PRIVATE_KEY_LIVE') : Configuration::get('OPENPAY_PRIVATE_KEY_TEST');
        $id = Configuration::get('OPENPAY_MODE') ? Configuration::get('OPENPAY_MERCHANT_ID_LIVE') : Configuration::get('OPENPAY_MERCHANT_ID_TEST');

        Openpay::getInstance($id, $pk);
        Openpay::setProductionMode(Configuration::get('OPENPAY_MODE'));

        try {
            $charge = $customer->charges->get($transaction_id);
            $charge->update(array('order_id' => $order_id, 'description' => 'PrestaShop ORDER #'.$reference));
            return true;
        } catch (Exception $e) {
            $this->error($e);
        }
    }

    public function getOpenpayCharge($customer, $transaction_id) {
        $pk = Configuration::get('OPENPAY_MODE') ? Configuration::get('OPENPAY_PRIVATE_KEY_LIVE') : Configuration::get('OPENPAY_PRIVATE_KEY_TEST');
        $id = Configuration::get('OPENPAY_MODE') ? Configuration::get('OPENPAY_MERCHANT_ID_LIVE') : Configuration::get('OPENPAY_MERCHANT_ID_TEST');

        Openpay::getInstance($id, $pk);
        Openpay::setProductionMode(Configuration::get('OPENPAY_MODE'));

        try {
            $charge = $customer->charges->get($transaction_id);
            return $charge;
        } catch (Exception $e) {
            $this->error($e);
        }
    }

    public function getMerchantInfo() {
        $sk = Configuration::get('OPENPAY_MODE') ? Configuration::get('OPENPAY_PRIVATE_KEY_LIVE') : Configuration::get('OPENPAY_PRIVATE_KEY_TEST');
        $id = Configuration::get('OPENPAY_MODE') ? Configuration::get('OPENPAY_MERCHANT_ID_LIVE') : Configuration::get('OPENPAY_MERCHANT_ID_TEST');

        $url = (Configuration::get('OPENPAY_MODE') ? $this->url : $this->sandbox_url).'/'.$id;

        $username = $sk;
        $password = '';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CAINFO, _PS_MODULE_DIR_.$this->name.'/lib/data/cacert.pem');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $result = curl_exec($ch);

        if (curl_exec($ch) === false) {
            Logger::addLog('Curl error '.curl_errno($ch).': '.curl_error($ch), 1, null, null, null, true);
        } else {
            $info = curl_getinfo($ch);
            Logger::addLog('HTTP code '.$info['http_code'].' on request to '.$info['url'], 1, null, null, null, true);
        }

        curl_close($ch);

        $array = Tools::jsonDecode($result, true);

        if (array_key_exists('id', $array)) {
            return true;
        } else {
            return false;
        }
    }

    public function error($e, $backend = false) {
        switch ($e->getErrorCode()) {
            /* ERRORES GENERALES */
            case '1000':
            case '1004':
            case '1005':
                $msg = $this->l('Servicio no disponible.');
                break;

            /* ERRORES TARJETA */
            case '3001':                
                $msg = $this->l('La tarjeta fue declinada.');
                break;
            
            case '3004':
                $msg = $this->l('La tarjeta ha sido identificada como una tarjeta robada.');
                break;
            
            case '3005':
                $msg = $this->l('La tarjeta ha sido rechazada por el sistema antifraudes.');
                break;
            
            case '3007':
                $msg = $this->l('La tarjeta fue declinada.');
                break;

            case '3002':
                $msg = $this->l('La tarjeta ha expirado.');
                break;

            case '3003':
                $msg = $this->l('La tarjeta no tiene fondos suficientes.');
                break;

            case '3006':
                $msg = $this->l('La operación no esta permitida para este cliente o esta transacción.');
                break;

            case '3008':
                $msg = $this->l('La tarjeta no es soportada en transacciones en línea..');
                break;

            case '3009':
                $msg = $this->l('La tarjeta fue reportada como perdida.');
                break;

            case '3010':
                $msg = $this->l('El banco ha restringido la tarjeta.');
                break;

            case '3011':
                $msg = $this->l('El banco ha solicitado que la tarjeta sea retenida. Contacte al banco.');
                break;

            case '3012':
                $msg = $this->l('Se requiere solicitar al banco autorización para realizar este pago.');
                break;

            default: /* Demás errores 400 */
                $msg = $this->l('la petición no pudo ser procesada.');
                break;
        }

        $error = 'ERROR '.$e->getErrorCode().'. '.$msg;
        $this->context->cookie->__set('openpay_error', $error);
        $this->context->cookie->__set('openpay_error_code', $e->getErrorCode());

        if ($backend) {
            return Tools::jsonDecode(Tools::jsonEncode(array('error' => $e->getErrorCode(), 'msg' => $error)), false);
        } else {
            if (class_exists('Logger')) {
                Logger::addLog($this->l('#Openpay - Payment transaction failed').' '.$error, 1, null, 'Cart', (int) $this->context->cart->id, true);
            }
            return false;
            //Tools::redirect('index.php?controller=order&step=1');      
        }
    }

    public function isNullOrEmpty($string) {
        return (!isset($string) || trim($string) === '');
    }

}
