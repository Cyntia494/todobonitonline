<?php
/**
 * @author Eduardo Garcia <eduardogb@boedots.com>
 * @category Boedots
 *
 **/

class Block extends ObjectModel
{	
	public $id_shop;
	public $image;
	public $position;
	public $active;
	public $title;
	public $details;
	public $url_name;
	public $url;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'eter_blocks',
		'primary' => 'id_block',
		'multilang' => true,
		'fields' => array(
			'image' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 255),
			'active' =>	array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position' =>	array('type' => self::TYPE_INT, 'required' => true),
			// Lang fields
			'title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml'),
			'details' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
			'url_name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
			'url' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'size' => 255),
		)
	);
	/**
     * Get table informations
     */
	public	function __construct($id = null, $id_lang = null, $id_shop = null, Context $context = null)
	{
		parent::__construct($id, $id_lang, $id_shop);
	}
	/**
     * Get table informations
     */
	public function add($autodate = true, $null_values = false)
	{
		$context = Context::getContext();
		$id_shop = ($this->id_shop) ? $this->id_shop : $context->shop->id;
		$res = parent::add($autodate, $null_values);
		$res &= Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'eter_blocks_shop` (`id_shop`, `id_block`) VALUES('.(int)$id_shop.', '.(int)$this->id.')');
		return $res;
	}
	/**
     * Get table informations
     */
	public function delete()
	{
		$res = true;
		$res &= Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'eter_blocks` WHERE `id_block` = '.(int)$this->id);
		$res &= parent::delete();
		return $res;
	}
	/**
     * Get table informations
     */
    public static function getData($active = null)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $id_lang = $context->language->id;
        $data = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT 
                hs.`id_block`, 
                hss.`active`,
                hss.`position`,
                hss.`image`,
                hssl.`title`, 
                hssl.`details`,
                hssl.`url_name`,
                hssl.`url`
            FROM '._DB_PREFIX_.'eter_blocks_shop hs
                LEFT JOIN '._DB_PREFIX_.'eter_blocks hss ON (hs.id_block = hss.id_block)
                LEFT JOIN '._DB_PREFIX_.'eter_blocks_lang hssl ON (hss.id_block = hssl.id_block)
            WHERE hs.id_shop = '.(int)$id_shop.'
                AND hssl.id_lang = '.(int)$id_lang.'
        	ORDER BY hss.`position` ASC');
        return $data;
    }
    /**
     * get form values
     */
    public static function getAddFieldsValues()
    {
        $fields = array();
        if (Tools::isSubmit('id_block')) {
            $data = new Block((int)Tools::getValue('id_block'));
            $fields['id_block'] = (int)$data->id;
        } else {
            $data = new Block();
        }

        $fields['active'] = Tools::getValue('active',$data->active);
        $fields['position'] = Tools::getValue('active',$data->position);
        $fields['image'] = Tools::getValue('image',$data->image);
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $fields['title'][$lang['id_lang']] = Tools::getValue('title_'.(int)$lang['id_lang'],$data->title[$lang['id_lang']]);
            $fields['details'][$lang['id_lang']] = Tools::getValue('details_'.(int)$lang['id_lang'], $data->details[$lang['id_lang']]);
            $fields['url_name'][$lang['id_lang']] = Tools::getValue('url_name_'.(int)$lang['id_lang'], $data->url_name[$lang['id_lang']]);
            $fields['url'][$lang['id_lang']] = Tools::getValue('url_'.(int)$lang['id_lang'], $data->url[$lang['id_lang']]);
        }
        return $fields;
    }
    /**
     * Upload image
     */
    public function uploadImage($name) {
        try {
            $basefolder = _PS_IMG_DIR_.$this->_img_path;
            if(!file_exists($basefolder)) {
                mkdir($basefolder,'0777',true);
            }

            $type = Tools::strtolower(Tools::substr(strrchr($_FILES[$name]['name'], '.'), 1));
            $imagesize = @getimagesize($_FILES[$name]['tmp_name']);
            if (isset($_FILES[$name]) &&
                isset($_FILES[$name]['tmp_name']) &&
                !empty($_FILES[$name]['tmp_name']) &&
                !empty($imagesize) &&
                in_array(
                    Tools::strtolower(Tools::substr(strrchr($imagesize['mime'], '/'), 1)), array(
                        'jpg',
                        'gif',
                        'jpeg',
                        'png'
                    )
                ) &&
                in_array($type, array('jpg', 'gif', 'jpeg', 'png'))
            ) {
                $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                $salt = sha1(microtime());
                if ($error = ImageManager::validateUpload($_FILES[$name])) {
                    $errors[] = $error;
                } elseif (!$temp_name || !move_uploaded_file($_FILES[$name]['tmp_name'], $temp_name)) {
                    return false;
                } elseif (!ImageManager::resize($temp_name, $basefolder.$_FILES[$name]['name'], null, null, $type)) {
                    $this->errors = $this->displayError($this->l('An error occurred during the image upload process.'));
                }
                if (isset($temp_name)) {
                    @unlink($temp_name);
                }
                return $this->_img_path.$_FILES[$name]['name'];
            }
        } catch (Exception $e) {
            return "none";   
        }       
    }
}
