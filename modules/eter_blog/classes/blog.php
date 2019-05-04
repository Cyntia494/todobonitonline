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
class SectionBlog extends ObjectModel    
{   
    /**
    * Define globlal variables
    */
    public $active;
    public $name;
    public $details;
    public $html;
    public $category;
    public $banner;
    public $url;
    public $products;
    public $created_at;
    /**
    * @see ObjectModel::$definition
    */
    public static $definition = array(
        'table' => 'eter_blog',   
        'primary' => 'id_blog',   
        'multilang' => true,
        'fields' => array(
            'active' =>  array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml'),
            'details' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
            'html' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
            'category' => array('type' => self::TYPE_INT, 'required' => true),
            'products' => array('type' => self::TYPE_STRING, 'required' => false),
            'banner' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
            'url' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 255),
            'created_at' => array('type' => self::TYPE_DATE, 'lang' => false, 'validate' => 'isCleanHtml'),
        )
    );
    /**
    * Add object SectionBlog
    */
    public function add($autodate = true, $null_values = false)
    {
        $context = Context::getContext();
        $id_shop = ($this->id_shop) ? $this->id_shop : $context->shop->id;
        $res = parent::add($autodate, $null_values);
        $res &= Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'eter_blog_shop` (`id_shop`, `id_blog`) VALUES('.(int)$id_shop.', '.(int)$this->id.')');
        return $res;
    }

    /**
    * Create tables
    */
    public function createTables()
    {
        $res = Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_blog` (
                `id_blog` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
                `category` int(10) unsigned NOT NULL,
                `created_at` datetime NOT NULL,
                `banner` varchar(255) NOT NULL,
                `products` varchar(255) NOT NULL,
                PRIMARY KEY (`id_blog`),
                FOREIGN KEY (category) REFERENCES '._DB_PREFIX_.'eter_category(id_category)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
            ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_blog_shop` (   
                `id_blog` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_blog`, `id_shop`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
            ');
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eter_blog_lang` (  
                `id_blog` int(10) unsigned NOT NULL,
                `id_lang` int(10) unsigned NOT NULL,
                `name` varchar(255) NOT NULL,
                `url` varchar(255) NOT NULL,
                `details` varchar(255) NOT NULL,
                `html` text NOT NULL,
                PRIMARY KEY (`id_blog`,`id_lang`)
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
    public function deleteTables()
    {
        $res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'eter_blog`;');
        $res &= Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'eter_blog_shop`;');
        $res &= Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'eter_blog_lang`;');
        return $res;
    }
    /**
    * Add default samples
    */
    public function installSamples() 
    {
        $jsonFile = _PS_MODULE_DIR_.'eter_blog/views/blog_posts.json';
        $htmlfile = _PS_MODULE_DIR_.'eter_blog/views/lorem.txt';
        #Se dan permisos al archivo y se cargar las configuraciones
        @chmod($jsonFile,0777);
        if(file_exists($jsonFile)) {
            $data = file_get_contents($jsonFile);
            $html = file_get_contents($htmlfile);
            $psData = json_decode($data, true);
            $posts = $psData['posts'];
            $languages = Language::getLanguages(false);
            foreach ($posts as $post) {
                $obj = new SectionBlog(); 
                $obj->active = $post['active'];
                $obj->category = $post['category'];
                $obj->banner = $post['banner'];  
                $obj->products = "1,2,3,5,4,6,7,8,9";               
                $obj->created_at = date('Y-m-d');;
                foreach ($languages as $lang) {
                    $obj->url[$lang['id_lang']] = $post['url'];
                    $obj->name[$lang['id_lang']] = $post['name'];
                    $obj->details[$lang['id_lang']] = $post['details'];
                    $obj->html[$lang['id_lang']] = $html;
                }
                $obj->save();
            }
        }
        return true;
    }
    /**
    * Get blog data
    */
    public static function getBlogData($limit = null,$active = false)
    {  
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $id_lang = $context->language->id;
        $where = "";
        $page = "";
        if (Tools::isSubmit('submitFilter')) {
            $filtername = Tools::getValue('SectionBlogFilter_name');
            if ($filtername != '') {
                $where = ' AND blog.name LIKE "%'.$filtername.'%"';
            }
        }
        if ($active) {
            $where .= ' AND blog.active = 1 ';
        }
        if ($limit) {
            $page = ' LIMIT '.$limit;
        }
        $data = Db::getInstance(_PS_USE_SQL_SLAVE_)->executes('
            SELECT 
                blog_shop.`id_blog`, 
                blog.`active`,
                blog.`category`,
                blog.`products`,
                blog.`banner`,
                blog.`created_at`,
                category_lang.`url` as cat_url,
                blog_lang.`url`,
                blog_lang.`name`,
                blog_lang.`details`,                
                blog_lang.`html`
            FROM '._DB_PREFIX_.'eter_blog_shop blog_shop
                LEFT JOIN '._DB_PREFIX_.'eter_blog blog ON (blog_shop.id_blog = blog.id_blog)
                LEFT JOIN '._DB_PREFIX_.'eter_blog_lang blog_lang ON (blog_lang.id_blog = blog_shop.id_blog)
                LEFT JOIN '._DB_PREFIX_.'eter_category_lang category_lang ON (category_lang.id_category = blog.category)
            WHERE blog_shop.id_shop = '.(int)$id_shop.'
                AND blog_lang.id_lang = '.(int)$id_lang.$where.'
                GROUP BY blog_shop.id_blog 
                ORDER BY blog.`created_at` DESC'.$page
        );
        
        return $data;   
    }
    /**
    * Get values for the config form
    */
    public static function getAddFieldsValues()
    {
        $fields = array();
        if (Tools::isSubmit('id_blog')) {
            $data = new SectionBlog((int)Tools::getValue('id_blog'));
            $fields['id_blog'] = Tools::getValue('id_blog');
        } else {
            $data = new SectionBlog();
        }
        $fields['active'] = Tools::getValue('active', $data->active);
        $fields['created_at'] = Tools::getValue('created_at', $data->created_at);
        $fields['category'] = Tools::getValue('category', $data->category);
        $fields['products'] = Tools::getValue('products', $data->products);
        
        $fields['banner'] = $data->banner;
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $fields['url'][$lang['id_lang']] = Tools::getValue('url_'.(int)$lang['id_lang'], $data->url[$lang['id_lang']]);
            $fields['name'][$lang['id_lang']] = Tools::getValue('name_'.(int)$lang['id_lang'], $data->name[$lang['id_lang']]);
            $fields['html'][$lang['id_lang']] = Tools::getValue('html_'.(int)$lang['id_lang'], $data->html[$lang['id_lang']]);
            $fields['details'][$lang['id_lang']] = Tools::getValue('details_'.(int)$lang['id_lang'], $data->details[$lang['id_lang']]);
        }
        return $fields;
    } 
}