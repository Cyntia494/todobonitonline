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
class eter_blogpostModuleFrontController extends ModuleFrontController
{
    /**
    * Define globlal variables
    */
    public $ssl = false;
    public $blogname = null;
    public $blog = null;
    /**
    * Initialize controller
    */
    public function init() {
        parent::init();
        $url = Tools::getValue('url');
        $this->blog = $this->getBlogData($url);
    }
    /**
    * Initialize controller
    */
    public function initContent() {
        $this->page_name = "blog-post";
        parent::initContent();
        if ($this->blog) {
            $blog = $this->blog;
            if($blog['banner']) {
                $blog["banner"] = $this->context->link->getMediaLink('/modules/eter_blog/images/'.$blog['banner']); 
            } else {
                $blog["banner"] = $this->context->link->getMediaLink('/modules/eter_blog/images/default/banner.png');
            }
            $productsHtml = "";
            if ($blog["products"]) {
                $productsList = $this->module->_blogproducts->getBlogProducts($blog["products"]);
                $this->context->smarty->assign(array('products' => $productsList));
                $productsHtml = $this->context->smarty->fetch(_PS_MODULE_DIR_.'eter_blog/views/templates/front/products.tpl');
            }
            $blog["url"] = $this->context->link->getModuleLink('eter_blog','post',['cat_url'=>$blog['cat_url'],'url'=>$blog['url']]);
            $relatedPosts = "";
            if ($blog["category"]) {
                $blogList = $this->getBlogList($blog["category"],$blog["id_blog"]);
                foreach ($blogList as $key => &$row) {
                    if($row['banner']) {
                        $row["banner"] = $this->context->link->getMediaLink('/modules/eter_blog/images/'.$row['banner']); 
                    } else {
                        $row["banner"] = $this->context->link->getMediaLink('/modules/eter_blog/images/default/banner.png');
                    }
                    $row["created_at"] = date("F j, Y", strtotime($row["created_at"]));
                    $row["url"] = $this->context->link->getModuleLink('eter_blog','post',['cat_url'=>$row['cat_url'],'url'=>$row['url']]);
                    if (isset($row["categorie_name"])) {
                        $this->categorie_name = $row["categorie_name"];
                    }
                }
                $this->context->smarty->assign(array('bloglist' => $blogList));
                $relatedPosts = $this->context->smarty->fetch(_PS_MODULE_DIR_.'eter_blog/views/templates/front/blog-list.tpl');
            }

            $this->blogname = $blog["name"];
            
            $this->context->smarty->assign(array('blog' => $blog,'products' => $productsHtml,'relateds' => $relatedPosts,'app_id' =>Configuration::get('FACEBOOK_APP_ID')));
            $this->setTemplate('module:eter_blog/views/templates/front/blog.tpl');
        } else {
            $link = $this->context->link->getModuleLink('eter_blog','blogs',[]);
            Tools::redirect($link);
        }

    }
    /**
    * Show canonical url
    */
    public function getCanonicalURL()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
        $full_url = $protocol."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        return $full_url;
    }
    protected function getBreadcrumbLinks()
    {
        $breadcrumb = array();

        $breadcrumb['links'][] = array(
            'title' => $this->getTranslator()->trans('Home', array(), 'Shop.Theme.Global'),
            'url' => $this->context->link->getPageLink('index', true),
        );

        $breadcrumb['links'][] = array(
            'title' => $this->l('Blog'),
            'url' => $this->context->link->getModuleLink('eter_blog','blogs',[]),
        );
        if ($this->blog) {
            $breadcrumb['links'][] = array(
                'title' => $this->blog['cat_name'],
                'url' => $this->context->link->getModuleLink('eter_blog','category',['url' => $this->blog['cat_url']]),
            );

            $breadcrumb['links'][] = array(
                'title' => $this->blog['name'],
                'url' => '',
            );
        }
        return $breadcrumb;
    }
    /**
    * Get blog data
    */
    public static function getBlogData($url)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $id_lang = $context->language->id;

        $data = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
            SELECT 
                blog_shop.`id_blog`, 
                blog.`active`,
                blog.`category`,
                blog.`products`,
                blog.`banner`,
                category_lang.`url` as cat_url,
                category_lang.`name` as cat_name,
                blog_lang.`name`,
                blog_lang.`details`,
                blog_lang.`url`,
                blog_lang.`html`
            FROM '._DB_PREFIX_.'eter_blog_shop blog_shop
                LEFT JOIN '._DB_PREFIX_.'eter_blog blog ON (blog_shop.id_blog = blog.id_blog)
                LEFT JOIN '._DB_PREFIX_.'eter_blog_lang blog_lang ON (blog_lang.id_blog = blog_shop.id_blog)
                LEFT JOIN '._DB_PREFIX_.'eter_category_lang category_lang ON (category_lang.id_category = blog.category)
            WHERE blog.active = 1  
                AND blog_shop.id_shop = '.(int)$id_shop.'
                AND blog_lang.id_lang = '.(int)$id_lang.'
                AND blog_lang.url like "'.$url.'"');
        return $data;   
    }
    /**
    * if id_category is > 0 will select blogs by id category
    * if id_catgory is 0 or not exist will select all blogs 
    */
    public static function getBlogList($id_category,$id_current)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $id_lang = $context->language->id;
        $data = Db::getInstance(_PS_USE_SQL_SLAVE_)->executes('
            SELECT 
                blog_shop.`id_blog`, 
                blog.`active` ,
                blog.`category`,
                blog.`created_at`,
                blog.`banner`,
                category_lang.`url` as cat_url,
                blog_lang.`name`,
                blog_lang.`details`,
                blog_lang.`url`,
                blog_lang.`html`
            FROM '._DB_PREFIX_.'eter_blog_shop blog_shop
                LEFT JOIN '._DB_PREFIX_.'eter_blog blog ON (blog_shop.id_blog = blog.id_blog)
                LEFT JOIN '._DB_PREFIX_.'eter_blog_lang blog_lang ON (blog_lang.id_blog = blog_shop.id_blog)
                LEFT JOIN '._DB_PREFIX_.'eter_category_lang category_lang ON (category_lang.id_category = blog.category)
            WHERE blog_shop.id_shop = '.(int)$id_shop.'
                AND blog_lang.id_lang = '.(int)$id_lang.'
                AND blog.active = 1 
                AND blog_shop.id_blog != '.$id_current.' 
                AND blog.category = '.$id_category.'
                ORDER BY blog.`created_at` DESC
                LIMIT 2');
        
        return $data;   
    }
    /**
    * Add media files
    */ 
    public function setMedia()
    {
    parent::setMedia(); // JS and CSS files
    $this->context->controller->addCSS(_PS_MODULE_DIR_.'eter_blog/views/assets/css/module.css');  
    }
}
