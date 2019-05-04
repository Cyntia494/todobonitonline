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
if (!defined('_PS_VERSION_')) {
    exit;
}
/**
* Import classes
*/
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
include_once(_PS_MODULE_DIR_.'eter_blog/classes/category.php');
include_once(_PS_MODULE_DIR_.'eter_blog/classes/blog.php');
include_once(_PS_MODULE_DIR_.'eter_blog/classes/EterMedia.php');
include_once(_PS_MODULE_DIR_.'eter_blog/classes/BlogProducts.php');
require_once dirname(__FILE__)."/src/autoload.php";
class Eter_Blog extends Module implements WidgetInterface
{
    /**
    * Define globlal variables
    */
    public $_html;
    public $errors;
    protected $templateFile;
    public $_categories = null;
    public $_blogs = null;
    public $_media = null;
    /**
    * Create construct with module's information 
    */
    public function __construct()
    {
        $this->name = 'eter_blog';
        $this->author = 'ETERLABS S.A.S de C.V';
        $this->version = '1.0.0';
        $this->tab = 'content_management';
        $this->secure_key = Tools::encrypt($this->name);
        $this->author_uri = 'https://www.eterlabs.com';
        
        parent::__construct();
        $this->bootstrap = true;
        $this->displayName = $this->l('Blog');
        $this->description = $this->l('Allow to add blog in the ecommerse site');
        $this->confirmUninstall = $this->l('Are you sure you want uninstall this module?');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
        $this->_categories = new SectionCategory();
        $this->_blogs = new SectionBlog();
        $this->_media = new EterMedia();
        $this->_blogproducts = new BlogProducts($this);
    }
    /**
    * Install module, in this function add and register hooks, and add new taba in back office
    */
    public function install()
    {
        return parent::install() 
            && $this->registerHook('displayHome') 
            && $this->registerHook('displayHeader')
            && $this->registerHook('moduleRoutes')
            && $this->registerHook('gSitemapAppendUrls')
            && EterHelper::addModuleTabMenu('ETERLABSMODULES','AdminMarketingContent','Marketing','eter_marketing',100,'trending_up')
            && EterHelper::addModuleTabMenu('AdminMarketingContent','AdminEterBlog','Blog',$this->name,20)
            && EterHelper::addModuleTabMenu('AdminEterBlog','AdminEterBlogs','Blogs',$this->name,10)
            && EterHelper::addModuleTabMenu('AdminEterBlog','AdminEterCategory','Categories',$this->name,20)
            && $this->_categories->createTables()
            && $this->_blogs->createTables();

    }
    /**
    * Uninstall module, remove tabsfrom back office
    */
    public function uninstall()
    {
        return EterHelper::removeModuleTabMenu('AdminEterBlogs')
            && EterHelper::removeModuleTabMenu('AdminEterCategory')
            && EterHelper::removeModuleTabMenu('AdminEterBlog')
            && EterHelper::removeModuleTabMenu('AdminMarketingContent')
            && EterHelper::removeModuleTabMenu('ETERLABSMODULES')
            && parent::uninstall()
            && $this->_blogs->deleteTables()
            && $this->_categories->deleteTables();
    }

    /**
    * Create friendly routes
    */
    public function hookgSitemapAppendUrls($parameter) 
    {
        $id_lang = $parameter["lang"]["id_lang"];
        $context = Context::getContext();
        $id_shop = $context->shop->id;

        $posts = Db::getInstance(_PS_USE_SQL_SLAVE_)->executes('
            SELECT 
                blog_lang.`url`,
                blog_lang.`name`,
                blog_lang.`details`,                
                blog.`banner`,
                category_lang.`url` as cat_url
            FROM '._DB_PREFIX_.'eter_blog_shop blog_shop
                LEFT JOIN '._DB_PREFIX_.'eter_blog blog ON (blog.id_blog = blog_shop.id_blog)
                LEFT JOIN '._DB_PREFIX_.'eter_blog_lang blog_lang ON (blog_lang.id_blog = blog_shop.id_blog)
                LEFT JOIN '._DB_PREFIX_.'eter_category_lang category_lang ON (category_lang.id_category = blog.category)
            WHERE blog_shop.id_shop = '.(int)$id_shop.'
                AND blog_lang.id_lang = '.(int)$id_lang.'
                ORDER BY blog.`created_at` DESC'
        );
        $links = [];
        foreach ($posts as $value) {
            $link['type'] = 'module';
            $link['page'] = $value['name'];
            $link['link'] = $this->context->link->getModuleLink('eter_blog','post',['cat_url' => $value['cat_url'],'url'=>$value['url']]);
            $link['image']['link'] = $this->context->link->getMediaLink('/modules/eter_blog/images/'.$value['banner']); 
            $link['image']['title_img'] = $value['name'];
            $link['image']['caption'] = $value['name'];
            $links[] = $link;
        }
        $categories = Db::getInstance(_PS_USE_SQL_SLAVE_)->executes('
            SELECT 
                category_lang.`url`,
                category_lang.`name`
            FROM '._DB_PREFIX_.'eter_category_shop category_shop
                LEFT JOIN '._DB_PREFIX_.'eter_category category ON (category.id_category = category_shop.id_category)
                LEFT JOIN '._DB_PREFIX_.'eter_category_lang category_lang ON (category_lang.id_category = category_shop.id_category)
            WHERE category_shop.id_shop = '.(int)$id_shop.'
                AND category_lang.id_lang = '.(int)$id_lang
        );
        foreach ($categories as $value) {
            $link['type'] = 'module';
            $link['page'] = $value['name'];
            $link['link'] = $this->context->link->getModuleLink('eter_blog','category',['url'=>$value['url']]);
            $link['image'] = [];
            $links[] = $link;
        }
        return $links;
    }

