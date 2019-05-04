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
* @package    Eter_ImageSlider
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/

class Eter_HomeSlide extends ObjectModel
{
	public $image;
	public $background;
	public $title;
	public $url;
	public $legend;
	public $description;
	public $active;
	public $side;
	public $position;
	public $id_shop;

	/**
	 * ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'eter_homeslider_slides',
		'primary' => 'id_homeslider_slides',
		'multilang' => true,
		'fields' => array(
			'active' =>			array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position' =>		array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'side' =>		array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'image' =>			array('type' => self::TYPE_STRING,'validate' => 'isCleanHtml', 'size' => 255),
			'background' =>			array('type' => self::TYPE_STRING,'validate' => 'isCleanHtml', 'size' => 255),

			// Lang fields
			'description' =>	array('type' => self::TYPE_HTML, 'lang' => true, 'size' => 4000),
			'title' =>			array('type' => self::TYPE_HTML, 'lang' => true, 'size' => 255),
			'legend' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
			'url' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'required' => true, 'size' => 255),
		)
	);

	/**
	 * ObjectModel::Construct
	 */
	public	function __construct($id_slide = null, $id_lang = null, $id_shop = null, Context $context = null)
	{
		parent::__construct($id_slide, $id_lang, $id_shop);
	}

	/**
	 * ObjectModel::Add
	 */
	public function add($autodate = true, $null_values = false)
	{
		$context = Context::getContext();
		$id_shop = $context->shop->id;

		$res = parent::add($autodate, $null_values);
		$res &= Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'eter_homeslider` (`id_shop`, `id_homeslider_slides`)
			VALUES('.(int)$id_shop.', '.(int)$this->id.')'
		);
		return $res;
	}

	/**
	 * ObjectModel::Delete
	 */
	public function delete()
	{
		$res = true;

		$image = $this->image;
		if (preg_match('/sample/', $image) === 0)
		if ($image && file_exists(dirname(__FILE__).'/images/'.$image))
			$res &= @unlink(dirname(__FILE__).'/images/'.$image);
		$res &= Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'eter_homeslider`
			WHERE `id_homeslider_slides` = '.(int)$this->id
		);

		$res &= parent::delete();
		return $res;
	}
	
	public static function getAssociatedIdsShop($id_slide)
	{
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hs.`id_shop`
			FROM `'._DB_PREFIX_.'eter_homeslider` hs
			WHERE hs.`id_homeslider_slides` = '.(int)$id_slide
		);

		if (!is_array($result))
			return false;

		$return = array();

		foreach ($result as $id_shop)
			$return[] = (int)$id_shop['id_shop'];

		return $return;
	}
	/**
     * Get slides
     */
    public function getData($active = null)
    {
      	$context = Context::getContext();
        $id_shop = $context->shop->id;
        $id_lang = $context->language->id;

        $sql = '
            SELECT * FROM '._DB_PREFIX_.'eter_homeslider hs
            	LEFT JOIN '._DB_PREFIX_.'eter_homeslider_slides hss 
            		ON (hs.id_homeslider_slides = hss.id_homeslider_slides)
            	LEFT JOIN '._DB_PREFIX_.'eter_homeslider_slides_lang hssl 
            		ON (hss.id_homeslider_slides = hssl.id_homeslider_slides)
            WHERE id_shop = '.(int)$id_shop.'
            	AND hssl.id_lang = '.(int)$id_lang.
            	($active ? ' AND hss.`active` = 1' : ' ').'
            	ORDER BY hss.position';
        $slides = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        return $slides;
    }
    /**
     * Get values for config form
     */
    public function getAddFieldsValues()
    {
        $fields = array();

        if (Tools::isSubmit('id_homeslider_slides')) {
            $slide = new Eter_HomeSlide((int)Tools::getValue('id_homeslider_slides'));
            $fields['id_homeslider_slides'] = (int)Tools::getValue('id_homeslider_slides', $slide->id);
        } else {
            $slide = new Eter_HomeSlide();
        }

        $fields['active'] = Tools::getValue('active_slide', $slide->active);
        $fields['has_picture'] = true;
        $fields['side'] = $slide->side;
        $fields['background'] = Tools::getValue('background');
        $fields['image'] = Tools::getValue('image');

        $languages = Language::getLanguages(false);

        foreach ($languages as $lang) {
            $fields['title'][$lang['id_lang']] = Tools::getValue('title_'.(int)$lang['id_lang'], $slide->title[$lang['id_lang']]);
            $fields['url'][$lang['id_lang']] = Tools::getValue('url_'.(int)$lang['id_lang'], $slide->url[$lang['id_lang']]);
            $fields['legend'][$lang['id_lang']] = Tools::getValue('legend_'.(int)$lang['id_lang'], $slide->legend[$lang['id_lang']]);
            $fields['description'][$lang['id_lang']] = Tools::getValue('description_'.(int)$lang['id_lang'], $slide->description[$lang['id_lang']]);
        }

        return $fields;
    }
    /**
     * Get las posicion for slides
     */
    public function getLastPosition(){
    	$sql = 'SELECT position FROM '._DB_PREFIX_.'eter_homeslider_slides';
    	$positions = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    	$maxPosition = 0;
    	foreach ($positions as $position) {
    		if($position['position']>$maxPosition){
    			$maxPosition = $position['position'];
    		}
    	}
    	return (int)$maxPosition;
    }
    /**
     * Reorder positions when slide has been deleted 
     */
    public function reorderPosition (){
    	$sql = 'SELECT id_homeslider_slides FROM '._DB_PREFIX_.'eter_homeslider_slides ORDER BY position';
    	$ids = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    	foreach ($ids as $key => $id) {
    		$slide = new Eter_HomeSlide((int)$id['id_homeslider_slides']);
            $slide->position = $key;
            $slide->save();
    	}
    }
}