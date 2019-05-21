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
* @package    Eter_Inlinelinks
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/
class EterLinks extends ObjectModel
{
    public $id_shop;
    public $icon;
    public $title;
    public $active;
    public $position;
    public $url;

    /**
    * @see ObjectModel::$definition
    */
    public static $definition = array(
        'table' => 'eter_links',   
        'primary' => 'id_link',   
        'multilang' => true,
        'fields' => array(
            'icon' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'sizse' => 255),
            'position' => array('type' => self::TYPE_INT, 'validate' => 'isCleanHtml'),
            'active' => array('type' => self::TYPE_INT, 'validate' => 'isCleanHtml'),
            'title' => array('type' => self::TYPE_HTML, 'lang' => true, 'size' => 255),
            'url' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
        )
    );

    /**
     * Get table informations
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
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_links` (
                `id_link` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `icon` varchar(255) NOT NULL,
                `position` int(10) unsigned NOT NULL,
                `active` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_link`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
          CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_links_shop` (   
                `id_link` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_link`, `id_shop`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');
        $res &= Db::getInstance()->execute('
          CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_links_lang` (  
                `id_link` int(10) unsigned NOT NULL,
                `id_lang` int(10) unsigned NOT NULL,
                `title` varchar(255) NOT NULL,
                `url` varchar(255) NOT NULL,
                PRIMARY KEY (`id_link`,`id_lang`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        if($res) {
            $samples = [];
            $samples[] = ['title' => "<strong>Follow</strong> your order",'icon' => 'order.png','url' => '#'];
            $samples[] = ['title' => "Free <strong>shipping</strong>",'icon' => 'shipping.png','url' => '#'];
            //$samples[] = ['title' => "Our <strong>offers</strong>",'icon' => 'offers.png','url' => '#'];
            $this->installSamples($samples);
        }
        return $res;
    }
    /**
     * return an available position 
     */
    public function getNewLastPosition()
    {
        return (Db::getInstance()->getValue('
            SELECT IFNULL(MAX(position),0)+1
            FROM `'._DB_PREFIX_.'eter_links`'));
    }
    /**
     * deletes tables
     */
    public function deleteTables()
    {
        $res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'eter_links`;');
        $res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'eter_links_shop`;');
        $res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'eter_links_lang`;');
        return $res;
    }
    
    /**
     * Adds samples
    */
    public function installSamples($samples) 
    {
        
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $key = 'ETER_INLINELINK_TITLE_'.$lang['id_lang'];
            $value = Tools::getValue($key);
            Configuration::updateValue($key,"The best for you!");
        }
        $shops = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * FROM '._DB_PREFIX_.'shop');
        foreach ($shops as $value) {
            $languages = Language::getLanguages(false,$value['id_shop']);
            foreach ($samples as $key => $sample) {
                $data = new EterLinks();
                $data->icon = "default/".$sample['icon'];
                $data->position = $key;
                $data->active = 1;
                foreach ($languages as $language) {
                    $lang = $language['id_lang'];
                    $data->title[$lang] = $sample['title'];
                    $data->url[$lang] = $sample['url'];
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
        $where = "";
        if($active) {
            $where = " AND ec.`active` = 1 ";
        }
        $data = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT
               ecs.`id_link`,
               ec.`icon`,
               ec.`position`,
               ec.`active`,
               ecl.`url`,
               ecl.`title` 
            FROM '._DB_PREFIX_.'eter_links_shop ecs
               LEFT JOIN '._DB_PREFIX_.'eter_links ec ON (ecs.id_link = ec.id_link)
               LEFT JOIN '._DB_PREFIX_.'eter_links_lang ecl ON (ecl.id_link = ec.id_link)
            WHERE ecs.id_shop = '.(int)$id_shop.'
               AND ecl.id_lang = '.(int)$id_lang.$where.'
            ORDER BY ec.position');
        
        return $data;
    }

    /**
     * get form values
     */
    public static function getAddFieldsValues()
    {
        $fields = array();
        if (Tools::isSubmit('id_link')) {
            $data = new EterLinks((int)Tools::getValue('id_link'));
            $fields['id_link'] = (int)$data->id;
        } else {
            $data = new EterLinks(); 
        }

        $fields['icon'] = Tools::getValue('icon',$data->icon);
        $fields['position'] = Tools::getValue('position',$data->position);
        $fields['active'] = Tools::getValue('active',$data->active);
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $fields['url'][$lang['id_lang']] = Tools::getValue('url_'.$lang['id_lang'], $data->url[$lang['id_lang']]);
            $fields['title'][$lang['id_lang']] = Tools::getValue('title_'.$lang['id_lang'], $data->title[$lang['id_lang']]);
        }
        return $fields;
    }

    /**
     * add table informations
     */
    public function add($autodate = true, $null_values = false)
    {
        $context = Context::getContext();
        $id_shop = ($this->id_shop) ? $this->id_shop : $context->shop->id;
        $res = parent::add($autodate, $null_values);
        $res &= Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'eter_links_shop` (`id_shop`, `id_link`) VALUES('.(int)$id_shop.', '.(int)$this->id.')');
        return $res;
    }

}