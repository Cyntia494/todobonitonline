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
include_once(_PS_MODULE_DIR_.'eter_imageslider/classes/Eter_HomeSlide.php');
class ImageSliderModuleImporter
{
	/**
     * Main method
     */
	public function run($theme) {
        $jsonData = file_get_contents(_ETER_IMPORT_DIR_.$theme.'/eter_modules/imageslider/imageslider.json');
        $data = json_decode($jsonData, true);
		$this->truncateTables();
		$this->installSlides($data,$theme);
        return ['success' => true];
	}

    /**
     * Install slides for imagesslider
     */
	public function installSlides($slides,$theme){
        foreach ($slides as $item) {
            $slide = new Eter_HomeSlide();
            $slide->active = $item['active'];
            $slide->position = $item['position'];
            $slide->side = $item['content_position'];
            $slide->image = $item['main_image'];
            $slide->background = $item['backgrond_image'];
            $languages = Language::getLanguages(false);
		        foreach ($languages as $lang) {
		            $slide->title[$lang['id_lang']] = $item['title'];
		            $slide->url[$lang['id_lang']] = $item['target_url'];
		            $slide->legend[$lang['id_lang']] = $item['button_text'];
		            $slide->description[$lang['id_lang']] = $item['description'];
		        }
            if($slide->add()) {
            	$from = _ETER_IMPORT_DIR_.$theme.'/eter_modules/imageslider/'.$item['main_image'];
            	$to = _PS_MODULE_DIR_.'eter_imageslider/images/'.$item['main_image'];
            	@copy($from,$to);
            	$from = _ETER_IMPORT_DIR_.$theme.'/eter_modules/imageslider/'.$item['backgrond_image'];
            	$to = _PS_MODULE_DIR_.'eter_imageslider/images/'.$item['backgrond_image'];
            	@copy($from,$to);
            }
        }
    }

    /**
     * Clear Database
     */
    public function truncateTables() {
        $res = Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'eter_homeslider_slides_lang`;');
        $res = Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'eter_homeslider_slides`;');
        $res = Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'eter_homeslider`;');
    }
}