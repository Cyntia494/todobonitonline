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
* @package    Eter_Theme
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/
if (!defined('_PS_VERSION_')) {
    exit;
}
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
require_once dirname(__FILE__)."/src/autoload.php";
class Eter_Theme extends Module implements WidgetInterface
{
    /**
    * Create construct with module's information 
    */
    public function __construct()
    {
        $this->name = 'eter_theme';
        $this->author = 'ETERLABS S.A.S de C.V';
        $this->version = '1.0.0';
        $this->tab = 'content_management';
        $this->secure_key = Tools::encrypt($this->name);
        $this->author_uri = 'https://www.eterlabs.com';
        
        parent::__construct();
        $this->bootstrap = true;
        $this->displayName = $this->l('Theme Customizations');
        $this->description = $this->l('Module Base for themes and modules');
        $this->confirmUninstall = $this->l('Are you sure you want uninstall this module?');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }
    /**
    * Install module, in this function add and register hooks, and add new tab "Theme Options" in back office
    */
    public function install()
    {
        Configuration::updateValue('ETERTHEME_ADDONS_UP', true);
        return parent::install()
            && $this->registerHook('actionGetExtraMailTemplateVars')
            && $this->registerHook('displayHeader')
            && $this->registerHook('DisplayListingAddToCart')
            && $this->registerHook('displayAfterBodyOpeningTag')
            && $this->registerHook('displayFeaturedFlag')
            && EterHelper::addModuleTabMenu('ETERLABSMODULES','EterlabsTheme','Theme Options',$this->name,10,'format_paint')
            && EterHelper::addModuleTabMenu('EterlabsTheme','EterlabsConfig','Configuration',$this->name,10)
            && EterHelper::addModuleTabMenu('EterlabsTheme','CustomCss','Custom Css',$this->name,20)
            && EterHelper::addModuleTabMenu('EterlabsTheme','AdminDemos','Sample data',$this->name,30);
    }
    /**
    * Uninstall module, remove tab "Theme Options" from back office
    */
    public function uninstall()
    {
        return parent::uninstall()
            && EterHelper::removeModuleTabMenu('AdminDemos')
            && EterHelper::removeModuleTabMenu('CustomCss')
            && EterHelper::removeModuleTabMenu('EterlabsConfig')
            && EterHelper::removeModuleTabMenu('EterlabsTheme')
            && EterHelper::removeModuleTabMenu('ETERLABSMODULES');
    }
    /**
    * Print tag for Best seller products
    */
    public function hookDisplayFeaturedFlag($params) 
    {
        $idProduct = $params['product']['id_product'];
        $prefix = _DB_PREFIX_;
        $query ="SELECT * FROM {$prefix}product_sale WHERE id_product = {$idProduct}";
        return (bool)Db::getInstance()->getRow($query);
    }
    /**
    * Add tag manager tags into body
    */
    public function getWidgetVariables($hookName, array $configuration)
    {}
    /**
    * Add tag manager tags into body
    */
    public function renderWidget($hookName, array $configuration)
    {
        //$this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        $languages = Language::getLanguages(true, $this->context->shop->id);
        $currencies = Currency::getCurrencies(true, true);
        if (count($languages) > 1 || count($currencies) > 1) {
            return $this->display(__FILE__, '/views/templates/international.tpl');
        }
    }
    /**
    * Add tag manager tags into body
    */
    public function hookDisplayAfterBodyOpeningTag($params)
    {        
        if (Configuration::get('ETERTHEME_DEMO_ACTIVE')) {
            $this->context->smarty->assign('demo_url', Configuration::get('ETERTHEME_DEMO_URL'));
            $this->context->smarty->assign('logoeter',  $this->context->link->getMediaLink('/modules/eter_theme/eterlabs.jpg'));
            
            return $this->display(__FILE__, '/views/templates/demo.tpl');
        }
    }
    /**
    * Get extra variables to mail template
    */
    public function hookactionGetExtraMailTemplateVars($params) 
    {
        $params['extra_template_vars']["{theme_url}"] =   $this->context->link->getBaseLink().'themes/'.$this->context->shop->theme->getName().'/assets/img/mails/';
    }
    /**
     * Display product add to card 
     */
    public function hookDisplayListingAddToCart($params)
    { 
        if (Configuration::get('ETERTHEME_GENERAL_ADD') && !Configuration::isCatalogMode()) {
            $idProduct = $params['product']['id_product'];
            $product = new Product($idProduct);
            $groups = [];
            $attributes_groups = $product->getAttributesGroups($this->context->language->id);
            if (is_array($attributes_groups) && $attributes_groups) {
                $combination_images = $product->getCombinationImages($this->context->language->id);
                $combination_prices_set = array();
                foreach ($attributes_groups as $k => $row) {
                    // Color management
                    if (isset($row['is_color_group']) && $row['is_color_group'] && (isset($row['attribute_color']) && $row['attribute_color']) || (file_exists(_PS_COL_IMG_DIR_.$row['id_attribute'].'.jpg'))) {
                        $colors[$row['id_attribute']]['value'] = $row['attribute_color'];
                        $colors[$row['id_attribute']]['name'] = $row['attribute_name'];
                        if (!isset($colors[$row['id_attribute']]['attributes_quantity'])) {
                            $colors[$row['id_attribute']]['attributes_quantity'] = 0;
                        }
                        $colors[$row['id_attribute']]['attributes_quantity'] += (int) $row['quantity'];
                    }
                    if (!isset($groups[$row['id_attribute_group']])) {
                        $groups[$row['id_attribute_group']] = array(
                            'group_name' => $row['group_name'],
                            'name' => $row['public_group_name'],
                            'group_type' => $row['group_type'],
                            'default' => -1,
                        );
                    }

                    $groups[$row['id_attribute_group']]['attributes'][$row['id_attribute']] = array(
                        'name' => $row['attribute_name'],
                        'html_color_code' => $row['attribute_color'],
                        'texture' => (@filemtime(_PS_COL_IMG_DIR_.$row['id_attribute'].'.jpg')) ? _THEME_COL_DIR_.$row['id_attribute'].'.jpg' : '',
                        'selected' => (isset($product_for_template['attributes'][$row['id_attribute_group']]['id_attribute']) && $product_for_template['attributes'][$row['id_attribute_group']]['id_attribute'] == $row['id_attribute']) ? true : false,
                    );

                    //$product.attributes.$id_attribute_group.id_attribute eq $id_attribute
                    if ($row['default_on'] && $groups[$row['id_attribute_group']]['default'] == -1) {
                        $groups[$row['id_attribute_group']]['default'] = (int) $row['id_attribute'];
                    }
                    if (!isset($groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']])) {
                        $groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']] = 0;
                    }
                    $groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']] += (int) $row['quantity'];

                    $this->combinations[$row['id_product_attribute']]['attributes_values'][$row['id_attribute_group']] = $row['attribute_name'];
                    $this->combinations[$row['id_product_attribute']]['attributes'][] = (int) $row['id_attribute'];
                    $this->combinations[$row['id_product_attribute']]['price'] = (float) $row['price'];

                    // Call getPriceStatic in order to set $combination_specific_price
                    if (!isset($combination_prices_set[(int) $row['id_product_attribute']])) {
                        $combination_specific_price = null;
                        Product::getPriceStatic((int) $product->id, false, $row['id_product_attribute'], 6, null, false, true, 1, false, null, null, null, $combination_specific_price);
                        $combination_prices_set[(int) $row['id_product_attribute']] = true;
                        $this->combinations[$row['id_product_attribute']]['specific_price'] = $combination_specific_price;
                    }
                    $this->combinations[$row['id_product_attribute']]['ecotax'] = (float) $row['ecotax'];
                    $this->combinations[$row['id_product_attribute']]['weight'] = (float) $row['weight'];
                    $this->combinations[$row['id_product_attribute']]['quantity'] = (int) $row['quantity'];
                    $this->combinations[$row['id_product_attribute']]['reference'] = $row['reference'];
                    $this->combinations[$row['id_product_attribute']]['unit_impact'] = $row['unit_price_impact'];
                    $this->combinations[$row['id_product_attribute']]['minimal_quantity'] = $row['minimal_quantity'];
                    if ($row['available_date'] != '0000-00-00' && Validate::isDate($row['available_date'])) {
                        $this->combinations[$row['id_product_attribute']]['available_date'] = $row['available_date'];
                        $this->combinations[$row['id_product_attribute']]['date_formatted'] = Tools::displayDate($row['available_date']);
                    } else {
                        $this->combinations[$row['id_product_attribute']]['available_date'] = $this->combinations[$row['id_product_attribute']]['date_formatted'] = '';
                    }

        
                }
            }
            $current_selected_attributes = array();
            $count = 0;
            foreach ($groups as &$group) {
                $count++;
                if ($count > 1) {
                    //find attributes of current group, having a possible combination with current selected
                    $id_product_attributes = array(0);
                    $query = 'SELECT pac.`id_product_attribute`
                        FROM `'._DB_PREFIX_.'product_attribute_combination` pac
                        INNER JOIN `'._DB_PREFIX_.'product_attribute` pa ON pa.id_product_attribute = pac.id_product_attribute
                        WHERE id_product = '.$product->id.' AND id_attribute IN ('.implode(',', array_map('intval', $current_selected_attributes)).')
                        GROUP BY id_product_attribute
                        HAVING COUNT(id_product) = '.count($current_selected_attributes);
                    if ($results = Db::getInstance()->executeS($query)) {
                        foreach ($results as $row) {
                            $id_product_attributes[] = $row['id_product_attribute'];
                        }
                    }
                    $id_attributes = Db::getInstance()->executeS('SELECT `id_attribute` FROM `'._DB_PREFIX_.'product_attribute_combination` pac2
                        WHERE `id_product_attribute` IN ('.implode(',', array_map('intval', $id_product_attributes)).')
                        AND id_attribute NOT IN ('.implode(',', array_map('intval', $current_selected_attributes)).')');
                    foreach ($id_attributes as $k => $row) {
                        $id_attributes[$k] = (int)$row['id_attribute'];
                    }
                    foreach ($group['attributes'] as $key => $attribute) {
                        if (!in_array((int)$key, $id_attributes)) {
                            unset($group['attributes'][$key]);
                            unset($group['attributes_quantity'][$key]);
                        }
                    }
                }
                //find selected attribute or first of group
                $index = 0;
                $current_selected_attribute = 0;
                foreach ($group['attributes'] as $key => $attribute) {
                    if ($index === 0) {
                        $current_selected_attribute = $key;
                    }
                    if ($attribute['selected']) {
                        $current_selected_attribute = $key;
                        break;
                    }
                }
                if ($current_selected_attribute > 0) {
                    $current_selected_attributes[] = $current_selected_attribute;
                }
            }
            $this->context->smarty->assign(['groups' => $groups,'product' => $params['product']]);
            $html = $this->context->smarty->fetch(_PS_MODULE_DIR_.'eter_theme/views/templates/product-variant.tpl');
            return $html;
        }
    }

    /**
    * Add css or js file on header section
    */
    public function hookdisplayHeader($params)
    {
        $base = $this->_path.'views/assets/';           
        $this->context->controller->addCSS($base.'css/module.css', 'all');
        $this->context->controller->addCSS($base.'css/demo.css', 'all');
        if (Configuration::get('ETERTHEME_COLOR_ACTIVE')) {
            $this->context->controller->addCSS($base.'css/custom.css', 'all');
        }
        $this->context->smarty->assign('eter_copyright', Configuration::get('ETERTHEME_THEME_COPYRIGHT'));
        $this->context->smarty->assign('show_facet_quantities', Configuration::get('PS_LAYERED_SHOW_QTIES'));
        
        $this->context->controller->addCSS($base.'css/csseditor.css', 'all');
        $this->context->controller->addJS($base.'js/module.js', 'all');
    }

}