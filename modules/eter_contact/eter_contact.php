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
* @package    Eter_Contact
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
require_once dirname(__FILE__)."/src/autoload.php";
class Eter_Contact extends Module
{
    
    public function __construct()
    {
        $this->name = 'eter_contact';
        $this->author = 'ETERLABS S.A.S de C.V';
        $this->version = '1.0.0';
        $this->tab = 'advertising_marketing';
        $this->secure_key = Tools::encrypt($this->name);
        $this->author_uri = 'https://www.eterlabs.com';
        
        parent::__construct();
        $this->bootstrap = true;
        $this->displayName = $this->l('Contact Widget');
        $this->description = $this->l('Allow to Add contact information Contact form, whatsapp, facebook and telephone');
        $this->confirmUninstall = $this->l('Are you sure you want uninstall this module?');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }
    /**
     * Install module
     */
    public function install()
    {
        /* Adds Module */
        return parent::install() 
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayAfterBodyOpeningTag')
            && EterHelper::addModuleTabMenu('ETERLABSMODULES','AdminMarketingContent','Marketing','eter_marketing',100,'trending_up')
            && EterHelper::addModuleTabMenu('AdminMarketingContent','AdminContact','Contact',$this->name,30);
    }
    /**
     * Uninstall module
     */
    public function uninstall()
    {
        return parent::uninstall() 
            && EterHelper::removeModuleTabMenu('AdminContact')
            && EterHelper::removeModuleTabMenu('AdminMarketingContent')
            && EterHelper::removeModuleTabMenu('ETERLABSMODULES');
    }
    /**
     * Add css or js file on header section
     */
    public function hookdisplayHeader($params)
    {
        $this->context->controller->addCSS($this->_path.'views/assets/css/module.css', 'all'); 
        $this->context->controller->addCSS($this->_path.'views/assets/css/colors.css', 'all');        
        $this->context->controller->addJS($this->_path.'views/assets/js/module.js', 'all');   
    }
    /**
     * Add css or js file on header section
     */
    public function hookdisplayAfterBodyOpeningTag($params)
    {
        $data = [];
        if ($facebook = Configuration::get("CONTACT_FACEBOOK_ACCOUNT")) {
            $data['facebook'] = "https://m.me/{$facebook}/";
        } 
        if ($whatsapp = Configuration::get("CONTACT_WHATSAPP_ACCOUNT")) {
            $data['whatsapp'] = "https://api.whatsapp.com/send?phone={$whatsapp}";
        } 
        if ($phone = Configuration::get("CONTACT_PHONE_ACCOUNT")) {
            $data['phone'] = $phone;
        } 
        if ($contact = Configuration::get("CONTACT_FORM_EMAILS")) {
            $template = "module:eter_contact/views/templates/contact-form.tpl";
            $address = $this->context->shop->getAddress();
            $form = [
                'address' => AddressFormat::generateAddress($address, array(), ','),
                'phone' => Configuration::get('PS_SHOP_PHONE'),
                'email' => Configuration::get('PS_SHOP_EMAIL'),
            ];
            $form['image'] = $this->context->link->getMediaLink('/modules/eter_contact/views/assets/img/backround-contact.png');
            $form['mail'] = $this->context->link->getMediaLink('/modules/eter_contact/views/assets/img/mail.png');
            $form['spinner'] = $this->context->link->getMediaLink('/modules/eter_contact/views/assets/img/spinner.gif');
            $form['thanks'] = $this->context->link->getMediaLink('/modules/eter_contact/views/assets/img/thanks.png');
            
            $this->context->smarty->assign($form);
            $json['form'] = $this->context->smarty->fetch($template);
            $data['contact'] = json_encode($json);
        } 
        $data['active'] = (bool)count($data);
        $this->smarty->assign($data);
        return $this->display(__FILE__, 'views/templates/contact-hook.tpl');
    }
}