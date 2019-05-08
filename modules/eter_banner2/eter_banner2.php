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
* @package    Eter_Banner2
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
require_once dirname(__FILE__)."/src/autoload.php";
class Eter_Banner2 extends Module implements WidgetInterface
{
    /**
    * Define globlal variables
    */
    protected $_html = '';
    /**
    * Create construct with module's information 
    */
    public function __construct()
    {
        $this->name = 'eter_banner2';
        $this->author = 'ETERLABS S.A.S de C.V';
        $this->version = '1.0.0';
        $this->tab = 'advertising_marketing';
        $this->secure_key = Tools::encrypt($this->name);
        $this->author_uri = 'https://www.eterlabs.com';
        
        parent::__construct();
        $this->bootstrap = true;
        $this->displayName = $this->l('Image Banner 2');
        $this->description = $this->l('Adds a clickable banner on the homepage of your ecommerce.');
        $this->confirmUninstall = $this->l('Are you sure you want uninstall this module?');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }
    /**
    * Install module, in this function add and register hooks, and add new tab "Home Banner 2" in back office
    */
    public function install()
    {
        Configuration::updateValue('BANNER2_IMAGE_NAME', "default/banner2.jpg");
        Configuration::updateValue('BANNER2_IMAGE_NAME_MOBILE', "default/banner2_mobile.jpg");
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            Configuration::updateValue('BANNER2_URL_L'.$lang['id_lang'], "#");
        }
        /* Adds Module */
        return parent::install() 
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayHome')
            && EterHelper::addModuleTabMenu('ETERLABSMODULES','AdminHomeContent','Page Content','eter_homepage',50,'home')
            && EterHelper::addModuleTabMenu('AdminHomeContent','AdminEterBanner2','Banner 2',$this->name,30);

    }
    /**
    * Uninstall module, remove tab "Home Banner 2" from back office
    */
    public function uninstall()
    {
        /* Deletes Module */
        Configuration::deleteByName('BANNER2_IMAGE_NAME');
        Configuration::deleteByName('BANNER2_IMAGE_NAME_MOBILE');
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            Configuration::deleteByName('BANNER2_URL_L'.$lang['id_lang']);
        }
        return parent::uninstall()
            && EterHelper::removeModuleTabMenu('AdminEterBanner2')
            && EterHelper::removeModuleTabMenu('AdminHomeContent')
            && EterHelper::removeModuleTabMenu('ETERLABSMODULES');  
    }
    /**
    * Add css or js file on header section
    */
    public function hookdisplayHeader($params)
    {
        if($this->context->controller->php_self == "index") {
            $this->context->controller->addCSS($this->_path.'views/assets/css/module.css', 'all');        
        }
    }
    /**
    * Render widget hook, display tpl to home page
    */
    public function renderWidget($hookName = null, array $configuration = [])
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        return $this->display(__FILE__, 'views/templates/front/banner.tpl');
    }
    /**
    * Get widget data to show in home page
    */
    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $lang = $this->context->language->id;
        $image = Configuration::get('BANNER2_IMAGE_NAME');
        $image_mobile = Configuration::get('BANNER2_IMAGE_NAME_MOBILE');
        $src = $this->context->link->getMediaLink('/modules/eter_banner2/images/'.$image);
        $src_mobile = $this->context->link->getMediaLink('/modules/eter_banner2/images/'.$image_mobile);
        $data['url'] = Configuration::get('BANNER2_URL_L'.$lang);
        $data['image'] = $src;
        $data['image_mobile'] = $src_mobile;
        $data['mobile'] = $this->context->isMobile();
        return ['data' => $data];
    }
    /**
    * This function validate image features , return false if the image do not comply the correct features (width, height, max size)
    */
    public function validateImage($name,$path,$width,$height,$validateInput,$max_size=500000) 
    {
        if($validateInput) {
            if (empty($_FILES[$name]['name'])) {
                return $this->l('Please select and image for ').$name;
            }
        }
        if (!empty($_FILES[$name]['name'])) {
            // Check image validity
            if ($error = ImageManager::validateUpload($_FILES[$name], Tools::getMaxUploadSize($max_size))) {
                return $error;
            }
            // Check image size
            $size = getimagesize($_FILES[$name]['tmp_name']);
            if($size[0] != $width && $size[1] != $height) {
                return $this->l('Please review the images width and height');
            }
            // Check directory
            if (!file_exists($path)) {
                $success = @mkdir($path, 0775, true);
                $chmod = @chmod($path, 0775);
                if (!$success && !$chmod) {
                    return $this->l('Please review access in the server to upload files');
                }
            }
            return false;
        } else {
            return false;
        }
    }
    /**
     * Upload image
     */
    public function uploadImage($fileName, $path) {
        try {
            $chmod = @chmod($path, 0775);
            $ext = pathinfo($_FILES[$fileName]['name'], PATHINFO_EXTENSION);
            $name = Tools::link_rewrite(pathinfo($_FILES[$fileName]['name'], PATHINFO_FILENAME));
            $uploadName = $name.'.'.$ext;

            if (move_uploaded_file($_FILES[$fileName]['tmp_name'], $path.$uploadName)) {
                return $uploadName;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
  
}