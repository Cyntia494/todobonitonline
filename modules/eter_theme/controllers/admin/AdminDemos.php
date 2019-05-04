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

class AdminDemosController extends ModuleAdminController
{
    /**
    * Define globlal variables
    */
    public $bootstrap = true;
    public $uploadImageDir;
    /**
    * Initialize controller content
    */
    public function init()
    {
        parent::init();
        if (!defined('_ETER_IMPORT_DIR_')) {
            define('_ETER_IMPORT_DIR_','http://demos.eterlabs.com/');
        }
        $this->steps = new InstallSteps($this->context->link);
        
    }
    /**
    * Initialize controller content
    */
    public function initContent()
    {
        parent::initContent();
        $_errors = [];
        $html = '';
        if(Tools::isSubmit('ajax') && Tools::isSubmit('getInstallSteps')){
            Configuration::updateValue("DEMO_CURRENT_THEME",Tools::getValue('installTheme'));
            $data = $this->steps->getInstallSteps(Tools::getValue('installTheme'));
            $data = json_encode($data);
            header('Content-Type: application/json');
            $this->ajaxDie($data);
        } else if(Tools::isSubmit('ajax') && Tools::isSubmit('installModule')){
            $data = $this->steps->installModuleStep(Tools::getValue('installModule'),Tools::getValue('installTheme'));
            $data = json_encode($data);
            header('Content-Type: application/json');
            $this->ajaxDie($data);
        } else if(Tools::isSubmit('ajax') && Tools::isSubmit('cleanCategories')) {
            $data = $this->steps->cleanCategories();
            $data = json_encode($data);
            header('Content-Type: application/json');
            $this->ajaxDie($data);
        } else if(Tools::isSubmit('ajax') && Tools::isSubmit('installCategory')) {
            $category = Tools::getValue('data');
            $data = $this->steps->installCategoryStep($category);
            $data = json_encode($data);
            header('Content-Type: application/json');
            $this->ajaxDie($data);
        } else if(Tools::isSubmit('ajax') && Tools::isSubmit('cleanProducts')) {
            $data = $this->steps->cleanProducts();
            $data = json_encode($data);
            header('Content-Type: application/json');
            $this->ajaxDie($data);
        } else if(Tools::isSubmit('ajax') && Tools::isSubmit('installFeature')) {
            $feature = Tools::getValue('data');
            $data = $this->steps->installFeature($feature);
            $data = json_encode($data);
            header('Content-Type: application/json');
            $this->ajaxDie($data);
        } else if(Tools::isSubmit('ajax') && Tools::isSubmit('installAttribute')) {
            $feature = Tools::getValue('data');
            $data = $this->steps->installAttribute($feature);
            $data = json_encode($data);
            header('Content-Type: application/json');
            $this->ajaxDie($data);
        } else if(Tools::isSubmit('ajax') && Tools::isSubmit('installProduct')) {
            $product = Tools::getValue('data');
            $data = $this->steps->installProduct($product);
            $data = json_encode($data);
            header('Content-Type: application/json');
            $this->ajaxDie($data);
        } else if(Tools::isSubmit('ajax') && Tools::isSubmit('installAccessories')) {
            $accessory = Tools::getValue('data');
            $data = $this->steps->installAccessories($accessory);
            $data = json_encode($data);
            header('Content-Type: application/json');
            $this->ajaxDie($data);
        }  
        $themesJson = @file_get_contents(_ETER_IMPORT_DIR_.'themes.json');
        $themes = $this->filterThemesOptions(json_decode($themesJson,true));
        $stepsUrl = $this->context->link->getAdminLink('AdminDemos',true,[],['ajax'=>1,'getInstallSteps'=>1]);
        $data['stepsUrl'] = $stepsUrl;
        $data['themes'] = $themes;
        $data['currenttheme'] = Configuration::get("DEMO_CURRENT_THEME");
        $data['loader'] = _PS_BASE_URL_.'/modules/eter_theme/views/assets/img/loader.gif';
        $this->context->smarty->assign($data);
        $this->context->controller->addCSS(_PS_MODULE_DIR_.'eter_theme/views/assets/css/admin/admin.css', 'all');
        $this->context->controller->addJS(_PS_MODULE_DIR_.'eter_theme/views/assets/js/install.js', 'all');
        $html .= $this->context->smarty->fetch(_PS_MODULE_DIR_.'eter_theme/views/templates/loader.tpl');
        //$html .= $this->renderForm(); 
        $before_html = EterHelper::getInfo($this->module);
        $this->context->smarty->assign(array(
            'content' => $before_html.$html,
        ));
    }
    /**
    * Filter theme options
    */
    public function filterThemesOptions($options) {
        $optionList = [];
        $haveUrleter = $this->haveUrleter();
        if ($options) {
            foreach ($options as $option) {
                if($haveUrleter){
                    $optionList [] = $option;
                }
                else{
                    if($option['production'] == 'true'){
                        $optionList [] = $option;
                    }
                }
            }
        }
        return $optionList;
    }
    /**
    * Validate url
    */
    public function haveUrleter (){
        if(strpos($_SERVER['HTTP_HOST'],'eterlabs.com')){
            return true;
        }
        return true;
    }
    /**
    * Display progess image
    */
    public function addCssEditor()
    {
        //$loader = _PS_BASE_URL_.'/modules/eter_imagesimport/views/assets/img/loader.gif';
        $this->context->controller->addCSS(_PS_MODULE_DIR_.'eter_theme/lib/addon/hint/show-hint.css', 'all');
        $this->context->controller->addCSS(_PS_MODULE_DIR_.'eter_theme/lib/codemirror.css', 'all');
        $this->context->controller->addJS(_PS_MODULE_DIR_.'eter_theme/lib/codemirror.js', 'all');

        $this->context->controller->addJS(_PS_MODULE_DIR_.'eter_theme/views/assets/js/admin.js', 'all');
        $this->context->controller->addJS(_PS_MODULE_DIR_.'eter_theme/lib/css.js', 'all');
        $this->context->controller->addJS(_PS_MODULE_DIR_.'eter_theme/addon/hint/show-hint.js', 'all');
        $this->context->controller->addJS(_PS_MODULE_DIR_.'eter_theme/addon/hint/css-hint.js', 'all');
        $cssFile = _PS_MODULE_DIR_.'eter_theme/views/assets/css/csseditor.css';
        try {
            $cssContent = file_get_contents($cssFile);
        } catch (Exception $e) {
            $cssContent = "";
        }

        $data['action'] = $this->context->link->getAdminLink('CustomCss', true);
        $data['cssContent'] = $cssContent;
        $this->context->smarty->assign($data);
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'eter_theme/views/templates/editor.tpl');
    }
    /**
    * Validate form values
    */
    public function postFormProcess($content) 
    {   
        $cssFile = _PS_MODULE_DIR_.'eter_theme/views/assets/css/csseditor.css';
        return file_put_contents($cssFile, $content);
    }


}
