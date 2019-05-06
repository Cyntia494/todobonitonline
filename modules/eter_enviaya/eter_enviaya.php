<?php 
/*
* WWWWWWW@#WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
* WWWWWWWWW#*=WWWWWWWWWWWWWWWWWWWW=@WWWWWWWWWWWWWWWWWWW
* WWWWWWWWWWW#++=@WWWWW#**#WWWWWWWWWWWWWWWWWWWWWWWWWWWW
* WWWWWWWWWWWWW#+++=@WWW@#WWWWWWWWWWWWWWWWW**@WWWWWWWWW
* WWWWWWWWWWWWWWW#++++*@WWWWWWWWWWWWW@WWWWWWWWWWWWWWWWW
* WWWWWWWWWWWWWWWWW#+++++*@WWWWW@@WWWWWW@WWWWWWWWWWWWWW
* WWWWWWWWWWWWWW@WWWW#++++++*#WWWWW@@WWWWWW@WWWWWWWWWWW
* WWWWWWWW***@WWW@@WWWW#+++++++*#WWWWW@@WWWWWW@WWWWWWWW
* WWWWWWWW@=#WWWW@@@@WWWW=+++++++++=WWWWW@@@WWWWWWWWWWW
* WWWWWWWWWWWWWW@@@WWWWWWWW=++++++++++=WWWWW@@@WWWWWWWW
* WWWWWWWWWWWWW@@WWWWWW@WWW@+++++++++++++=@WWWWWWWWWWWW
* WWWWWWWWWWWW@WWWWWW@WWWW=+++++++++++++++++*@WWWWWWWWW
* WWWWWWWWWWWWWWWWW@WWWW=++++++++++++++++++++++#WWWWWWW
* WWWWWWWWWWWWWWW@WWWW=+++++++++++++++++++++++*@WWWWWWW
* WWWWWWW=@WWWW@WWWW=+++++++++++++++++++++++*#WWWWWWWWW
* WWWWWWWWWWW@WWWW=+++++++++++++++++++++++*#WWWWWWWWWWW
* WWWWWWWWWWWWWW=+++++++++++++++++++++++*#WWWWWWWWWWWWW
* WWWWWWWWWWWW#+++++++++++++++++++++++*#WWW@@WWWWWWWWWW
* WWWWWWWWWW#+++++++++++++++++++++++*#WWW@@WWWWWWWWWWWW
* WWWWWWWW@+++++++++++++++++++++++*#WWW@@WWWWWWWWWWWWWW
* WWWWWWWW@++++++++++++++++++++++#WWW@@WWWW@WWWWWWWWWWW
* WWWWWWWWWW#*++++++++++++++++*#WWWW@WWWW@@WWWWWWWWWWWW
* WWWWWWWWWWWWW#+++++++++++++*WWWW@WWWW@@@WWWWWWWWWWWWW
* WWWWWWWWWW@@WWWW#++++++++++*WWWWWWW@@@@WWW***WWWWWWWW
* WWWWWWWWWWWWW@@WWWW=*+++++***@WWWWWWW@@WWW@=@WWWWWWWW
* WWWWWWWWWW@WWWWW@@WWWW=*******#WWWWWWWW@WWWWWWWWWWWWW
* WWWWWWWWWWWWW@WWWWW@@WWWW=*****=WWWWWWWWWWWWWWWWWWWWW
* WWWWWWWWWWWWWWWW@WWWWW@@WWWW=****@WWWWWWWWWWWWWWWWWWW
* WWWWWWWWW#@WWWWWWWW@WWWWWWW@@W@=**#WWWWWWWWWWWWWWWWWW
* WWWWWWWWWWWWWWWWWWWWWWWWWWW=#WWWW@==WWWWWWWWWWWWWWWWW
*
* @category   eter_enviaya
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/
if (!defined('_PS_VERSION_')) {
    exit;
};
require_once dirname(__FILE__)."/src/autoload.php";

class Eter_Enviaya extends CarrierModule
{
    public $_html = "";
    public $id_carrier;
	public $carriers = [];
    public $enviaya_configuration = "ETER_SHIPPING_ID_CARRIER";

	function __construct()
	{
		$this->name = 'eter_enviaya';
        $this->author = 'ETERLABS S.A.S de C.V'; 
        $this->version = '1.0.0';
        $this->tab = 'shipping_logistics';
        $this->secure_key = Tools::encrypt($this->name);
        $this->author_uri = 'https://www.eterlabs.com';
        
        parent::__construct();
        $this->bootstrap = true;
        $this->displayName = $this->l('Envia ya');
        $this->description = $this->l('by using a enviaya element, you can add shipping order and calculate costs');
        $this->confirmUninstall = $this->l('Are you sure you want uninstall this module?');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
        $this->enviayaws = new EnviaYa();
        $this->services = new EnviaYaServices();
	}
	/**
     * Module::install()
     */
    public function install()
    {
        /* Adds Module */
        return parent::install() 
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayAdminOrderContentShip')
            && $this->registerHook('displayAdminOrderTabShip')
            && $this->registerHook('actionValidateOrder')
            && $this->registerHook('displayOrderDetail')
            && $this->registerHook('actionAdminControllerSetMedia')
            && $this->registerHook('actionCartSave')
            
            //&& $this->services->createTables()
            && EterHelper::addColumn("carrier", "enviaya_carrier", "varchar(50)")
            && EterHelper::addColumn("carrier", "enviaya_service_code", "varchar(50)")
            && EterHelper::addColumn("carrier", "enviaya_carrier_code", "varchar(50)")
            && EterHelper::addColumn("order_carrier", "enviaya_rate_id", "varchar(50)")
            && EterHelper::addColumn("order_carrier", "enviaya_label_url", "varchar(350)");
    }
    /**
     * @see Module::uninstall()
     */
    public function uninstall()
    {
        $res = parent::uninstall();
        $carriers = Db::getInstance()->executeS('SELECT id_carrier FROM ' . _DB_PREFIX_ . 'carrier WHERE external_module_name="eter_enviaya"');
        foreach ($carriers as $carrier) {
            $carrier = new Carrier((int)$carrier['id_carrier']);
            $carrier->delete = 1;
            $carrier->save();
        }
        Configuration::deleteByName($this->enviaya_configuration);
        return $res;
    }
    
    /**
     * Admnin order Shipping menu tab
     */
    public function hookactionCartSave($params) 
    {
        EnviaYa::clear();
    }
    /**
     * Admnin order Shipping menu tab
     */
    public function hookdisplayAdminOrderTabShip($params) 
    {
        $order = $params['order'];
        if ($order->id) {
            if ($this->services->isEnviaYa($order->id)) {
                return $this->display(__FILE__, 'views/templates/admin/enviaya_tab.tpl');
            }
        }
    }
    /**
     * Admnin order Shipping tab
     */
    public function hookdisplayAdminOrderContentShip($params) 
    {
        $order = $params['order'];
        if ($order->id) {
            if ($this->services->isEnviaYa($order->id)) {
                $data = $this->services->getShipmentData($order->id);
                if($data->tracking_number) {
                    $tracking = (array)$this->getTracking($data->enviaya_carrier,$data->tracking_number);
                    $smartydata['tracking'] = $tracking;
                }
                $link = $this->context->link;
                $smartydata['orderid'] = $order->id;
                $smartydata['carrier'] = (array)$data;
                $urlParams['configure'] = $this->name;
                $urlParams['module_name'] = $this->name;
                $urlParams['module_name'] = $this->tab;
                $urlParams['requestShipment'] = 1;
                $smartydata['requesturl'] = $this->context->link->getAdminLink('AdminModules', true,[],$urlParams);
                $smartydata['abletoship'] = !$this->services->hasShipment($order->id);
                $this->smarty->assign($smartydata);
                return $this->display(__FILE__, 'views/templates/admin/enviaya_content.tpl');
            }
        }
    }
    /**
     * Show order tracking 
     */
    public function hookdisplayOrderDetail($params) 
    {
        $order = $params['order'];
        if ($order->id) {
            $data = $this->services->getShipmentData($order->id);
            if($data->tracking_number) {
                $tracking = (array)$this->getTracking($data->enviaya_carrier,$data->tracking_number);
                $smartydata['tracking'] = $tracking;
                $smartydata['carrier'] = (array)$data;
                $this->smarty->assign($smartydata);
                return $this->display(__FILE__, 'views/templates/customer/tracking.tpl');
            }
        }
    }
    /**
     * Update order carrier 
     */
    public function hookactionValidateOrder($params) 
    {
        $order = $params['order'];
        if ($order->id) {
            $service = $this->services->getService($order->id_carrier);
            $rates = $this->getCarriersWebService();
            if ($service && $service->active && isset($rates[$service->enviaya_service_code])) {
                $rateService = (object)$rates[$service->enviaya_service_code];
                $data['enviaya_rate_id'] = $rateService->rate_id;
                $where = 'id_carrier='.(int)$order->id_carrier.' and id_order='.(int)$order->id;
                Db::getInstance()->update('order_carrier', $data, $where);
            }
        }
    }
    /**
     * Load rates from enviaya
     */
    public function getOrderShippingCost($params,$shipping_cost) 
    {
        $rates = $this->getCarriersWebService();
        $service = $this->services->getService($this->id_carrier);
        if ($service && $service->active && isset($rates[$service->enviaya_service_code])) {
            $rateService = (object)$rates[$service->enviaya_service_code];
            return ceil(Tools::convertPrice($rateService->total_amount));
        } else {
            return false;
        }
    }
    /**
     * Default method to load external price
     */
    public function getOrderShippingCostExternal($cart) 
    {
        return $this->getOrderShippingCost($params, 0);
    }
    /**
    * Get Origin Address
    */ 
    public function getOriginAddress() 
    {
        $address = [];
        $address['full_name'] = Configuration::get('ENVIAYA_SENDER_NAME');
        $address['country_code'] = Configuration::get('ENVIAYA_SENDER_COUNTRYCODE');
        $address['postal_code'] = Configuration::get('ENVIAYA_SENDER_POSTCODE');
        $address['direction_1'] = Configuration::get('ENVIAYA_SENDER_ADDRESS1');
        $address['direction_2'] = Configuration::get('ENVIAYA_SENDER_ADDRESS2');
        $address['state_code'] = Configuration::get('ENVIAYA_SENDER_STATECODE');
        $address['city'] = Configuration::get('ENVIAYA_SENDER_CITY');
        $address['phone'] = Configuration::get('ENVIAYA_SENDER_PHONE');
        return $address;
    }
    /**
    * Get request shipment
    */
    public function getTracking($carrier,$shipment_number) 
    {
        $url = "https://enviaya.com.mx/api/v1/trackings";
        $data = [
            "carrier"=> $carrier,
            "shipment_number" => $shipment_number
        ];
        $response = EnviaYa::callWS($url,$data);
        EnviaYa::log(print_r($response,TRUE));
        return $response;
    }
    /**
    * Get request shipment
    */
    public function requetShipment($orderid,$rateid,$carrier,$carrier_code) 
    {
        $url = "https://enviaya.com.mx/api/v1/shipments";
        $data = [
            "carrier"=> $carrier,
            "carrier_service_code" => $carrier_code,
            "rate_id" => $rateid,
            "shipment"=> [
                "shipment_type" => "Package",
                "content" => "New shipment for order id: ".$orderid,
                "parcels" => $this->getParcels($orderid)
            ],
            "origin_direction" => $this->getOriginAddress(),
            "destination_direction" => $this->getDestinationAddress($orderid)
        ];
        $response = EnviaYa::callWS($url,$data);
        EnviaYa::log(print_r($response,TRUE));
        return $response;
    }
    /**
    * Get Available Services
    */
    public function getCarriersWebService($idorder = null)
    {
        if (count($this->context->cart->getProducts(true)) >= 1) {
            $parcels = $this->getParcels();
            $destination = $this->getDestinationAddress($idorder);
            if (count($parcels) && $destination) {
                $key = Configuration::get('ENVIAYA_SENDER_KEY');
                $account = Configuration::get('ENVIAYA_SENDER_ACCOUNT');
                $data = [
                    "shipment"=> [
                        "shipment_type" => "Package",
                        "parcels" => $this->getParcels()
                    ],
                    "origin_direction" => $this->getOriginAddress(),
                    "destination_direction" => $destination,
                ];
                $response = EnviaYa::getCarriersWebService($data);
                EnviaYa::log(print_r($response,TRUE));
                return $response;
            }
        }  
    }
    /**
     * getContent
     */
    public function getContent()
    {
        if (Tools::isSubmit('requestShipment')) {
            $this->requestShipment();
        } else if (Tools::isSubmit('submitConfig')) {
            Configuration::updateValue('ENVIAYA_ACCOUNT_SANDBOX',Tools::getValue('ENVIAYA_ACCOUNT_SANDBOX'));
            Configuration::updateValue('ENVIAYA_SENDER_SANDBOXKEY',Tools::getValue('ENVIAYA_SENDER_SANDBOXKEY'));
            Configuration::updateValue('ENVIAYA_SENDER_PRODUCTIONKEY',Tools::getValue('ENVIAYA_SENDER_PRODUCTIONKEY'));
            Configuration::updateValue('ENVIAYA_SENDER_ACCOUNT',Tools::getValue('ENVIAYA_SENDER_ACCOUNT'));
            Configuration::updateValue('ENVIAYA_SENDER_NAME',Tools::getValue('ENVIAYA_SENDER_NAME'));
            Configuration::updateValue('ENVIAYA_SENDER_POSTCODE',Tools::getValue('ENVIAYA_SENDER_POSTCODE'));
            Configuration::updateValue('ENVIAYA_SENDER_ADDRESS1',Tools::getValue('ENVIAYA_SENDER_ADDRESS1'));
            Configuration::updateValue('ENVIAYA_SENDER_ADDRESS2',Tools::getValue('ENVIAYA_SENDER_ADDRESS2'));
            Configuration::updateValue('ENVIAYA_SENDER_COUNTRYCODE',Tools::getValue('ENVIAYA_SENDER_COUNTRYCODE'));
            Configuration::updateValue('ENVIAYA_SENDER_CITY',Tools::getValue('ENVIAYA_SENDER_CITY'));
            Configuration::updateValue('ENVIAYA_SENDER_STATECODE',Tools::getValue('ENVIAYA_SENDER_STATECODE'));
            Configuration::updateValue('ENVIAYA_SENDER_PHONE',Tools::getValue('ENVIAYA_SENDER_PHONE'));
            Configuration::updateValue('ENVIAYA_ACCOUNT_LOGS',Tools::getValue('ENVIAYA_ACCOUNT_LOGS'));
            $availableServices = Tools::getValue('groupBox');
            EnviaYaServices::activateService($availableServices);
        } 
        $this->_html = EterHelper::getInfo($this);
        $this->_html .= $this->renderConfigForm();
        return $this->_html;
    }
    /**
     * Request to create a new shipment
     */
    private function requestShipment() 
    {
        $order = new Order(Tools::getValue('order'));
        $data = EnviaYaServices::getShipmentData($order->id);
        $ws = $this->requetShipment($order->id,$data->rateid,$data->carrier,$data->carrier_code);
        $response['success'] = false;
        if(property_exists($ws,"enviaya_shipment_number")) {
            $tracking_number = "";
            $id_order_carrier = $this->getIdOrderCarrier($order);
            $orderCarrier = new OrderCarrier($id_order_carrier);
            $update['tracking_number'] = $ws->carrier_shipment_number;
            $update['enviaya_label_url'] = $ws->label_share_link;
            
            Db::getInstance()->update('order_carrier', $update, "id_order_carrier={$orderCarrier->id}");
            if ($orderCarrier->sendInTransitEmail($order)) {
                $customer = new Customer((int)$order->id_customer);
                $carrier = new Carrier((int)$order->id_carrier, $order->id_lang);
                Hook::exec('actionAdminOrdersTrackingNumberUpdate', array(
                    'order' => $order,
                    'customer' => $customer,
                    'carrier' => $carrier
                ), null, false, true, false, $order->id_shop);
                
                $history = new OrderHistory();
                $history->id_order = (int)$order->id;
                $history->changeIdOrderState(Configuration::get('PS_OS_SHIPPING'), (int)$order->id);
            } 
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['message'] = $ws->status_message;
        }
        header('Content-Type: application/json');
        die(Tools::jsonEncode($response));
    }
    /**
    * Load order carrier id
    */
    private function getIdOrderCarrier($order) 
    {
        $query = new DbQuery();
        $query->select("id_order_carrier");
        $query->from("order_carrier", "ordca");
        $query->where("id_carrier={$order->id_carrier} and id_order={$order->id}");
		$idOrderCarrier = (int)Db::getInstance()->getValue($query);
        return $idOrderCarrier;
    }
    /**
     * Render configuration form
     */
    public function renderConfigForm() 
    { 
        $available_services = array();
        $data = array();
        foreach (EnviaYa::getCarriersServices() as $code => $service) {
            $data['groupBox_' . $code] = $this->services->isServiceActive($code);
            $available_services[] = array('id_group' => $code, 'name' => $service['name']);
        }
        $fields_form = array (
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Sandbox Mode'),
                        'name' => 'ENVIAYA_ACCOUNT_SANDBOX',
                        'is_bool' => true,
                        'required' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('API KEY SANDBOX'),
                        'name' => 'ENVIAYA_SENDER_SANDBOXKEY',
                        'hint' => $this->l('EnviaYa key for sandbox.'),
                        'lang' => false,
                        'required' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('API KEY PRODUCTION'),
                        'name' => 'ENVIAYA_SENDER_PRODUCTIONKEY',
                        'hint' => $this->l('EnviaYa key for production.'),
                        'lang' => false,
                        'required' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('API ACCOUNT'),
                        'name' => 'ENVIAYA_SENDER_ACCOUNT',
                        'hint' => $this->l('Api account key.'),
                        'lang' => false,
                        'required' => true,
                    ), 
                    array(
                        'type' => 'text',
                        'label' => $this->l('Name'),
                        'name' => 'ENVIAYA_SENDER_NAME',
                        'lang' => false,
                        'required' => true,
                    ), 
                    array(
                        'type' => 'text',
                        'label' => $this->l('Postcode'),
                        'name' => 'ENVIAYA_SENDER_POSTCODE',
                        'lang' => false,
                        'required' => true,
                    ), 
                    array(
                        'type' => 'text',
                        'label' => $this->l('Address 2'),
                        'name' => 'ENVIAYA_SENDER_ADDRESS1',
                        'lang' => false,
                        'required' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Address 2'),
                        'name' => 'ENVIAYA_SENDER_ADDRESS2',
                        'lang' => false,
                        'required' => true,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Country'),
                        'name' => 'ENVIAYA_SENDER_COUNTRYCODE',
                        'required' => true,
                        'options' => array(
                            'query' => Country::getCountries($this->context->language->id, false),
                            'id' => 'iso_code',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('State Code'),
                        'name' => 'ENVIAYA_SENDER_STATECODE',
                        'hint' => $this->l('Sender State Code ').'. '.$this->l('If your country do not have provinces or states you can use "XX"'),
                        'lang' => false,
                        'required' => true,
                    ), 
                    array(
                        'type' => 'text',
                        'label' => $this->l('City'),
                        'name' => 'ENVIAYA_SENDER_CITY',
                        'lang' => false,
                        'required' => true,
                    ), 
                    array(
                        'type' => 'text',
                        'label' => $this->l('Phone'),
                        'name' => 'ENVIAYA_SENDER_PHONE',
                        'lang' => false,
                        'required' => true,
                    ), 
                    array(
                        'type' => 'group',
                        'label' => $this->l('Available Services'),
                        'name' => 'ENVIAYA_SERVICES',
                        'values' => $available_services,
                        'hint' => $this->l('EnviaYa available services for customer order shipping.'),
                        'required' => true
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Activate logs'),
                        'name' => 'ENVIAYA_ACCOUNT_LOGS',
                        'is_bool' => true,
                        'required' => true,
                        'desc' => $this->l('Review the output in var/logs/enviaya.log.'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                ),

                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );
        $data['ENVIAYA_ACCOUNT_SANDBOX'] = Configuration::get('ENVIAYA_ACCOUNT_SANDBOX');
        $data['ENVIAYA_ACCOUNT_LOGS'] = Configuration::get('ENVIAYA_ACCOUNT_LOGS');
        
        $data['ENVIAYA_SENDER_SANDBOXKEY'] = Configuration::get('ENVIAYA_SENDER_SANDBOXKEY');
        $data['ENVIAYA_SENDER_PRODUCTIONKEY'] = Configuration::get('ENVIAYA_SENDER_PRODUCTIONKEY');
        $data['ENVIAYA_SENDER_ACCOUNT'] = Configuration::get('ENVIAYA_SENDER_ACCOUNT');
        $data['ENVIAYA_SENDER_NAME'] = Configuration::get('ENVIAYA_SENDER_NAME');
        $data['ENVIAYA_SENDER_POSTCODE'] = Configuration::get('ENVIAYA_SENDER_POSTCODE');
        $data['ENVIAYA_SENDER_ADDRESS1'] = Configuration::get('ENVIAYA_SENDER_ADDRESS1');
        $data['ENVIAYA_SENDER_ADDRESS2'] = Configuration::get('ENVIAYA_SENDER_ADDRESS2');
        $data['ENVIAYA_SENDER_COUNTRYCODE'] = Configuration::get('ENVIAYA_SENDER_COUNTRYCODE');
        $data['ENVIAYA_SENDER_CITY'] = Configuration::get('ENVIAYA_SENDER_CITY');
        $data['ENVIAYA_SENDER_STATECODE'] = Configuration::get('ENVIAYA_SENDER_STATECODE');
        $data['ENVIAYA_SENDER_PHONE'] = Configuration::get('ENVIAYA_SENDER_PHONE');

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitConfig';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $data,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        return $helper->generateForm(array($fields_form));
    }
    /**
    * Get Destination Address
    */ 
    public function getDestinationAddress($idorder = null) 
    {
        if($idorder) {
            $order = new Order($idorder);
            $idAddress = $order->id_address_delivery;
        } else {
            $idAddress = $this->context->cart->id_address_delivery;
        }
        if ($idAddress) {
            $customerAddress = new Address(intval($idAddress));
            $address = [];
            $address['full_name'] = $customerAddress->firstname." ".$customerAddress->lastname;
            $address['country_code'] = Country::getIsoById($customerAddress->id_country);
            $address['postal_code'] = $customerAddress->postcode;
            $address['direction_1'] = $customerAddress->address1;
            $address['direction_2'] = $customerAddress->address2;
            $state = new State($customerAddress->id_state);
            $address['state_code'] = substr($state->iso_code, 0,2);
            $address['city'] = $customerAddress->city;
            $address['phone'] = $customerAddress->phone;
            return $address;
        } else {
            return false;
        }
    }
    /**
    * Get parcels Services
    */
    public function getParcels($idorder = null) {
        $parcels = [];
        $products = NULL;
        if($idorder) {
            $order = new Order($idorder);
            $products = $order->getProductsDetail();
        } else {
            $products = $this->context->cart->getProducts(true);
        }
        foreach ($products as $product) {
            $qty = 0;
            if($idorder) {
                $qty = $product['product_quantity'];
            } else {
                $qty = $product['quantity'];
            }
            $parcel = [];

            $weight = (float)$product['weight'];
            $height = (float)$product['height'];
            $width = (float)$product['width'];
            $depth = (float)$product['depth'];

            $parcel['quantity'] = $qty;
            $parcel['weight'] = ($weight) ? $weight : 1;
            $parcel['weight_unit'] = Configuration::get('PS_WEIGHT_UNIT');
            $parcel['length'] = ($depth) ? $depth : 1;
            $parcel['height'] =  ($height) ? $height : 1;
            $parcel['width'] = ($width) ? $width : 1;
            $parcel['dimension_unit'] =  Configuration::get('PS_DIMENSION_UNIT');
            $parcels[] = $parcel;
        }
        return $parcels;
    }
    /**
     * Admnin header js
     */
    public function hookActionAdminControllerSetMedia($params) 
    { 
        if ($this->context->controller->className == "Order" && Tools::isSubmit('id_order')) {
            if ($this->services->isEnviaYa(Tools::getValue('id_order'))) {
                $this->context->controller->addJS($this->_path.'views/assets/js/admin/enviaya.js');
            }
        }
    }
    /**
     * Add css or js file on header section
     */
    public function hookdisplayHeader($params)
    {
        $this->context->controller->addCSS($this->_path.'views/assets/css/module.css', 'all');
    }
    
}