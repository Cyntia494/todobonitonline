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

class EterIcons extends ObjectModel
{
    /**
    * Define globlal variables
    */
    public $id_shop;
    public $image;
    public $name;
    public $active;
    public $position;
    public $url;
    /**
    * @see ObjectModel::$definition
    */
    public static $definition = array(
        'table' => 'eter_icons',   
        'primary' => 'id_icon',   
        'multilang' => true,
        'fields' => array(
            'image' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'sizse' => 255),
            'position' => array('type' => self::TYPE_INT, 'validate' => 'isCleanHtml'),
            'active' => array('type' => self::TYPE_INT, 'validate' => 'isCleanHtml'),
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml','size' => 255),
            'url' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
        )
    );
    /**
    * Contruct object EterIcons
    */
    public  function __construct($id = null, $id_lang = null, $id_shop = null, Context $context = null)
    {
        parent::__construct($id, $id_lang, $id_shop);
    }
    /**
    * Creates tables
    */
    public function createTables()
    {
        $res = (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_icons` (
            `id_icon` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `image` varchar(255) NOT NULL,
            `position` int(10) unsigned NOT NULL,
            `active` int(10) unsigned NOT NULL,
            PRIMARY KEY (`id_icon`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
            ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_icons_shop` (   
            `id_icon` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `id_shop` int(10) unsigned NOT NULL,
            PRIMARY KEY (`id_icon`, `id_shop`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
            ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_icons_lang` (  
            `id_icon` int(10) unsigned NOT NULL,
            `id_lang` int(10) unsigned NOT NULL,
            `name` varchar(255) NOT NULL,
            `url` varchar(255) NOT NULL,
            PRIMARY KEY (`id_icon`,`id_lang`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
            ');

        if($res) {
            $this->installSamples();
        }
        return $res;
    }
    /**
    * Deletes tables
    */
    public function deleteTables()
    {
        $res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'eter_icons`;');
        $res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'eter_icons_shop`;');
        $res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'eter_icons_lang`;');
        return $res;
    }
    /**
    * Add default samples
    */
    public function installSamples() 
    {
        $languages = Language::getLanguages(false);
        $shops = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * FROM '._DB_PREFIX_.'shop');
        foreach ($shops as $value) {
            $languages = Language::getLanguages(false,$value['id_shop']);
            for ($i = 1; $i <= 4; $i++) {
                $data = new Etericons();
                $data->image = "default/icon".$i.".png";
                $data->position = $i;
                $data->active = 1;
                foreach ($languages as $language) {
                    $lang = $language['id_lang'];
                    $data->name[$lang] = 'icon '.$i;
                    $data->url[$lang] = "#";
                }
                $data->save();
            }
        }
    }
    /**
    * Get table informations
    */
    public function getData($active = null)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $id_lang = $context->language->id;
        $data = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT
            ecs.`id_icon`,
            ec.`image`,
            ec.`position`,
            ec.`active`,
            ecl.`url`,
            ecl.`name` 
            FROM '._DB_PREFIX_.'eter_icons_shop ecs
            LEFT JOIN '._DB_PREFIX_.'eter_icons ec ON (ecs.id_icon = ec.id_icon)
            LEFT JOIN '._DB_PREFIX_.'eter_icons_lang ecl ON (ecl.id_icon = ec.id_icon)
            WHERE ecs.id_shop = '.(int)$id_shop.'
            AND ecl.id_lang = '.(int)$id_lang.'
            ORDER BY ec.position');

        return $data;
    }
    /**
    * Get form values
    */
    public static function getAddFieldsValues()
    {
        $fields = array();
        if (Tools::isSubmit('id_icon')) {
            $data = new Etericons((int)Tools::getValue('id_icon'));
            $fields['id_icon'] = (int)$data->id;
        } else {
            $data = new Etericons(); 
        }

        $fields['image'] = $data->image;
        $fields['position'] = Tools::getValue('position',$data->position);
        $fields['active'] = Tools::getValue('active',$data->active);
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $fields['url'][$lang['id_lang']] = Tools::getValue('url_'.$lang['id_lang'], $data->url[$lang['id_lang']]);
            $fields['name'][$lang['id_lang']] = Tools::getValue('name_'.$lang['id_lang'], $data->name[$lang['id_lang']]);
        }
        return $fields;
    }
    /**
    * Add table informations
    */
    public function add($autodate = true, $null_values = false)
    {
        $context = Context::getContext();
        $id_shop = ($this->id_shop) ? $this->id_shop : $context->shop->id;
        $res = parent::add($autodate, $null_values);
        $res &= Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'eter_icons_shop` (`id_shop`, `id_icon`) VALUES('.(int)$id_shop.', '.(int)$this->id.')');
        return $res;
    }
}