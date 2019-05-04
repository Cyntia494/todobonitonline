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
* @package    Eter_Icons
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
* Import classes
*/
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
include_once(_PS_MODULE_DIR_.'eter_icons/classes/etericons.php');
require_once dirname(__FILE__)."/src/autoload.php";
class Eter_Icons extends Module implements WidgetInterface
{
    /**
    * Define globlal variables
    */
    protected $_html = '';
    protected $templateFile;
    public $_icons = null; 
    /**
    * Create construct with module's information 
    */
    public function __construct()
    {
        $this->name = 'eter_icons';
        $this->author = 'ETERLABS S.A.S de C.V';
        $this->version = '1.0.0';
        $this->tab = 'front_office_features';
        $this->secure_key = Tools::encrypt($this->name);
        $this->author_uri = 'https://www.eterlabs.com';

        parent::__construct();
        $this->bootstrap = true;
        $this->displayName = $this->l('Footer Icons');
        $this->description = $this->l('allow to add icons in footer section');
        $this->confirmUninstall = $this->l('Are you sure you want uninstall this module?');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
        $this->_icons = new EterIcons();
    }
    /**
    * Install module, in this function add and register hooks, and add new tab "Footer Icons" in back office
    */
    public function install()
    {
        /* Adds Module */
        return parent::install() 
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayFooterAfter')
            && EterHelper::addModuleTabMenu('ETERLABSMODULES','AdminHomeContent','Page Content','eter_homepage',50,'home')
            && EterHelper::addModuleTabMenu('AdminHomeContent','AdminEterIcons','Footer Icons',$this->name,110)
            && $this->_icons->createTables();
    }
    /**
    * Uninstall module, remove tab "Footer Icons" from back office
    */
    public function uninstall()
    {
        /* Deletes Module */
        return parent::uninstall()
            && EterHelper::removeModuleTabMenu('AdminEterIcons')
            && EterHelper::removeModuleTabMenu('AdminHomeContent')
            && EterHelper::removeModuleTabMenu('ETERLABSMODULES')
            && $this->_icons->deleteTables();
    }
    /**
    * Add css or js file on header section
    */
    public function hookdisplayHeader($params)
    {
        $theme_author = Context::getContext()->shop->theme->get('author');
        $this->context->controller->addCSS($this->_path.'views/assets/css/module.css', 'all');  
    }
    /**
    * Render widget hook, display tpl to home page
    */
    public function renderWidget($hookName = null, array $configuration = [])
    {
        $cache_id = 'eter_icons';
        $cache_id .= Context::getContext()->language->id;
        $cache = Cache::getInstance();
        $html = "";
        if ((!_PS_CACHE_ENABLED_) || (_PS_CACHE_ENABLED_ && !$cache->exists($cache_id))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
            $html = $this->display(__FILE__, 'views/templates/front/icons.tpl');
            $cache->set($cache_id, $html);
        }
        $cacheHtml = $cache->get($cache_id);
        return ($cacheHtml) ? $cacheHtml : $html;  
    }
    /**
    * Get widget data to show in home page
    */
    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $data = $this->_icons->getData();
        $icons = [];
        foreach ($data as $key => &$row) {
            if ($row["active"]) {
                $row["image"] = $this->context->link->getMediaLink('/modules/eter_icons/images/'.$row['image']);
                $icons[] = $row;
            }
        }
        return ['data' => $icons];
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