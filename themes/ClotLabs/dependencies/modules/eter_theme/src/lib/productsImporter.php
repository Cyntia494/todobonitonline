<?php

if (!defined('_PS_VERSION_')) {
	exit;
}
class productsImporter
{	
	/**
     * Add attributes
     */
    public function installAccessories($data) 
    {
    	$sql = 'SELECT `id_product` FROM `' . _DB_PREFIX_ . 'product` p WHERE p.reference = "' . pSQL($data['sku']) . '"';
    	$id = Db::getInstance()->getValue($sql);
        if ($id) {
        	$ids = Db::getInstance()->executeS('SELECT id_product FROM '._DB_PREFIX_.'product where reference in ("'.str_replace(',','","', $data['accessories']).'")');
        	$accessories = [];
        	foreach ($ids as $key => $value) {
        		$accessories[]['id'] = $value['id_product'];
            }
        	if($accessories) {
                $product = new Product($id);
	    		$product->setWsAccessories($accessories);
        	}
        }
    	return ['success' => true];
    }
	/**
     * Add attributes
     */
    public function installProduct($data) 
    {
    	//get Combinations
    	$combinations = $data['combinations'];
    	unset($data['combinations']);
    	//get Customizations
    	$customizable = $data['customizable'];
    	unset($data['customizable']);
    	//get Features
    	$features = $data['features'];
    	unset($data['features']);
    	//get Attacheds
    	$attacheds = $data['attacheds'];
    	unset($data['attacheds']);
    	//get Virtual
    	$virtual = $data['virtual'];
    	unset($data['virtual']);
        //get Virtual
        $images = null;
        if (isset($data['images'])) {
            $images = $data['images'];
            unset($data['images']);
        }
    	//get Categories
    	$categories = $data['category'];
    	$dafaultCat = $data['CategoryDefault'];
    	unset($data['CategoryDefault']);
    	unset($data['category']);
    	//unset url
    	unset($data['url']);
        
        $product = new Product();
		foreach ($data as $key => $value) {
			$product->{$key} = $value;
		}
		$product->id_category_default = $this->getDefaultCategoryId($dafaultCat);
		if($product->save()) {
            $this->addProductImages($product,[$data['cover']],$data['sname'],true);
			$this->addProductImages($product,$images,$data['sname']);
			$this->addProductCategories($product,$categories);
			$this->addProductFeatures($product,$features);
			$this->addProductCombinations($product,$combinations);
		}
		return ['success' => true];
    }
    /**
     * Add categories
     */
    public function addProductCombinations($product,$combinations) 
    {
        if($combinations) {
            foreach ($combinations as $combination) {
                $this->addProductCombination($product,$combination);
            }
        }
    }
    /**
     * Add product combination
     */
    public function addProductCombination($product,$data) 
    {
        $combinationAttributes = [];
        foreach ($data['attributes'] as $key => $value) {
    		$sql = 'SELECT * FROM '._DB_PREFIX_.'attribute a
    				LEFT JOIN '._DB_PREFIX_.'attribute_group ag on (a.id_attribute_group = ag.id_attribute_group)
    				WHERE a.attributev_code = "'.$value.'" and ag.attribute_code = "'.$key.'"';
    		$attribute = Db::getInstance()->getRow($sql);
    		if ($attribute) {
    			$combinationAttributes[] = $attribute['id_attribute'];
    		}
    	}
        if (!empty($combinationAttributes) && !$product->productAttributeExists($combinationAttributes)) {
            $idImages = [];
            $location = null;
            $idImages = $this->addProductImages($product,$data['images'],$data['sname']);
            $idProductAttribute = $product->addProductAttribute(
            	$data['price'], 
            	$data['weight'],
            	$data['unit_price_impact'], 
            	$data['ecotax'],  
            	$data['quantity'],
            	$idImages,
            	$data['reference'],
            	$data['supplier_reference'],
            	$data['ean13'],
            	(int)$data['default_on'],
            	$location,
            	$data['upc'],
            	(int)$data['minimal_quantity'],
            	$data['isbn'],
            	(int)$data['low_stock_threshold'],
            	(int)$data['low_stock_alert']
            );
            $product->addAttributeCombinaison($idProductAttribute, $combinationAttributes);
            $combination = new Combination($idProductAttribute);
            $combination->setImages($idImages);
        }
    }
    /**
     * Add categories
     */
    public function addProductFeatures($product,$features) 
    {
    	foreach ($features as $key => $value) {
    		$sql = 'SELECT * FROM '._DB_PREFIX_.'feature_value fv
    				LEFT JOIN '._DB_PREFIX_.'feature f on (fv.id_feature = f.id_feature)
    				WHERE fv.featurev_code = "'.$value.'" and f.feature_code = "'.$key.'"';

    		$featureValue = Db::getInstance()->getRow($sql);
    		if ($featureValue) {
    			$idFeature = $featureValue['id_feature'];
    			$idFeatureValue = $featureValue['id_feature_value'];
    			$product->addFeatureProductImport($product->id,$idFeature,$idFeatureValue);
    		}
    	}
    }
    /**
     * Add categories
     */
    public function getDefaultCategoryId($dafaultCat) 
    {
    	$id = Db::getInstance()->getValue('SELECT id FROM '._DB_PREFIX_.'categories_import where code like "%'.$dafaultCat.'%"');
    	if (!$id) {
    		$id = Db::getInstance()->getValue('SELECT id FROM '._DB_PREFIX_.'categories_import');
    	}
    	return $id;
    }
    /**
     * Add categories
     */
    public function addProductCategories($product,$categories) 
    {
    	if ($categories == "All" || $categories == "All") {
    		$data = Db::getInstance()->executeS('SELECT id FROM '._DB_PREFIX_.'categories_import');
    	} else {
    		$codes = implode(',', $categories);
    		$data = Db::getInstance()->executeS('SELECT id FROM '._DB_PREFIX_.'categories_import where code in ('.$codes.')');
    	}
    	$categoriesIds = [];
    	foreach ($data as $key => $value) {
    		if ($value['id'] > 0) {
    			$categoriesIds[] = $value['id'];
    		}
    	}
    	$product->addToCategories($categoriesIds);
    }
    /**
     * Add attributes
     */
    public function addProductImages($product,$images,$imageName,$cover = false) 
    {
        $ids = [];
        if($images) {
            foreach ($images as $key => $image) {
                $imageObj = new Image();
                $imageObj->id_product = $product->id;
                $imageObj->position = Image::getHighestPosition($product->id) + 1;
                $imageObj->cover = $cover;
                $imageObj->legend = $imageName;
                if ($imageObj->save()) {
                    if (productsImporter::copyImg($product->id, $imageObj->id, $image, 'products', true)) {
                        $ids[] = $imageObj->id;
                    } else {
                        $imageObj->delete();
                    }
                }
            }
         }
        return $ids;
    }
	/**
     * Add attributes
     */
    public function installAttribute($attribute) 
    {
        $newGroup = new AttributeGroup();
        $title = $this->createMultiLangField('code',$attribute);
        $newGroup->name = $title;
        $newGroup->public_name = $title;
        $newGroup->group_type = $attribute['type'];
        if ($newGroup->add()) {
            $this->updateAttributeCode($newGroup->id,$attribute['code']);
            foreach ($attribute['values'] as $key => $value) {
                $newAttribute = new Attribute();
                $title = $this->createMultiLangField($key,[$key => $value]);
                $newAttribute->name = $title;
                $newAttribute->color = "";
                if ($attribute['type'] == "color") {
                    $newAttribute->color = $key;
                }
                $newAttribute->id_attribute_group = $newGroup->id;
                if($newAttribute->save()) {
                    $this->updateAttributeValueCode($newAttribute->id,$key,'attribute');
                }
            }
        }
        return ['success' => true];
    }
	/**
     *  Return available category steps to install
     */
	public function installFeature($feature)
	{
		$featureObj = new Feature();
        $name = $this->createMultiLangField('code',$feature);
        $featureObj->name = $name;
        $featureObj->position = 0;
        if ($featureObj->save()) {
            $this->updateFeatureCode($featureObj->id,$feature['code']);
            foreach ($feature['values'] as $key => $value) {
                $featureValue = new FeatureValue();
                $name = $this->createMultiLangField($key,[$key => $value]);
                $featureValue->id_feature = $featureObj->id;
                $featureValue->value = $name;
                $featureValue->custom = 0;
                if($featureValue->add()) {
                    $this->updateFeatureValueCode($featureValue->id,$key);
                }
            }
        }

		return ['success' => true];
	}
	/**
     * Create multilenguaje array
     */
    private function createMultiLangField($field,$feature) {
        $languages = Language::getLanguages(false);
        $fieldLang = [];
        foreach ($languages as $lang) {
        	$idLang = $lang['id_lang'];
        	if (is_array($feature[$field])) {
        		$fieldLang[$idLang] = reset($feature[$field]);
	            if (isset($feature[$field][$idLang])) {
	                $fieldLang[$idLang] = $feature[$field][$idLang];
	            } else {
	                $fieldLang[$idLang] = reset($feature[$field]);
	            }
        	} else {
        		$fieldLang[$idLang] = $feature[$field];
        	}
        }
        return $fieldLang;
    }
    
