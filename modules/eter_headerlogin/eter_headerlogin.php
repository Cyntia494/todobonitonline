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
* @package    Eter_HeaderLogin
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/

use PrestaShop\PrestaShop\Adapter\Cart\CartPresenter;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
* Import class
*/
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
require_once dirname(__FILE__)."/src/autoload.php";

class Eter_Headerlogin extends Module implements WidgetInterface
{
    /**
    * Create construct with module's information 
    */
    public function __construct()
    {
        $this->name = 'eter_headerlogin';
        $this->author = 'ETERLABS S.A.S de C.V';
        $this->version = '1.0.0';
        $this->tab = 'front_office_features';
        $this->secure_key = Tools::encrypt($this->name);
        $this->author_uri = 'https://www.eterlabs.com';

        parent::__construct();
        $this->bootstrap = true;
        $this->displayName = $this->l('Header Login');
        $this->description = $this->l('Add Login form in header');
        $this->confirmUninstall = $this->l('Are you sure you want uninstall this module?');
        $this->ps_versions_compliancy = array('min' => '1.7.2.0', 'max' => _PS_VERSION_);
    }
    /**
    * Install module
    */
    public function install()
    {
        return parent::install() 
            && $this->registerHook('displayNav2') 
            && $this->registerHook('displayHeader');
    }
    /**
    * Render widget hook, display tpl to home page
    */
    public function renderWidget($hookName = null, array $configuration = [])
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        return $this->display(__FILE__, 'views/templates/login.tpl');
    }
    /**
    * Get widget data to show in home page
    */
    public function getWidgetVariables($hookName, array $params)
    {
        $form = new CustomerLoginForm(
            $this->context->smarty,
            $this->context,
            $this->getTranslator(),
            new CustomerLoginFormatter($this->getTranslator()),
                $this->context->controller->getTemplateVarUrls()
        );
        return $form->getTemplateVariables();
    }
    /**
    * Add css or js file on header section
    */
    public function hookdisplayHeader($params)
    {
        $theme_author = Context::getContext()->shop->theme->get('author');
        if($theme_author['name'] != 'eterlabs') {
            $this->context->controller->addJS($this->_path.'js/headerlogin.js', 'all');
            $this->context->controller->addCSS($this->_path.'views/assets/css/module.css', 'all');
        }
    }
}