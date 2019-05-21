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
use PrestaShop\PrestaShop\Core\Product\Search\Pagination;
class eter_blogblogsModuleFrontController extends ModuleFrontController
{
    CONST BLOGS_PAGE_ITEMS = 12;
    /**
    * This method start with the execution
    */
    public function initContent() 
    {
        $this->cat_name = null;	
        $this->cat_url = null;   
        $this->page_name = "blogs";
        $this->blogs = $this->getBlogData();
        if ($this->blogs) {
            foreach ($this->blogs as $key => &$row) {
                if($row['banner']) {
                    $row["banner"] = $this->context->link->getMediaLink('/modules/eter_blog/images/'.$row['banner']); 
                } else {
                    $row["banner"] = $this->context->link->getMediaLink('/modules/eter_blog/images/default/banner.png');
                }
                $row["created_at"] = date("F j, Y", strtotime($row["created_at"]));
                $row["url"] = $this->context->link->getModuleLink('eter_blog','post',['cat_url'=>$row['cat_url'],'url'=>$row['url']]);
                if (isset($row["cat_name"])) {
                    $this->cat_name = $row["cat_name"];
                    $this->cat_url = $row["cat_url"];
                }
            }  


            $categories = $this->getCategories();
            parent::initContent();
            $pagination = $this->getTemplateVarPagination();
            $this->context->smarty->assign(array(
                'blogs' => $this->blogs,
                'categories' => $categories,
                'pagination' => $pagination,
            ));
            $this->setTemplate('module:eter_blog/views/templates/front/blogs.tpl');
        } else if (Tools::getValue('url')) {
            $link = $this->context->link->getModuleLink('eter_blog','blogs',[]);
            Tools::redirect($link);
        } else {
            $link = $this->context->link->getPageLink('index');
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
    /**
     * Create pagination date
     */
    protected function getTemplateVarPagination() 
    {
        $totalItems = $this->getTotalPost();
        $pagination = new Pagination();
        $pagination
            ->setPage($this->getPage())
            ->setPagesCount(ceil($this->getTotalPost() / $this->getResultsPerPage()));
        $itemsShownFrom = ($this->getResultsPerPage() * ($this->getPage() - 1)) + 1;
        $itemsShownTo = $this->getResultsPerPage() * $this->getPage();

        $pages = array_map(function ($link) {
            $link['url'] = $this->updateQueryString(array(
                'page' => $link['page'] > 1 ? $link['page'] : null,
            ));

            return $link;
        }, $pagination->buildLinks());

        //Filter next/previous link on first/last page
        $pages = array_filter($pages, function ($page) use ($pagination) {
            if ('previous' === $page['type'] && 1 === $pagination->getPage()) {
                return false;
            }
            if ('next' === $page['type'] && $pagination->getPagesCount() === $pagination->getPage()) {
                return false;
            }

            return true;
        });

        return array(
            'total_items' => $totalItems,
            'items_shown_from' => $itemsShownFrom,
            'items_shown_to' => ($itemsShownTo <= $totalItems) ? $itemsShownTo : $totalItems,
            'current_page' => $this->getPage(),
            'pages_count' => $pagination->getPagesCount(),
            'pages' => $pages,
            // Compare to 3 because there are the next and previous links
            'should_be_displayed' => (count($pagination->buildLinks()) > 3),
        );
    }
    /**
    * get Current page
    */
    public function getPage() 
    {
        $page = (int)Tools::getValue('page');
        return $page ?: 1;
    }
    /**
    * get Current page
    */
    public function getResultsPerPage() 
    {
        return  self::BLOGS_PAGE_ITEMS;
    }
    /**
    * if id_category is > 0 will select blogs by id category
    * if id_catgory is 0 or not exist will select all blogs 
    */
    public function getTotalPost()
    {
        $url = Tools::getValue('url');
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $id_lang = $context->language->id;
        $urlFilter = "";
        if($url) {
            $urlFilter = ' AND category_lang.url like "'.$url.'"';
        }
        $postsSql = 'SELECT blog.id_blog
            FROM '._DB_PREFIX_.'eter_blog_shop blog_shop
                LEFT JOIN '._DB_PREFIX_.'eter_blog blog ON (blog.id_blog = blog_shop.id_blog)
                LEFT JOIN '._DB_PREFIX_.'eter_blog_lang blog_lang ON (blog_lang.id_blog = blog.id_blog)
                LEFT JOIN '._DB_PREFIX_.'eter_category_lang category_lang ON (category_lang.id_category = blog.category)
            WHERE 
                blog_shop.id_shop = '.(int)$id_shop.'
                AND blog_lang.id_lang = '.(int)$id_lang.'
                AND blog.active = 1 '.$urlFilter.'
                GROUP by blog_shop.id_blog';

        $totalSql = 'SELECT COUNT(*) FROM ('.$postsSql.') as posts';
        $total = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($totalSql);
        return $total;
    }
    /**
    * if id_category is > 0 will select blogs by id category
    * if id_catgory is 0 or not exist will select all blogs 
    */
    public function getBlogData()
    {
        $url = Tools::getValue('url');
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $id_lang = $context->language->id;
        $filterUrl = "";
        if($url) {
            $filterUrl = ' AND category_lang.url like "%'.$url.'%"';
        }
        $items_per_page = $this->getResultsPerPage();
        $page = $this->getPage();
        $offset = ($page - 1) * $items_per_page;
        $data = Db::getInstance(_PS_USE_SQL_SLAVE_)->executes('
            SELECT 
                blog_shop.`id_blog`, 
                blog.`active`,
                blog.`category`,
                blog.`created_at`,
                blog.`banner`,
                category_lang.`name` as cat_name,
                category_lang.`url` as cat_url,
                blog_lang.`name`,
                blog_lang.`details`,
                blog_lang.`url`,
                blog_lang.`html`
            FROM '._DB_PREFIX_.'eter_blog_shop blog_shop
                LEFT JOIN '._DB_PREFIX_.'eter_blog blog ON (blog_shop.id_blog = blog.id_blog)
                LEFT JOIN '._DB_PREFIX_.'eter_blog_lang blog_lang ON (blog_lang.id_blog = blog_shop.id_blog AND blog_lang.id_lang = '.$id_lang.')
                LEFT JOIN '._DB_PREFIX_.'eter_category_lang category_lang ON (category_lang.id_category = blog.category)
            WHERE blog_shop.id_shop = '.(int)$id_shop.'
                AND category_lang.id_lang = '.(int)$id_lang.'
                AND blog.active = 1  '.$filterUrl.'
                GROUP by blog_shop.`id_blog` 
                ORDER BY blog.`created_at` DESC
                LIMIT '. $offset . ',' . $items_per_page.'');
        
        return $data;   
    }
    /* 
    *  Breadcrumps
    */
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
        if ($this->cat_name) {
            $breadcrumb['links'][] = array(
                'title' => $this->cat_name,
                'url' => '',
            );
        }
        return $breadcrumb;
    }
    /**
    * Select all the categories
    */ 
    public static function getCategories()
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $id_lang = $context->language->id;

        $data = Db::getInstance(_PS_USE_SQL_SLAVE_)->executes('
            SELECT 
                section_shop.`id_category`, 
                category.`active`,
                section_lang.`name`,
                section_lang.`url`
            FROM '._DB_PREFIX_.'eter_category_shop section_shop
                LEFT JOIN '._DB_PREFIX_.'eter_category category ON (section_shop.id_category = category.id_category)
                LEFT JOIN '._DB_PREFIX_.'eter_category_lang section_lang ON (section_lang.id_category = section_shop.id_category)
            WHERE section_shop.id_shop = '.(int)$id_shop.'
                AND section_lang.id_lang = '.(int)$id_lang);
        foreach ($data as $key => &$value) {
            $post = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('Select count(id_blog) as posts FROM '._DB_PREFIX_.'eter_blog WHERE category = '.$value['id_category']);
            if($post == 0) {
                unset($data[$key]);
            }
        }
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