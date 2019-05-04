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
class LinkListModuleImporter
{
    /**
     * Main method
     */
	public function run()
    {
        $this->translator = Context::getContext()->getTranslator();
		$this->truncateTables();
		$this->install();
        return ['success' => true];
	}
    
    /**
     * Instal new link list
     */
    public function install()
    {
        Db::getInstance()->insert('link_block', $this->getNode1());
        Db::getInstance()->insert('link_block', $this->getNode2());
        Db::getInstance()->insert('link_block', $this->getNode3());

        foreach (Language::getLanguages(false) as $lang) {
            Db::getInstance()->insert('link_block_lang', $this->getNode1Lang($lang['id_lang'],$lang['locale']));
            Db::getInstance()->insert('link_block_lang', $this->getNode2Lang($lang['id_lang'],$lang['locale']));
            Db::getInstance()->insert('link_block_lang', $this->getNode3Lang($lang['id_lang'],$lang['locale']));
        }
    }
    /**
     * Return data for node1
     */
    public function getNode1() 
    {
        $pages['cms'] = [false];
        $pages['product'] = ["prices-drop","new-products","best-sales"];
        $pages['static'] = ["contact","stores"];

        $data['id_link_block'] = 1;
        $data['id_hook'] = (int)Hook::getIdByName('displayFooter');
        $data['position'] = 1;
        $data['content'] = json_encode($pages);
        return $data;
    }
    /**
     * Return data for node1
     */
    public function getNode1Lang($id_lang,$locale) 
    {
        $data['id_link_block'] = 1;
        $data['id_lang'] = $id_lang;
        $data['name'] = $this->translator->trans('Products', array(), 'Modules.Linklist.Shop', $locale);
        return $data;
    }
    /**
     * Return data for node1
     */
    public function getNode2() 
    {
        $pages['cms'] = ["1","2","3","4","5"];
        $pages['product'] = [false];
        $pages['static'] = [false];

        $data['id_link_block'] = 2;
        $data['id_hook'] = (int)Hook::getIdByName('displayFooter');
        $data['position'] = 2;
        $data['content'] = json_encode($pages);
        return $data;
    }
    /**
     * Return data for node1
     */
    public function getNode2Lang($id_lang,$locale) 
    {
        $data['id_link_block'] = 2;
        $data['id_lang'] = $id_lang;
        $data['name'] = $this->translator->trans('Legal', array(), 'Modules.Linklist.Shop', $locale);
        return $data;
    }
    /**
     * Return data for node1
     */
    public function getNode3() 
    {
        $pages['cms'] = [false];
        $pages['product'] = [false];
        $pages['static'] = ["contact","sitemap","stores","my-account"];

        $data['id_link_block'] = 3;
        $data['id_hook'] = (int)Hook::getIdByName('displayFooter');
        $data['position'] = 3;
        $data['content'] = json_encode($pages);
        return $data;
    }
    /**
     * Return data for node1
     */
    public function getNode3Lang($id_lang,$locale) 
    {
        $data['id_link_block'] = 3;
        $data['id_lang'] = $id_lang;
        $data['name'] = $this->translator->trans('Static', array(), 'Modules.Linklist.Shop', $locale);
        return $data;
    }
    /**
     * Delete current link list
     */
    public function truncateTables(){
        Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'link_block`');
        Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'link_block_lang`');
        Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_.'link_block_shop`');
    }

}