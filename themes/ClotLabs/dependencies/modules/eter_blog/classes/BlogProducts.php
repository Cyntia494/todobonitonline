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
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Product\ProductPresentationSettings;
class BlogProducts 
{   
    public function __construct($module)
    {
        $this->module = $module;
    }
    /**
    * Get product Crossales
    *
    * @param int $id_lang Language id
    * @return array Product accessories
    */
    public function getProducts($parents,$limit = 5,$id_lang, $active = true)
    {
        $id_shop = Context::getContext()->shop->id;
        $active = 1;
        $sql = '
            SELECT
                p.*, 
                ps.*, 
                stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, 
                pl.`description`, 
                pl.`description_short`, 
                pl.`link_rewrite`,
                pl.`meta_description`, 
                pl.`meta_keywords`, 
                pl.`meta_title`, 
                pl.`name`, 
                pl.`available_now`, 
                pl.`available_later`,
                isp.`id_image` id_image, 
                il.`legend`, 
                m.`name` as manufacturer_name, 
                cl.`name` AS category_default, 
                IFNULL(pas.id_product_attribute, 0) id_product_attribute,
                DATEDIFF(
                p.`date_add`,
                DATE_SUB(
                "'.date('Y-m-d').' 00:00:00",
                INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
                )
                ) > 0 AS new
            FROM `'._DB_PREFIX_.'product_shop` ps 
                LEFT JOIN `'._DB_PREFIX_.'product` p ON (p.id_product = ps.id_product)
                LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` pas ON (ps.id_product = pas.id_product AND pas.default_on = 1)
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.id_product = pl.id_product AND pl.id_lang = '.(int)$id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (ps.id_category_default = cl.id_category AND cl.id_lang = '.(int)$id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'image_shop` isp ON (isp.id_product = ps.id_product AND isp.cover=1)
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (isp.id_image = il.id_image AND il.id_lang = '.(int)$id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (p.id_manufacturer= m.id_manufacturer)
                '.Product::sqlStock('p', 0).'
            WHERE ps.id_product in ('.$parents.') 
                AND ps.active = 1 
                AND ps.id_shop = '.$id_shop.'
                AND ps.visibility != \'none\'
                ORDER BY RAND()
                LIMIT '.$limit.'
        ';

        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('SET SESSION sql_mode = \'\'');
        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return false;
        }
        foreach ($result as &$row) {
            $row['id_product_attribute'] = Product::getDefaultAttribute((int)$row['id_product']);
        }
        return Product::getProductsProperties($id_lang, $result);
    }

    /**
    * Display crossales options in cart page in front 
    */
    public function getBlogProducts($products)
    {
        try {
            $context = Context::getContext();
            $productList = $this->getProducts($products,3,$context->language->id);

            $presenter = new ProductListingPresenter(
                new ImageRetriever(
                    $context->link
                ),
                $context->link,
                new PriceFormatter(),
                new ProductColorsRetriever(),
                $this->module->getTranslator()
            );
            $presentationSettings = $this->getProductPresentationSettings();
            if (is_array($productList)) {
                foreach ($productList as &$listItem) {
                    $listItem = $presenter->present(
                        $presentationSettings,
                        Product::getProductProperties($context->language->id, $listItem, $context),
                        $context->language
                    );
                }
            }
            return $productList;
        } catch (Exception $e) {
            return false;            
        }
    }

    /**
    * Get presentation settings
    *
    * @return ProductPresentationSettings
    */
    public function getProductPresentationSettings()
    {
        $settings = new ProductPresentationSettings();

        $settings->catalog_mode = Configuration::isCatalogMode();
        $settings->allow_add_variant_to_cart_from_listing = (int) Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY');
        $settings->stock_management_enabled = Configuration::get('PS_STOCK_MANAGEMENT');
        $settings->showPrices = Configuration::showPrices();
        $settings->lastRemainingItems = Configuration::get('PS_LAST_QTIES');

        return $settings;
    }
}