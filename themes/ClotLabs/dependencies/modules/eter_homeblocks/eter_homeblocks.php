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
* @package    Eter_Homeblocks
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/
if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
include_once(_PS_MODULE_DIR_.'eter_homeblocks/classes/Block.php');
require_once dirname(__FILE__)."/src/autoload.php";

class Eter_Homeblocks extends Module implements WidgetInterface
{
    /**
    * Define globlal variables
    */
    protected $_html = '';
    protected $_tableid = "id_block";
    protected $templateFile;
    public $_img_path = "eterlabs/eter_homeblocks/";
    /**
    * Create construct with module's information 
    */
    public function __construct()
    {
        $this->name = 'eter_homeblocks';
        $this->author = 'ETERLABS S.A.S de C.V';
        $this->version = '1.0.0';
        $this->tab = 'front_office_features';
        $this->secure_key = Tools::encrypt($this->name);
        $this->author_uri = 'https://www.eterlabs.com';
        
        parent::__construct();
        $this->bootstrap = true;
        $this->displayName = $this->l('Blocks Carousels');
        $this->description = $this->l('Add a carousel (Image, Title, Description and button) on the homepage, excellent for promoting news, products, categories or links in general');
        $this->confirmUninstall = $this->l('Are you sure you want uninstall this module?');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }
    /**
    * Install module, in this function add and register hooks, and add new tab "HTML Carousel" in back office
    */
    public function install()
    {
        /* Adds Module */
        return parent::install() 
            && $this->registerHook('displayHome') 
            && $this->registerHook('displayHeader')
            && EterHelper::addModuleTabMenu('ETERLABSMODULES','AdminHomeContent','Page Content','eter_homepage',50,'home')
            && EterHelper::addModuleTabMenu('AdminHomeContent','AdminEterHomeblocks','Block Carousel',$this->name,90)
            && $this->createTables();
    }
    /**
    * Uninstall module, remove tab "HTML Carousel" from back office
    */
    public function uninstall()
    {
        /* Deletes Module */
        return parent::uninstall()
            && EterHelper::removeModuleTabMenu('AdminEterHomeblocks')
            && EterHelper::removeModuleTabMenu('AdminHomeContent')
            && EterHelper::removeModuleTabMenu('ETERLABSMODULES')
            && $this->deleteTables();
    }
    /**
    * Create tables
    */
    protected function createTables()
    {
        $res = (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_blocks_shop` (
                `id_block` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_block`, `id_shop`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
            ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_blocks` (
                `id_block` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `image` varchar(255) NOT NULL,
                `position` int(10) unsigned NOT NULL,
                `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
                PRIMARY KEY (`id_block`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
            ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_blocks_lang` (
                `id_block` int(10) unsigned NOT NULL,
                `id_lang` int(10) unsigned NOT NULL,
                `title` varchar(255) NOT NULL,
                `details` text NOT NULL,
                `url_name` varchar(255) NOT NULL,
                `url` varchar(255) NOT NULL,
                PRIMARY KEY (`id_block`,`id_lang`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
            ');
        if($res) {
            $this->installSamples();
        }
        return $res;
    }
    /**
    * Delete tables
    */
    protected function deleteTables()
    {
        $res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'eter_blocks_shop`;');
        $res &= Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'eter_blocks`;');
        $res &= Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'eter_blocks_lang`;');
        return $res;
    }
    /**
    * Remove tab from admin menu
    */
    protected function installSamples() 
    {
        Configuration::updateValue('HOMEBLOCK_ADD_LIBRARY', 2);
        $languages = Language::getLanguages(false);
        $shops = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * FROM '._DB_PREFIX_.'shop');
        foreach ($shops as $value) {
            $languages = Language::getLanguages(false,$value['id_shop']);
            for ($i = 1; $i <= 3; ++$i) {
                $slide = new Block();
                $slide->image = 'default/homeblocks-'.$i.'.jpg';
                $slide->active = 1;
                $slide->position = $i;
                $slide->id_shop = $value['id_shop'];
                foreach ($languages as $language) {
                    $lang = $language['id_lang'];
                    $slide->title[$lang] = 'Comercio electrónico '.$i;
                    $slide->details[$lang] = 'La mejor imagen, estrategia, metodología y consultoría para que tu negocio se vea increible.';
                    $slide->url_name[$lang] = 'Ver más';
                    $slide->url[$lang] = '#';
                }
                $slide->add();
            }
        }
    }
    /**
    * Add css or js file on header section
    */
    public function hookdisplayHeader($params)
    {
        if($this->context->controller->php_self == "index") {
            $this->context->controller->addCSS($this->_path.'css/assets/owl.carousel.min.css', 'all');
            $this->context->controller->addCSS($this->_path.'css/assets/owl.theme.default.min.css', 'all');
            $this->context->controller->addJS($this->_path.'js/owl.carousel.min.js', 'all');
            $this->context->controller->addCSS($this->_path.'views/assets/css/module.css', 'all');
            $this->context->controller->addJS($this->_path.'js/module.js', 'all');
        }
    }
    /**
    * Render widget hook, display tpl to home page
    */
    public function renderWidget($hookName = null, array $configuration = [])
    {
        $cache_id = 'eter_homeblocks';
        $cache_id .= Context::getContext()->language->id;
        $cache = Cache::getInstance();
        $html = "";
        if ((!_PS_CACHE_ENABLED_) || (_PS_CACHE_ENABLED_ && !$cache->exists($cache_id))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
            $html = $this->fetch('module:eter_homeblocks/views/templates/hook/blocks.tpl');
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
        $data = Block::getData();
        foreach ($data as $key => &$row) {
            $row["image_url"] = $this->context->link->getMediaLink('/modules/eter_homeblocks/images/'.$row['image']);
        }
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
