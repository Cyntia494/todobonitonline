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
include_once(_PS_MODULE_DIR_.'eter_clients/classes/Customers.php');
class ClientsModuleImporter
{
	/**
     * Main method
     */
	public function run($theme) {
        $jsonData = file_get_contents(_ETER_IMPORT_DIR_.$theme.'/eter_modules/clients/clients.json');
        $data = json_decode($jsonData, true);
		$this->truncateTables();
		$this->installCustomersReview($data,$theme);
        return ['success' => true];
	}
	/**
     * Add new clients review
     */
	public function installCustomersReview($clients,$theme) {
		$shops = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * FROM '._DB_PREFIX_.'shop');
		foreach ($shops as $key => $shop) {
			$languages = Language::getLanguages(true,$shop['id_shop']);
			foreach ($clients as $key => $client) {
				$customer = new Customers();
				$customer->picture = $client['image'];
				$customer->logo = $client['logo'];
				$customer->id_shop = $shop['id_shop'];
				foreach ($languages as $language) {
                    $lang = $language['id_lang'];
                    $customer->name[$lang] = $client['name'];
                    $customer->comment[$lang] = $client['comment'];
                    $customer->charge[$lang] = $client['charge'];;
                }
                if ($customer->add()) {
                	$from = _ETER_IMPORT_DIR_.$theme.'/eter_modules/clients/'.$client['image'];
            		$to = _PS_MODULE_DIR_.'eter_clients/images/'.$client['image'];
            		@copy($from,$to);
            		$from = _ETER_IMPORT_DIR_.$theme.'/eter_modules/clients/'.$client['logo'];
            		$to = _PS_MODULE_DIR_.'eter_clients/images/'.$client['logo'];
            		@copy($from,$to);
                }
			}
		}
	}
	/**
     * Clear Database
     */
    public function truncateTables() {
        $res = Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'eter_customer_reviews_shop`;');
        $res = Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'eter_customer_reviews`;');
        $res = Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'eter_customer_reviews_lang`;');
    }
}