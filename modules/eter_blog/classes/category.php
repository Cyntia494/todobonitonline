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
* @package    Eter_Blogs
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/
class SectionCategory extends ObjectModel
{   
    /**
    * Define globlal variables
    */
    public $active;
    public $name;
    public $url;
    public $resumen;
    /**
    * @see ObjectModel::$definition
    */
    public static $definition = array(
        'table' => 'eter_category',   
        'primary' => 'id_category',   
        'multilang' => true,
        'fields' => array(
            'active' =>  array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml'),
            'resumen' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
            'url' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 255), 
        )
    );
    /**
    * Add object SectionCategory
    */
    public function add($autodate = true, $null_values = false)
    {
        $context = Context::getContext();
        $id_shop = ($this->id_shop) ? $this->id_shop : $context->shop->id;
        $res = parent::add($autodate, $null_values);
        $res &= Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'eter_category_shop` (`id_shop`, `id_category`) VALUES('.(int)$id_shop.', '.(int)$this->id.')');
        return $res;
    }

    /**
    * Create tables
    */
    public function createTables()
    {
        $res = (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_category_shop` (   
                `id_category` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_category`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
            ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_category` (
                `id_category` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
                PRIMARY KEY (`id_category`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
            ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_category_lang` (  
                `id_category` int(10) unsigned NOT NULL,
                `id_lang` int(10) unsigned NOT NULL,
                `name` varchar(255) NOT NULL,
                `url` varchar(255) NOT NULL,
                `resumen` varchar(255) NOT NULL,
                PRIMARY KEY (`id_category`,`id_lang`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
            ');
        if($res) {
            $this->installSamples();
        }
        return $res;
    }
    /**
    * Delete Tables
    */
    public function deleteTables()
    {
        $res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'eter_category`;');
        $res &= Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'eter_category_lang`;');
        $res &= Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'eter_category_shop`;');
        return $res;
    }
    /**
    * Add default samples
    */
    public function installSamples() 
    {
        $jsonFile = _PS_MODULE_DIR_.'eter_blog/views/blog_categories.json';
        @chmod($jsonFile,0777);
        if(file_exists($jsonFile)) {
            $data = file_get_contents($jsonFile);
            $psData = json_decode($data, true);
            $categories = $psData['categories'];
            $languages = Language::getLanguages(false);
            foreach ($categories as $category) {
                $obj = new SectionCategory(); 
                $obj->active = $category['active'];
                foreach ($languages as $lang) {
                    $obj->url[$lang['id_lang']] = $category['url'];  
                    $obj->name[$lang['id_lang']] = $category['name'];
                    $obj->resumen[$lang['id_lang']] = $category['resumen'];
                }
                $obj->save();
            }
        }
        return true;
    }
    /**
    * Get seccion category data
    */
    public static function getData()
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $id_lang = $context->language->id;
        $data = Db::getInstance(_PS_USE_SQL_SLAVE_)->executes('
            SELECT 
                section_shop.`id_category`, 
                if(category.`active` = 1, "Yes", "No") as active,
                section_lang.`url`,
                section_lang.`name`,
                section_lang.`resumen`
            FROM '._DB_PREFIX_.'eter_category_shop section_shop
                LEFT JOIN '._DB_PREFIX_.'eter_category category ON (section_shop.id_category = category.id_category)
                LEFT JOIN '._DB_PREFIX_.'eter_category_lang section_lang ON (section_lang.id_category = section_shop.id_category)
            WHERE section_shop.id_shop = '.(int)$id_shop.'
                AND section_lang.id_lang = '.(int)$id_lang);
        return $data;    
    }
    /**
    * Get values for the config form
    */
    public static function getAddFieldsValues()
    {
        $fields = array();
        if (Tools::isSubmit('id_category')) {
            $data = new SectionCategory((int)Tools::getValue('id_category'));
            $fields['id_category'] = Tools::getValue('id_category');
        } else {
            $data = new SectionCategory();
        }
        $fields['active'] = Tools::getValue('active', $data->active);
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $fields['url'][$lang['id_lang']] = Tools::getValue('url_'.(int)$lang['id_lang'], $data->url[$lang['id_lang']]);
            $fields['name'][$lang['id_lang']] = Tools::getValue('name_'.(int)$lang['id_lang'], $data->name[$lang['id_lang']]);
            $fields['resumen'][$lang['id_lang']] = Tools::getValue('resumen_'.(int)$lang['id_lang'], $data->resumen[$lang['id_lang']]);
        }
        return $fields;
    }
}