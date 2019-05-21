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
* @package    Eter_Minicart
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/
if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Adapter\Cart\CartPresenter;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
require_once dirname(__FILE__)."/src/autoload.php";
class Eter_Minicart extends Module implements WidgetInterface
{
    /**
     * Create construct with module's information 
     */
    public function __construct()
    {
        $this->name = 'eter_minicart';
        $this->author = 'ETERLABS S.A.S de C.V';
        $this->version = '1.0';
        $this->tab = 'front_office_features';
        $this->secure_key = Tools::encrypt($this->name);
        $this->author_uri = 'https://www.eterlabs.com';
        
        parent::__construct();
        $this->bootstrap = true;
        $this->displayName = $this->l('Minicart');
        $this->description = $this->l('Add a minicart in header section');
        $this->confirmUninstall = $this->l('Are you sure you want uninstall this module?');
        $this->ps_versions_compliancy = array('min' => '1.7.2.0', 'max' => _PS_VERSION_);
    }
    /**
     * Install module, in this function add and register hooks
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
        if (!Configuration::isCatalogMode()) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
            return $this->display(__FILE__, 'views/templates/minicart.tpl');
        }
    }
    /**
     * Render Success cart
     */
    public function renderSuccessCart($hookName, array $params)
    {
        if (Configuration::isCatalogMode()) {
            return;
        }
        $this->smarty->assign($this->getWidgetVariables($hookName, $params));
        return $this->fetch('module:ps_shoppingcart/ps_shoppingcart.tpl');
    }
    /**
     * Render modal cart
     */
    public function renderModal(Cart $cart, $id_product, $id_product_attribute)
    {
        $data = (new CartPresenter)->present($cart);
        $product = null;
        foreach ($data['products'] as $p) {
            if ($p['id_product'] == $id_product && $p['id_product_attribute'] == $id_product_attribute) {
                $product = $p;
                break;
            }
        }

        $this->smarty->assign(array(
            'product' => $product,
            'cart' => $data,
            'cart_url' => $this->getCartSummaryURL(),
        ));

        return $this->fetch('module:ps_shoppingcart/modal.tpl');
    }
    /**
     * Get Cart url
     */
    private function getCartSummaryURL()
    {
        return $this->context->link->getPageLink(
            'cart',
            null,
            $this->context->language->id,
            array(
                'action' => 'show'
            ),
            false,
            null,
            true
        );
    }
    /**
     * Get widget data to show in home page
     */
    public function getWidgetVariables($hookName, array $params)
    {
        $cart_url = $this->getCartSummaryURL();

        return array(
            'cart' => (new CartPresenter)->present(isset($params['cart']) ? $params['cart'] : $this->context->cart),
            'refresh_url' => $this->context->link->getModuleLink('ps_shoppingcart', 'ajax', array(), null, null, null, true),
            'cart_url' => $cart_url
        );
    }
    /**
     * Get variables
     */
    public function getControllerVariables()
    {
        $cart_url = $this->getCartSummaryURL();
        return array(
            'cart' => (new CartPresenter)->present($this->context->cart),
            'refresh_url' => $this->context->link->getModuleLink('ps_shoppingcart', 'ajax', array(), null, null, null, true),
            'cart_url' => $cart_url
        );
    }
    /**
     * Add css or js file on header section
     */
    public function hookdisplayHeader($params)
    {
        $this->context->controller->addJS($this->_path.'js/minicart.js', 'all');
        $this->context->controller->addCSS($this->_path.'views/assets/css/module.css', 'all');
    }
}