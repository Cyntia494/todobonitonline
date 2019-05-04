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
* @package    Eter_ImageSlider
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
include_once(_PS_MODULE_DIR_.'eter_imageslider/classes/Eter_HomeSlide.php');
require_once dirname(__FILE__)."/src/autoload.php";
class Eter_ImageSlider extends Module implements WidgetInterface
{

	/**
     * Create  global variables
     */
	protected $_html = '';
    protected $default_width = 779;
    protected $default_speed = 5000;
    protected $default_pause_on_hover = 1;
    protected $default_wrap = 1;
    protected $templateFile;
    
    /**
     * Construct
     */
    public function __construct()
    {
        $this->name = 'eter_imageslider';
        $this->author = 'ETERLABS S.A.S de C.V';
        $this->version = '1.0.0';
        $this->tab = 'slideshows';
        $this->secure_key = Tools::encrypt($this->name);
        $this->author_uri = 'https://www.eterlabs.com';
        
        parent::__construct();
        $this->bootstrap = true;
        $this->displayName = $this->l('Image Slider');
        $this->description = $this->l('Allow to Add a Slider in home page');
        $this->confirmUninstall = $this->l('Are you sure you want uninstall this module?');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
        $this->templateFile = 'module:eter_imageslider/views/templates/hook/slider.tpl';
    }

    /**
     * Function install
     */
    public function install()
    {
        $res = true;
        Configuration::updateValue('PS_USE_HTMLPURIFIER',false);
        if (parent::install()
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayHome')
            && $this->registerHook('actionShopDataDuplication')
            && EterHelper::addModuleTabMenu('ETERLABSMODULES','AdminHomeContent','Page Content','eter_homepage',50,'home')
            && EterHelper::addModuleTabMenu('AdminHomeContent','AdminEterImageSlider','Image Slider',$this->name,1)
            ){
            
            /* Creates tables */
            $res &= $this->createTables();

            /* Adds samples */
            if ($res) {
                $this->installSamples();
            }
            return (bool)$res;
        }
        return false;

	}

	/**
     * Function unistall
     */
    public function uninstall()
    {
        /* Deletes Module */
        if (parent::uninstall() 
                && EterHelper::removeModuleTabMenu('AdminEterImageSlider')
                && EterHelper::removeModuleTabMenu('AdminHomeContent')
                && EterHelper::removeModuleTabMenu('ETERLABSMODULES')) {

            /* Deletes tables */
            $res = $this->deleteTables();

            /* Unsets configuration */
            $res &= Configuration::deleteByName('ETER_HOMESLIDER_SPEED');
            $res &= Configuration::deleteByName('ETER_HOMESLIDER_PAUSE_ON_HOVER');
            $res &= Configuration::deleteByName('ETER_HOMESLIDER_WRAP');

            return (bool)$res;
        }

        return false;
    }

	/**
     * Adds samples
     */
    protected function installSamples()
    {
        $languages = Language::getLanguages(false);
        for ($i = 1; $i <= 3; ++$i) {
            $slide = new Eter_HomeSlide();
            $slide->position = $i;
            $slide->active = 1;
            $slide->side = $i;
            $slide->background = 'default/sample-'.$i.'.png';
            $slide->image = 'default/image.png';
            foreach ($languages as $language) {
                $slide->title[$language['id_lang']] = 'Eureka</br><span>We have the formula<span>';
                $slide->description[$language['id_lang']] = 'Creating, Re-Designing and Innovating your eCommerce has never been so easy.';
                $slide->legend[$language['id_lang']] = 'More Information';
                $slide->url[$language['id_lang']] = '#';
            }
            $slide->add();
        }
    }

	/**
     * Creates tables
     */
    protected function createTables()
    {
        /* Slides */
        $res = (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_homeslider` (
                `id_homeslider_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_homeslider_slides`, `id_shop`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        /* Slides configuration */
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_homeslider_slides` (
              `id_homeslider_slides` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `position` int(10) unsigned NOT NULL DEFAULT \'0\',
              `side` int(10) unsigned NOT NULL DEFAULT \'0\',
              `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
              `background` varchar(255) NOT NULL,
              `image` varchar(255) NOT NULL,
              PRIMARY KEY (`id_homeslider_slides`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        /* Slides lang configuration */
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_homeslider_slides_lang` (
              `id_homeslider_slides` int(10) unsigned NOT NULL,
              `id_lang` int(10) unsigned NOT NULL,
              `title` varchar(255) NOT NULL,
              `description` text NOT NULL,
              `legend` varchar(255) NOT NULL,
              `url` varchar(255) NOT NULL,
              PRIMARY KEY (`id_homeslider_slides`,`id_lang`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        return $res;
    }
    /**
     * deletes tables
     */
    protected function deleteTables()
    {	
        return Db::getInstance()->execute('
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'eter_homeslider`, `'._DB_PREFIX_.'eter_homeslider_slides`, `'._DB_PREFIX_.'eter_homeslider_slides_lang`;
        ');
    }
    /**
     * Add css or js file on header section
     */
    public function hookdisplayHeader($params)
    {
        $this->context->controller->registerStylesheet('imagesslidercss', 'modules/'.$this->name.'/views/assets/css/homeslider.css');
        $this->context->controller->registerStylesheet('royalslidercss', 'modules/'.$this->name.'/views/assets/css/royalslider.css');
        $this->context->controller->registerJavascript('royasliderjs', 'modules/'.$this->name.'/views/assets/js/royalslider.js');
        $this->context->controller->registerJavascript('easing', 'modules/'.$this->name.'/views/assets/js/easing.js');
        $this->context->controller->registerJavascript('homeslider', 'modules/'.$this->name.'/views/assets/js/homeslider.js');
    }
    /**
     * Add render widget
     */
    public function renderWidget($hookName = null, array $configuration = [])
    {
        $cache_id = 'eter_imageslider';
        $cache_id .= Context::getContext()->language->id;
        $cache = Cache::getInstance();
        $html = "";
        if ((!_PS_CACHE_ENABLED_) || (_PS_CACHE_ENABLED_ && !$cache->exists($cache_id))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
            $html = $this->display(__FILE__, 'views/templates/hook/slider.tpl');
            $cache->set($cache_id, $html);
        }
        $cacheHtml = $cache->get($cache_id);
        return ($cacheHtml) ? $cacheHtml : $html;  

    }
    /**
     * get render widget variables
     */
    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $active = true;
        $data =  Eter_HomeSlide::getData($active);
        foreach ($data as &$value) {
            $src = $this->context->link->getMediaLink('/modules/eter_imageslider/images/'.$value['background']);
            $value['background'] = $src;
            $src = $this->context->link->getMediaLink('/modules/eter_imageslider/images/'.$value['image']);
            $value['image'] = $src;
        }
        return [
            'homeslider' => [
                'slides' => $data,
            ],
        ];
    }
     /* Upload image */
    public function validateImage($name,$path,$width,$height,$validateInput,$max_size=500000) 
    {
        if($validateInput) {
            if (empty($_FILES[$name]['name'])) {
                return $this->l('Please select an image');
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