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

class InstallSteps
{
	public function __construct($link) 
	{
		$this->link = $link;
	}
	/**
     *  Execute method by step
     */
	public function installModuleStep($module,$theme) 
	{
		switch ($module) {
			case 'default':
	            $model  = new DefaultModuleImporter();
	            return $model->run();  
				break;
			case 'ps_linklist':
	            $model  = new LinkListModuleImporter();
	            return $model->run();  
				break;
			case 'ps_socialfollow':
	            $model  = new SocialFollowModuleImporter();
	            return $model->run();  
				break;
			case 'eter_advertisement':
	            $model  = new AdvertisementModuleImporter();
	            return $model->run($theme);  
				break;
			case 'eter_banner1':
	            $model  = new Banner1ModuleImporter();
	            return $model->run($theme);
				break;
			case 'eter_banner2':
	            $model  = new Banner2ModuleImporter();
	            return $model->run($theme);
				break;
			case 'eter_blog':
	            $model  = new BlogModuleImporter();
	            return $model->run($theme);
				break;
			case 'eter_brands':
	            $model  = new BrandsModuleImporter();
	            return $model->run($theme);
				break;
			case 'eter_categorieslinks':
	            $model  = new CategoriesLinksModuleImporter();
	            return $model->run($theme);
				break;
			case 'eter_categoriesmenu':
	            $model  = new CategoriesMenuModuleImporter();
	            return $model->run($theme);
				break;
			case 'eter_clients':
	            $model  = new ClientsModuleImporter();
	            return $model->run($theme);
				break;
			case 'eter_homeblocks':
	            $model = new HomeBlocksModuleImporter();
	            return $model->run($theme);
				break;
			case 'eter_homelinks':
	            $model = new HomeLinksModuleImporter();
	            return $model->run($theme);
				break; 
			case 'eter_imageslider':
	            $model  = new ImageSliderModuleImporter();
	            return $model->run($theme);
				break;
			case 'eter_imagesgallery':
	            $model  = new ImagesGalleryModuleImporter();
	            return $model->run($theme);
				break;
			case 'eter_popup':
	            $from = _ETER_IMPORT_DIR_.$theme.'/eter_modules/popup/popup.jpg';
	            $to = _PS_MODULE_DIR_.'eter_popup/images/popup.jpg';
	            if(@copy($from,$to)){
	                Configuration::updateValue('POPUP_IMAGE_NAME', "popup.jpg");
	            }
	            return ['success' => true];
				break;
		}
	}
	/**
     *  Return available category steps to install
     */
	public function installAccessories($product)
	{
		$productsImporterModel = new productsImporter();
		return $productsImporterModel->installAccessories($product);
	}
	/**
     *  Return available category steps to install
     */
	public function installProduct($product)
	{
		$productsImporterModel = new productsImporter();
		return $productsImporterModel->installProduct($product);
	}
	/**
     *  Return available category steps to install
     */
	public function installFeature($feature)
	{
		$productsImporterModel = new productsImporter();
		return $productsImporterModel->installFeature($feature);
	}
	/**
     *  Return available category steps to install
     */
	public function installAttribute($attribute)
	{
		$productsImporterModel = new productsImporter();
		return $productsImporterModel->installAttribute($attribute);
	}
	
	/**
     *  Return available category steps to install
     */
	public function cleanProducts()
	{
		$productsImporterModel = new productsImporter();
		return $productsImporterModel->cleanProducts();
	}
	/**
     *  Return available category steps to install
     */
	public function cleanCategories()
	{
		$categoriesModel = new CategoriesImporter();
		return $categoriesModel->cleanData();
	}
	/**
     *  Return available category steps to install
     */
	public function installCategoryStep($data)
	{
		$categoriesModel = new CategoriesImporter();
		return $categoriesModel->installCategory($data);
	}
	