    /**
     *  Return available category steps to install
     */
	public function updateAttributeCode($id,$code)
	{
		if(!$this->validateColumn('attribute_group','attribute_code')) {
			$this->addColumn('attribute_group','attribute_code');
		}
		$data['attribute_code'] = $code;
		Db::getInstance()->update('attribute_group', $data, "id_attribute_group = {$id}");
	}
	
    /**
     *  Return available category steps to install
     */
	public function updateAttributeValueCode($id,$code)
	{
		if(!$this->validateColumn('attribute','attributev_code')) {
			$this->addColumn('attribute','attributev_code');
		}
		$data['attributev_code'] = $code;
		Db::getInstance()->update('attribute', $data, "id_attribute = {$id}");
	}
    /**
     *  Return available category steps to install
     */
	public function updateFeatureValueCode($id,$code)
	{
		if(!$this->validateColumn('feature_value','featurev_code')) {
			$this->addColumn('feature_value','featurev_code');
		}
		$data['featurev_code'] = $code;
		Db::getInstance()->update('feature_value', $data, "id_feature_value = {$id}");
	}
	/**
     *  Return available category steps to install
     */
	public function updateFeatureCode($id,$code)
	{
		if(!$this->validateColumn('feature','feature_code')) {
			$this->addColumn('feature','feature_code');
		}
		$data['feature_code'] = $code;
		Db::getInstance()->update('feature', $data, "id_feature = {$id}");
	}
	/**
     * Create columns isprepay and prepayamount in table eter_point_sales
     */ 
    public function addColumn($table,$column){
        $sql = 'ALTER TABLE '._DB_PREFIX_.$table.' ADD COLUMN '.$column.' varchar(100);';
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
    }
	/**
     * Validate if column exist
     */
    public function validateColumn($table,$column) 
    {
        $sql = 'SELECT * 
            FROM information_schema.COLUMNS 
            WHERE TABLE_SCHEMA = "'._DB_NAME_.'" 
            AND TABLE_NAME = "'._DB_PREFIX_.$table.'" 
            AND COLUMN_NAME = "'.$column.'"';

        return (bool)Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
    }
	/**
     *  Return available category steps to install
     */
	public function cleanProducts()
	{
		$sql = 'SET FOREIGN_KEY_CHECKS = 0; 
				TRUNCATE TABLE '._DB_PREFIX_.'product ;
                TRUNCATE TABLE '._DB_PREFIX_.'product_shop;
                TRUNCATE TABLE '._DB_PREFIX_.'product_lang;
                TRUNCATE TABLE '._DB_PREFIX_.'image;
                TRUNCATE TABLE '._DB_PREFIX_.'image_shop;
                TRUNCATE TABLE '._DB_PREFIX_.'image_lang;
                TRUNCATE TABLE '._DB_PREFIX_.'product_attribute_combination;
                TRUNCATE TABLE '._DB_PREFIX_.'product_attribute_image;
                TRUNCATE TABLE '._DB_PREFIX_.'product_attribute_shop;
                TRUNCATE TABLE '._DB_PREFIX_.'feature_product;
                TRUNCATE TABLE '._DB_PREFIX_.'product_attribute;
                TRUNCATE TABLE '._DB_PREFIX_.'category_product;
                TRUNCATE TABLE '._DB_PREFIX_.'customization_field;
                TRUNCATE TABLE '._DB_PREFIX_.'customization_field_lang;
                TRUNCATE TABLE '._DB_PREFIX_.'accessory;
                TRUNCATE TABLE '._DB_PREFIX_.'attribute_group ;
                TRUNCATE TABLE '._DB_PREFIX_.'attribute_group_lang;
                TRUNCATE TABLE '._DB_PREFIX_.'attribute_group_shop;
                TRUNCATE TABLE '._DB_PREFIX_.'attribute_impact;
                TRUNCATE TABLE '._DB_PREFIX_.'attribute_lang;
                TRUNCATE TABLE '._DB_PREFIX_.'attribute_shop;
                TRUNCATE TABLE '._DB_PREFIX_.'attribute;
                TRUNCATE TABLE '._DB_PREFIX_.'feature;
                TRUNCATE TABLE '._DB_PREFIX_.'feature_lang;
                TRUNCATE TABLE '._DB_PREFIX_.'feature_shop;
                TRUNCATE TABLE '._DB_PREFIX_.'feature_value;
                TRUNCATE TABLE '._DB_PREFIX_.'feature_value_lang;
				SET FOREIGN_KEY_CHECKS = 1;';
		try {
			Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
        } catch(Exception $e){}
        return ['success' => true];
	}
	/**
     * copyImg copy an image located in $url and save it in a path
     * according to $entity->$id_entity .
     * $id_image is used if we need to add a watermark.
     *
     * @param int $id_entity id of product or category (set in entity)
     * @param int $id_image (default null) id of the image if watermark enabled
     * @param string $url path or url to use
     * @param string $entity 'products' or 'categories'
     * @param bool $regenerate
     *
     * @return bool
     */
    public static function copyImg($id_entity, $id_image = null, $url = '', $entity = 'products', $regenerate = true)
    {
        $tmpfile = tempnam(_PS_TMP_IMG_DIR_, 'ps_import');
        $watermark_types = explode(',', Configuration::get('WATERMARK_TYPES'));

        switch ($entity) {
            default:
            case 'products':
                $image_obj = new Image($id_image);
                $path = $image_obj->getPathForCreation();
                break;
            case 'categories':
                $path = _PS_CAT_IMG_DIR_ . (int) $id_entity;
                break;
            case 'manufacturers':
                $path = _PS_MANU_IMG_DIR_ . (int) $id_entity;
                break;
            case 'suppliers':
                $path = _PS_SUPP_IMG_DIR_ . (int) $id_entity;
                break;
            case 'stores':
                $path = _PS_STORE_IMG_DIR_ . (int) $id_entity;
                break;
        }

        $url = urldecode(trim($url));
        $parced_url = parse_url($url);

        if (isset($parced_url['path'])) {
            $uri = ltrim($parced_url['path'], '/');
            $parts = explode('/', $uri);
            foreach ($parts as &$part) {
                $part = rawurlencode($part);
            }
            unset($part);
            $parced_url['path'] = '/' . implode('/', $parts);
        }

        if (isset($parced_url['query'])) {
            $query_parts = array();
            parse_str($parced_url['query'], $query_parts);
            $parced_url['query'] = http_build_query($query_parts);
        }

        if (!function_exists('http_build_url')) {
            require_once _PS_TOOL_DIR_ . 'http_build_url/http_build_url.php';
        }

        $url = http_build_url('', $parced_url);

        $orig_tmpfile = $tmpfile;

        if (Tools::copy($url, $tmpfile)) {
            // Evaluate the memory required to resize the image: if it's too much, you can't resize it.
            if (!ImageManager::checkImageMemoryLimit($tmpfile)) {
                @unlink($tmpfile);

                return false;
            }

            $tgt_width = $tgt_height = 0;
            $src_width = $src_height = 0;
            $error = 0;
            ImageManager::resize($tmpfile, $path . '.jpg', null, null, 'jpg', false, $error, $tgt_width, $tgt_height, 5, $src_width, $src_height);
            $images_types = ImageType::getImagesTypes($entity, true);

            if ($regenerate) {
                $previous_path = null;
                $path_infos = array();
                $path_infos[] = array($tgt_width, $tgt_height, $path . '.jpg');
                foreach ($images_types as $image_type) {
                    $tmpfile = self::get_best_path($image_type['width'], $image_type['height'], $path_infos);

                    if (ImageManager::resize(
                        $tmpfile,
                        $path . '-' . stripslashes($image_type['name']) . '.jpg',
                        $image_type['width'],
                        $image_type['height'],
                        'jpg',
                        false,
                        $error,
                        $tgt_width,
                        $tgt_height,
                        5,
                        $src_width,
                        $src_height
                    )) {
                        // the last image should not be added in the candidate list if it's bigger than the original image
                        if ($tgt_width <= $src_width && $tgt_height <= $src_height) {
                            $path_infos[] = array($tgt_width, $tgt_height, $path . '-' . stripslashes($image_type['name']) . '.jpg');
                        }
                        if ($entity == 'products') {
                            if (is_file(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int) $id_entity . '.jpg')) {
                                unlink(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int) $id_entity . '.jpg');
                            }
                            if (is_file(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int) $id_entity . '_' . (int) Context::getContext()->shop->id . '.jpg')) {
                                unlink(_PS_TMP_IMG_DIR_ . 'product_mini_' . (int) $id_entity . '_' . (int) Context::getContext()->shop->id . '.jpg');
                            }
                        }
                    }
                    if (in_array($image_type['id_image_type'], $watermark_types)) {
                        Hook::exec('actionWatermark', array('id_image' => $id_image, 'id_product' => $id_entity));
                    }
                }
            }
        } else {
            @unlink($orig_tmpfile);
            return false;
        }
        unlink($orig_tmpfile);

        return true;
    }
    /**
     *  get best path
     */
    protected static function get_best_path($tgt_width, $tgt_height, $path_infos)
    {
        $path_infos = array_reverse($path_infos);
        $path = '';
        foreach ($path_infos as $path_info) {
            list($width, $height, $path) = $path_info;
            if ($width >= $tgt_width && $height >= $tgt_height) {
                return $path;
            }
        }

        return $path;
    }
}