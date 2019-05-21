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
include_once(_PS_MODULE_DIR_.'eter_categoriesmenu/classes/HomeCategoryMenu.php');
class CategoriesMenuModuleImporter
{
    /**
     * Main method
     */
	public function run($theme) {
        $jsonData = file_get_contents(_ETER_IMPORT_DIR_.$theme.'/eter_modules/categoriesmenu/categoriesmenu.json');
		$data = json_decode($jsonData, true);
        $this->truncateTables();
		$this->installCategories($data,$theme);
        return ['success' => true];
	}

    /**
     * Install categories menu
     */
	public function installCategories($data,$theme) {
        $languages = Language::getLanguages(false);
        $images = [];
        foreach ($data as $item) {
            $homeCategoryMenu = new HomeCategoryMenu();
            $homeCategoryMenu->banner = $item['banner'];
            $homeCategoryMenu->image1 = $item['image1'];
            $homeCategoryMenu->image2 = $item['image2'];
            $images [] = $item['banner'];
            $images [] = $item['image1'];
            $images [] = $item['image2'];
            $homeCategoryMenu->categories = $item['categories'];
            $homeCategoryMenu->active = $item['active'];
            $homeCategoryMenu->position = $item['position'];
            foreach ($languages as $language) {
                $lang = $language['id_lang'];
                $homeCategoryMenu->name[$lang] = $item['name'];
                $homeCategoryMenu->banner_url[$lang] = $item['banner_url'];
                $homeCategoryMenu->image1_url[$lang] = $item['image1_url'];
                $homeCategoryMenu->image2_url[$lang] = $item['image2_url'];
            }
            if($homeCategoryMenu->add()) {
                foreach ($images as $image) {
                    $from = _ETER_IMPORT_DIR_.$theme.'/eter_modules/categoriesmenu/'.$image;
                    $to = _PS_MODULE_DIR_.'eter_categoriesmenu/images/'.$image;
                    @copy($from,$to);
                }
            }
        }
    }

    /**
     * Clear Database
     */
    public function truncateTables() {
        $res = Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'eter_homemenu`;');
        $res = Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'eter_homemenu_shop`;');
        $res = Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'eter_homemenu_lang`;');
    }
}