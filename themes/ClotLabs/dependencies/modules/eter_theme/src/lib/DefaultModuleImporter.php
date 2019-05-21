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

class DefaultModuleImporter
{
    /**
     * Main method, install default info for the shop
     */
	public function run() {
        Configuration::updateValue('PS_SHOP_NAME', "Demo Store");
        Configuration::updateValue('PS_SHOP_EMAIL', "demo@store.com");
        Configuration::updateValue('PS_SHOP_DETAILS', "");
        Configuration::updateValue('PS_SHOP_ADDR1', "240 Jonh Stale Street");
        Configuration::updateValue('PS_SHOP_ADDR2', "Office 2304");
        Configuration::updateValue('PS_SHOP_CODE', "A54629");
        Configuration::updateValue('PS_SHOP_CITY', "Cuahtemoc");
        Configuration::updateValue('PS_SHOP_CONUNTRY_ID', "145");
        Configuration::updateValue('PS_SHOP_STATE_ID', "73");
        Configuration::updateValue('PS_SHOP_PHONE', "7742460273");
        Configuration::updateValue('PS_SHOP_FAX', "");
        $this->truncate();
        $this->installLegal();
        $this->installTerms();
        $this->installSecure();
        $this->installShipping();
        $this->installAboutUs();
        return ['success' => true];
	}
     /**
     * Clean tables
     */
    public function truncate() {
        $res = Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'cms_lang`;');
        $res = Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'cms_shop`;');
        $res = Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'cms`;');
    }

     /**
     * Install legal
     */
    public function installLegal() {
        $url = _ETER_IMPORT_DIR_.'cms/legal.html';
        $data = $data = file_get_contents($url);
        $cms = new CMS();
        $cms->position = 1;
        $cms->indexation = true;
        $cms->id_cms_category = 1;
        $cms->active = true;
        $languages = Language::getLanguages(false); 
        foreach ($languages as $lang) {
            $id_lang = $lang['id_lang'];
            $cms->meta_description[$id_lang] = "";
            $cms->meta_keywords[$id_lang] = "";
            $cms->meta_title[$id_lang] = Context::getContext()->getTranslatorFromLocale($lang['locale'])->trans("Legal Notice");;
            $cms->link_rewrite[$id_lang] = Context::getContext()->getTranslatorFromLocale($lang['locale'])->trans("legal-notice");
            $cms->content[$id_lang] = $data;
        }
        $cms->add();
    }

    /**
     * Install legal
     */
    public function installTerms() {
        $url = _ETER_IMPORT_DIR_.'cms/terms.html';
        $data = $data = file_get_contents($url);
        $cms = new CMS();
        $cms->position = 1;
        $cms->indexation = true;
        $cms->id_cms_category = 1;
        $cms->active = true;
        $languages = Language::getLanguages(false); 
        foreach ($languages as $lang) {
            $id_lang = $lang['id_lang'];
            $cms->meta_description[$id_lang] = "";
            $cms->meta_keywords[$id_lang] = "";
            $cms->meta_title[$id_lang] = Context::getContext()->getTranslatorFromLocale($lang['locale'])->trans("Terms and Conditions");;
            $cms->link_rewrite[$id_lang] = Context::getContext()->getTranslatorFromLocale($lang['locale'])->trans("terms-and-conditions");
            $cms->content[$id_lang] = $data;
        }
        $cms->add();
    }
    /**
     * Install legal
     */
    public function installSecure() {
        $url = _ETER_IMPORT_DIR_.'cms/secure.html';
        $data = $data = file_get_contents($url);
        $cms = new CMS();
        $cms->position = 1;
        $cms->indexation = true;
        $cms->id_cms_category = 1;
        $cms->active = true;
        $languages = Language::getLanguages(false); 
        foreach ($languages as $lang) {
            $id_lang = $lang['id_lang'];
            $cms->meta_description[$id_lang] = "";
            $cms->meta_keywords[$id_lang] = "";
            $cms->meta_title[$id_lang] = Context::getContext()->getTranslatorFromLocale($lang['locale'])->trans("Secure");;
            $cms->link_rewrite[$id_lang] = Context::getContext()->getTranslatorFromLocale($lang['locale'])->trans("secure");
            $cms->content[$id_lang] = $data;
        }
        $cms->add();
    }
    /**
     * Install legal
     */
    public function installShipping() {
        $url = _ETER_IMPORT_DIR_.'cms/shipping.html';
        $data = $data = file_get_contents($url);
        $cms = new CMS();
        $cms->position = 1;
        $cms->indexation = true;
        $cms->id_cms_category = 1;
        $cms->active = true;
        $languages = Language::getLanguages(false); 
        foreach ($languages as $lang) {
            $id_lang = $lang['id_lang'];
            $cms->meta_description[$id_lang] = "";
            $cms->meta_keywords[$id_lang] = "";
            $cms->meta_title[$id_lang] = Context::getContext()->getTranslatorFromLocale($lang['locale'])->trans("Shipping");;
            $cms->link_rewrite[$id_lang] = Context::getContext()->getTranslatorFromLocale($lang['locale'])->trans("shipping");
            $cms->content[$id_lang] = $data;
        }
        $cms->add();
    }
    /**
     * Install legal
     */
    public function installAboutUs() {
        $url = _ETER_IMPORT_DIR_.'cms/aboutus.html';
        $data = $data = file_get_contents($url);
        $cms = new CMS();
        $cms->position = 1;
        $cms->indexation = true;
        $cms->id_cms_category = 1;
        $cms->active = true;
        $languages = Language::getLanguages(false); 
        foreach ($languages as $lang) {
            $id_lang = $lang['id_lang'];
            $cms->meta_description[$id_lang] = "";
            $cms->meta_keywords[$id_lang] = "";
            $cms->meta_title[$id_lang] = Context::getContext()->getTranslatorFromLocale($lang['locale'])->trans("About Us");;
            $cms->link_rewrite[$id_lang] = Context::getContext()->getTranslatorFromLocale($lang['locale'])->trans("about-us");
            $cms->content[$id_lang] = $data;
        }
        $cms->add();
    }
}