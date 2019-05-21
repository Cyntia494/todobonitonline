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
* @package    Eter_Cookies
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
require_once dirname(__FILE__)."/src/autoload.php";
class Eter_Cookies extends Module
{
    
    public function __construct()
    {
        $this->name = 'eter_cookies';
        $this->author = 'ETERLABS S.A.S de C.V';
        $this->version = '1.0.0';
        $this->tab = 'advertising_marketing';
        $this->secure_key = Tools::encrypt($this->name);
        $this->author_uri = 'https://www.eterlabs.com';
        
        parent::__construct();
        $this->bootstrap = true;
        $this->displayName = $this->l('Cookie disclaimer Widget');
        $this->description = $this->l('Allow to Add cookie disclaimer in the page');
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
            && EterHelper::addModuleTabMenu('AdminMarketingContent','AdminCookies','Cookies',$this->name,40);
    }
    /**
     * Uninstall module
     */
    public function uninstall()
    {
        return parent::uninstall() 
            && EterHelper::removeModuleTabMenu('AdminCookies')
            && EterHelper::removeModuleTabMenu('AdminMarketingContent')
            && EterHelper::removeModuleTabMenu('ETERLABSMODULES');
    }
    /**
     * Add css or js file on header section
     */
    public function hookdisplayHeader($params)
    {
        $this->context->controller->addCSS($this->_path.'views/assets/css/module.css', 'all');
        $this->context->controller->addJS($this->_path.'views/assets/js/module.js', 'all');   
    }
    /**
     * Add css or js file on header section
     */
    public function hookdisplayAfterBodyOpeningTag($params)
    {
        if(!$this->context->cookie->acept_cookie) {
            $data = [];
            $data['backcolor'] = Configuration::get("COOKIE_SETTINGS_BACK_COLOR");
            $data['color'] = Configuration::get("COOKIE_SETTINGS_FONT_COLOR");
            $cms = $this->context->link->getCMSLink((int)Configuration::get('COOKIE_SETTINGS_TERMS'));
            $data['terms'] = "<a href=\"{$cms}\" target=\"_blank\">";
            $this->smarty->assign($data);
            return $this->display(__FILE__, 'views/templates/cookie-hook.tpl');
        }
    }
}