    /**
    * Create friendly routes
    */
    public function hookModuleRoutes(){
        return array(
            'module-eter_blog-blogs' => array( 
                'controller' => 'blogs', 
                'rule' => 'blog', 
                'keywords' => array(
                    'id_category' => array('regexp' => '[0-9]+'), 
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'eter_blog', 
                )
            ),
            'module-eter_blog-category' => array( 
                'controller' => 'blogs', 
                'rule' => 'blog/{url}', 
                'keywords' => array(
                    'url' => array('regexp' => '[_a-zA-Z0-9\pL\pS-]*', 'param' => 'url'), 
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'eter_blog', 
                )
            ),
            'module-eter_blog-post' => array( 
                'controller' => 'post', 
                'rule' => 'blog/{cat_url}/{url}', 
                'keywords' => array(
                    'url' => array('regexp' => '[_a-zA-Z0-9\pL\pS-]*', 'param' => 'url'), 
                    'cat_url' => array('regexp' => '[_a-zA-Z0-9\pL\pS-]*', 'param' => 'cat_url'), 
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => 'eter_blog', 
                )
            )
        );
    }
    /**
    * Render widget hook, display tpl to home page
    */
    public function renderWidget($hookName = null, array $configuration = [])
    {
        $this->getWidgetVariables($hookName, $configuration);
        return $this->display(__FILE__, 'views/templates/home_blogs.tpl');
    }
    /**
    * Get widget data to show in home page
    */
    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $this->smarty->assign($this->getBlogs());
    }
    /**
    * Add css or js file on header section
    */
    public function hookdisplayHeader($params)
    {
        if($this->context->controller->php_self == "index" ) {
            $base = $this->_path.'views/assets/';
            $this->context->controller->registerStylesheet('owl_css_carousel',$base.'css/owl.css');
            $this->context->controller->registerStylesheet('owl_css',$base.'css/assets/owl.theme.css');
            $this->context->controller->registerJavascript('owl_js',$base.'js/owl.js');            
            $this->context->controller->addCSS($base.'css/module.css', 'all');
            $this->context->controller->addJS($base.'js/module.js', 'all');
        }
    }
    /**
    * Get all blogs
    */
    public function getBlogs()
    {
        $blogs = $this->_blogs->getBlogData(6,true);
        $blogsArray = [];
        foreach ($blogs as $key => &$row) {
            if ($row['active']) {
                if($row['banner']) {
                    $row["banner"] = $this->context->link->getMediaLink('/modules/eter_blog/images/'.$row['banner']); 
                } else {
                    $row["banner"] = $this->context->link->getMediaLink('/modules/eter_blog/images/default/banner.png');
                }
                $row["url"] = $this->context->link->getModuleLink('eter_blog','post',['cat_url'=>$row['cat_url'],'url'=>$row['url']]);
                $row["created_at"] = date("F j, Y", strtotime($row["created_at"]));
                $blogsArray[] = $row;
            }
        }
        return ['blogs' => $blogsArray];
    }
    /**
    * Render Configurations
    */
    public function getContent()
    {
        if (Tools::isSubmit('submitFacebookId')) {
            Configuration::updateValue('FACEBOOK_APP_ID', trim(Tools::getValue('FACEBOOK_APP_ID')));
        }
        $response = ModuleValidator::IsSecureKey($this->module,true);
        if (!$response->active) {
            return $response->html;
        } else {
            return $this->displayForm();
        }
    }
    /**
    * Display form configuration
    */
    public function displayForm()
    {

        $this->fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->displayName,
                    'icon' => 'icon-user'
                ),
                'input' => array(
                    array(
                        'type'     => 'text',
                        'label'    => $this->l('Your Facebook App ID'),
                        'name'     => 'FACEBOOK_APP_ID',
                        'required' => true,
                        'col'      => '4',
                        'desc'     => $this->l('This will help you to manage the comments:'),
                    ),
                ),
                'submit' => array(
                    'name'  => 'submitFacebookId',
                    'title' => $this->l('Save ID'),
                )
            )
        );

        $this->fields_value['FACEBOOK_APP_ID'] = Tools::safeOutput(
            Tools::getValue('FACEBOOK_APP_ID', Configuration::get('FACEBOOK_APP_ID'))
        );

        $helper = new HelperForm();
        // Module, token and currentIndex
        $helper->module          = $this;
        $helper->fields_value    = $this->fields_value;
        $helper->name_controller = $this->name;
        $helper->token           = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex    = AdminController::$currentIndex.'&configure='.$this->name;
        // Language
        $default_lang                     = $this->context->language->id;
        $helper->default_form_language    = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        // Title and toolbar
        $helper->title          = $this->displayName;
        $helper->show_toolbar   = false; 
        $helper->toolbar_scroll = false; 
        $helper->submit_action  = 'submitFacebookId';
        return $helper->generateForm(array($this->fields_form));
    }
    /* List Paginations */
    public function paginate($data, $page = 1, $pagination = 50)
    {
        if (count($data) > $pagination) {
            $data = array_slice($data, $pagination * ($page - 1), $pagination);
        }
        return $data;
    }
}




