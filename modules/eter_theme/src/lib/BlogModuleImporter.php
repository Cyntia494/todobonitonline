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
* @package    Eter_Demos
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/

if (!defined('_PS_VERSION_')) {
	exit;
}
/**
* Import class
*/
include_once(_PS_MODULE_DIR_.'eter_blog/classes/blog.php');
include_once(_PS_MODULE_DIR_.'eter_blog/classes/category.php');
class BlogModuleImporter
{	
	public $data = null;
	/**
	* Main method
	*/
	public function run($theme)
	{
		$jsonData = file_get_contents(_ETER_IMPORT_DIR_.$theme.'/eter_modules/blogs/blogs.json');
		$data = json_decode($jsonData, true);
		$this->truncateTables();
		$this->installBlogCategories($data['categories']);
		$this->installBlogs($data['posts'],$data['content'],$theme);
		return ['success' => true];
	}
	/**
	* Install blogs
	*/
	public function installBlogs($posts,$html,$theme)
	{
		$languages = Language::getLanguages(false);
		foreach ($posts as $post) {
			$obj = new SectionBlog(); 
            $obj->active = $post['active'];
            $obj->category = $post['category'];
            $obj->banner = $post['image'];  
            $obj->products = "1,2,3,5,4,6,7,8,9";               
            $obj->created_at = date('Y-m-d');
            foreach ($languages as $lang) {
                $obj->url[$lang['id_lang']] = $post['url'];
                $obj->name[$lang['id_lang']] = $post['name'];
                $obj->details[$lang['id_lang']] = $post['details'];
                $obj->html[$lang['id_lang']] = $html['text'];
            }
			if($obj->save()){
				$from = _ETER_IMPORT_DIR_.$theme.'/eter_modules/blogs/'.$post['image'];
				$to = _PS_MODULE_DIR_.'eter_blog/images/'.$post['image'];
				@copy($from,$to);
			}
		}
	}
	/**
	* Install categories for blogs
	*/
	public function installBlogCategories($categories)
	{
		$languages = Language::getLanguages(false);
		foreach ($categories as $category) {
			$obj = new SectionCategory(); 
			$obj->active = $category['active'];
			$obj->url = $category['url'];  
			foreach ($languages as $lang) {
				$obj->name[$lang['id_lang']] = $category['name'];
				$obj->resumen[$lang['id_lang']] = $category['resumen'];
			}
			$obj->save();
		}
	}
	/**
     * Clear Database
     */
    public function truncateTables() 
    {
    	Db::getInstance()->execute('SET FOREIGN_KEY_CHECKS = 0; ');
        Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'eter_category_lang`;');
        Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'eter_category_shop`;');
        Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'eter_category`;');
        Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'eter_blog_lang`;');
        Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'eter_blog_shop`;');
        Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'eter_blog`;');
        Db::getInstance()->execute('SET FOREIGN_KEY_CHECKS = 1; ');
    }
}