	/**
     *  Return available category steps to install
     */
	public function getCategoriesSteps()
	{
		$categoriesModel = new CategoriesImporter();
		$categories = $categoriesModel->prepareData($this->theme);
		$data['steps'] = $categories;
		$data['total'] = count($categories);
		$data['increase'] = 100/count($categories);
		return $data;
	}
	/**
     *  Return available category steps to install
     */
	public function getCategoriesCleanSteps()
	{
		$steps[] = [
            'url' => $this->link->getAdminLink('AdminDemos',true,[],['ajax'=>1,'cleanCategories'=>1])
		];
		$data['steps'] = $steps;
		$data['total'] = count($steps);
		$data['increase'] = 100/count($steps);
		return $data;
	}
	/**
     *  Return available category steps to install
     */
	public function getProductsCleanSteps()
	{
		$steps[] = [
            'url' => $this->link->getAdminLink('AdminDemos',true,[],['ajax'=>1,'cleanProducts'=>1])
		];
		$data['steps'] = $steps;
		$data['total'] = count($steps);
		$data['increase'] = 100/count($steps);
		return $data;
	}
	/**
     *  Return available modules steps to install
     */
	public function getModulesSteps()
	{
		$modules = $this->getEterModulesSteps();
		$data['steps'] = $modules;
		$data['total'] = count($modules);
		$data['increase'] = 100/count($modules);

		return $data;
	}
	/**
     *  Return available steps to install
     */
	public function getInstallSteps($theme)
	{
		$this->theme = $theme;

		$data['modules'] = $this->getModulesSteps();
		$data['cleancategories'] = $this->getCategoriesCleanSteps();
		$data['categories'] = $this->getCategoriesSteps();

		$productsModel = new productsStepsImporter();
		$productsModel->prepareData($this->theme);
		$data['cleanproducts'] = $this->getProductsCleanSteps();
		$data['features'] = $productsModel->getProductsFeatureSteps();
		$data['attributes'] = $productsModel->getProductsAttributesSteps();
		$data['products'] = $productsModel->getProductsSteps();
		$data['accessories'] = $productsModel->getAccesoriesSteps();
		
		return $data;
	}
	/**
     *  Return available modules steps to install
     */
	public function getEterModulesSteps() 
	{
		$steps = [];
		//ps modules
		$this->addPsStepModule($steps,'default', "Store Data");
		$this->addPsStepModule($steps,'ps_linklist', "Link List");
		$this->addPsStepModule($steps,'ps_socialfollow', "Social Data");
		//Eterlabs modules
		$this->addEterStepModule($steps,'eter_advertisement', "Advertisement");
		$this->addEterStepModule($steps,'eter_banner1', "Banner 1");
		$this->addEterStepModule($steps,'eter_banner2', "Banner 2");
		$this->addEterStepModule($steps,'eter_blog', "Blog");
		$this->addEterStepModule($steps,'eter_brands', "Brands");
		$this->addEterStepModule($steps,'eter_categorieslinks', "Category Links");
		$this->addEterStepModule($steps,'eter_categoriesmenu', "Category Menu");
		$this->addEterStepModule($steps,'eter_clients', "Clients");
		$this->addEterStepModule($steps,'eter_homelinks', "Home Links");
		$this->addEterStepModule($steps,'eter_homeblocks', "Home Blocks");
		$this->addEterStepModule($steps,'eter_imageslider', "Image Slider");
		$this->addEterStepModule($steps,'eter_popup', "Pop Up");
		return $steps;
	}
	/**
     *  Add the module to steps if module is enabled
     */
	public function addEterStepModule(&$steps,$module,$title) 
	{
		if( Module::isInstalled($module)) {
			$type['name'] = $module;
			$type['type'] = "module";
            	$type['title'] = $title;
            	$type['url'] = $this->link->getAdminLink('AdminDemos',true,[],['ajax'=>1,'installModule'=>$module,'installTheme'=>$this->theme]);
			$steps[] = $type;
		}
	}
	/**
     *  Add the module to steps if module is enabled
     */
	public function addPsStepModule(&$steps,$module,$title) 
	{
		$type['name'] = $module;
		$type['type'] = "module";
        	$type['title'] = $title;
        	$type['url'] = $this->link->getAdminLink('AdminDemos',true,[],['ajax'=>1,'installModule'=>$module,'installTheme'=>$this->theme]);
		$steps[] = $type;
	}
}