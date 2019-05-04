<?php

if (!defined('_PS_VERSION_')) {
	exit;
}
class productsStepsImporter
{
	public $extraInputs = [
		'unit_price' => 0,
		'unity' => 0,
		'low_stock_threshold' => 0,
		'low_stock_alert' => 0,
		'additional_shipping_cost' => 0,
		'available_now' => 1,
		'available_later' => '',
		'available_for_order' => '',
		'available_date' => '',
		'date_add' => '',
		'show_price' => 1,
		'online_only' => 0,
		'out_of_stock' => '',
	];

	public $combinationsExtra = [
		'supplier_reference' => '',
		'ecotax' => 0,
		'wholesale_price' => 0,
		'available_date' => '',
		'unit_price_impact' => 0,
		'low_stock_threshold' => '',
		'low_stock_alert' => '',
	];
	public $features = [];
	public $attributes = [];
	public $accessories = [];
	/**
     * Prepare categories data
     */
	public function prepareData($theme) 
	{
		$baseurl = _ETER_IMPORT_DIR_.$theme;
		$baseurl = rtrim($baseurl,'/').'/';
		$this->theme = $theme;
		$this->products = [];
		//try {		   
			$url = _ETER_IMPORT_DIR_.$theme.'/products.csv';
			$handle = fopen($url, "r");
            if ($handle !== FALSE) {           		
				$header = null;
                while (($row = fgetcsv($handle, 10000, ",")) !== FALSE) {
                   	if(!$header) {
                       $header = $row;
                    } else if(is_array($row) && is_array($header)){
                        $data = array_combine($header, $row);
                        //try {
	                        if (isset($data['GlobalReference']) && $data['GlobalReference']) {
		                        if (isset($this->products[$data['GlobalReference']])) {
		                        	$this->updateProduct($data);
		                        } else {
		                      		$this->createProduct($data);
		                        }
	                        }
                        //} catch (Exception $e) {
                        	//TODO: include validation errors
                        //}
                    } 
                }               
            }
		//} catch (Exception $e) {}
	}
	/**
     *  Return available category steps to install
     */
	public function getProductsFeatureSteps()
	{
		$steps = [];
		$link = Context::getContext()->link;
		foreach ($this->features as $key => $value) {
			$step['url'] = $link->getAdminLink('AdminDemos',true,[],['ajax'=>1,'installFeature'=>1]);
			$step['code'] = $key;
			$step['sname'] = $key;
			$step['values'] = $value;
			$steps[] = $step;
		}
		$data['steps'] = $steps;
		$data['total'] = count($steps);
		$data['increase'] = 100/count($steps);
		return $data;
	}
	/**
     *  Return available category steps to install
     */
	public function getProductsAttributesSteps()
	{
		$steps = [];
		$link = Context::getContext()->link;
		foreach ($this->attributes as $key => $value) {
			$step['url'] = $link->getAdminLink('AdminDemos',true,[],['ajax'=>1,'installAttribute'=>1]);
			$split = explode(':', $key);
			$step['code'] = $split[0];
			$step['sname'] = $split[0];
			$step['type'] = $split[1];
			$step['values'] = $value;
			$steps[] = $step;
		}
		$data['steps'] = $steps;
		$data['total'] = count($steps);
		$data['increase'] = 100/count($steps);
		return $data;
	}
	/**
     *  Return available category steps to install
     */
	public function getProductsSteps()
	{
		$steps = [];
		foreach ($this->products as $key => $value) {
			$steps[] = $this->products[$key];
		}
		$data['steps'] = $steps;
		$data['total'] = count($this->products);
		$data['increase'] = 100/count($this->products);
		return $data;
	}
	/**
     *  Return available category steps to install
     */
	public function getAccesoriesSteps()
	{
		$steps = [];
		foreach ($this->accessories as $key => $value) {
			$step['url'] = Context::getContext()->link->getAdminLink('AdminDemos',true,[],['ajax'=>1,'installAccessories'=>1]);
			$step['sku'] = $key;
			$step['sname'] = $key;
			$step['accessories'] = $value;
			$steps[] = $step;
		}
		$data['steps'] = $steps;
		$data['total'] = count($steps);
		$data['increase'] = 100/count($steps);
		return $data;
	}
	/**
     * Create category
     */
	public function installCategory($data) 
	{
		$category = new Category($data['id_category']);
		foreach ($data as $key => $value) {
			if ($key == 'id_parent') {
				$category->{$key} = $this->getIdParentByCode($data['id_parent']);	
			} else {
				$category->{$key} = $value;
			}
		}

		if($category->save()) {
			$this->insertIntoTable($category);
        	@unlink(_PS_CAT_IMG_DIR_.$category->id.'.jpg');
        	@unlink(_PS_CAT_IMG_DIR_.$category->id.'.png');
            $from = $category->image;
        	$to =  _PS_CAT_IMG_DIR_.$category->id.'.jpg';
        	@copy($from,$to);
        }
		//echo print_r($category,true);
		return ['success' => true];
	}

	/**
     * create main product array
     */
	public function createProduct($data) 
	{
		$product = []; 
		$product['reference'] = $data['GlobalReference'];
		$product['active'] =  isset($data['Active']) ? $data['Active'] : 1; 
		$product['shop'] =  isset($data['Store']) ? $data['Store'] : 1; 
		$product['id_tax_rules_group'] = isset($data['TaxId']) ? $data['TaxId'] : 0;
		$product['category'] = isset($data['Categories']) ? $data['Categories'] : 0;
		$product['price'] = isset($data['PriceTaxInc']) ? $data['PriceTaxInc'] : 0;
		$product['price_tex'] = isset($data['PriceTaxExc']) ? $data['PriceTaxExc'] : 0;
		$product['price_tin'] = isset($data['PriceTaxInc']) ? $data['PriceTaxInc'] : 0;
		$product['ean13'] = isset($data['EAN13']) ? $data['EAN13'] : 0;
		$product['sname'] = isset($data['Name']) ? $data['Name'] : "";
		$product['cover'] = isset($data['Cover']) ? _ETER_IMPORT_DIR_.'products/'.$data['Cover'] : '';
		$product['upc'] = isset($data['UPC']) ? $data['UPC'] : 0;
		$product['isbn'] = isset($data['ISBN']) ? $data['ISBN'] : 0;
		$product['width'] = isset($data['Width']) ? $data['Width'] : 0;
		$product['height'] = isset($data['Height']) ? $data['Height'] : 0;
		$product['depth'] = isset($data['Depth']) ? $data['Depth'] : 0;
		$product['weight'] = isset($data['Weight']) ? $data['Weight'] : 0;
		$product['quantity'] = isset($data['Quantity']) ? $data['Quantity'] : 0;
		$product['minimal_quantity'] = isset($data['MinQty']) ? $data['MinQty'] : 0;
		$product['visibility'] = isset($data['Visibility']) ? $data['Visibility'] : 0;
		$product['condition'] = isset($data['Condition']) ? $data['Condition'] : 0;
		$product['CategoryDefault'] = isset($data['CategoryDefault']) ? $data['CategoryDefault'] : 'home';

		$product['url'] = Context::getContext()->link->getAdminLink('AdminDemos',true,[],['ajax'=>1,'installProduct'=>1]);
		$this->accessories[$data['GlobalReference']] = isset($data['Accessories']) ? $data['Accessories'] : 0;
		if ($images = $this->prepareProductImages($data)) {
			$product['images'] = $images;
		}
		if ($features = $this->prepareFeatures($data)) {
			$product['features'] = $features;
		}
		if ($attributes = $this->prepareAttributes($data)) {
			$product['attributes'] = $attributes;
		}
		if ($attacheds = $this->prepareAttacheds($data)) {
			$product['attacheds'] = $attacheds;
		} else {
			$product['attacheds'] = null;
		}
		if ($customizable = $this->prepareCustomFields($data)) {
			$product['customizable'] = $customizable;
		}

		if (isset($data['DownloadFile']) && $data['DownloadFile']) {
			$product['virtual']['file_url'] = isset($data['DownloadFile']) ? $data['DownloadFile'] : 0;
			$product['virtual']['nb_downloadable'] = isset($data['DownloadAllows']) ? $data['DownloadAllows'] : 0;
			$product['virtual']['nb_days_accessible'] = isset($data['DownloadDays']) ? $data['DownloadDays'] : 0;
			$product['virtual']['date_expiration'] = isset($data['DownloadExpDate']) ? $data['DownloadExpDate'] : 0;
		} else {
			$product['virtual'] = null;
		}

		$languages = Language::getLanguages(false);
		foreach ($languages as $language) {
        	$id_lang = $language['id_lang'];
			$product['name'][$id_lang] = $data['Name'];
			$link_rewrite = (isset($data['LinkRewrite']) && $data['LinkRewrite']) ? $data['LinkRewrite'] : Tools::link_rewrite($data['Name']);
			$product['link_rewrite'][$id_lang] = $link_rewrite;
			$product['description'][$id_lang] = $data['Description'];
			$product['description_short'][$id_lang] = $data['Sumary'];
			$product['meta_title'][$id_lang] = isset($data['MetaTitle']) ? $data['MetaTitle'] : '';
			$product['meta_description'][$id_lang] = isset($data['MetaDescription']) ? $data['MetaDescription'] : '';
			$product['meta_keywords'][$id_lang] = isset($data['MetaKeyword']) ? $data['MetaKeyword'] : '';
        }

        $this->fillExtraInput($this->extraInputs,$product,$data);
        if (isset($product['attributes']) && count($product['attributes']) > 0) {
			$product['combinations'][$data['Reference']] = $this->prepareCombinations($product,$data,true);
		} else {
			$product['combinations'] = null;
		}
        $this->products[$data['GlobalReference']] = $product;
	}
	/**
     * Update product data
     */
	public function updateProduct($data) 
	{
		$product = [];
		$globalRef = $data['GlobalReference']; 
		if ($images = $this->prepareProductImages($data)) {
			$product['images'] = $images;
		}

		if ($attributes = $this->prepareAttributes($data)) {
			$product['attributes'] = $attributes;
		}
		if ($features = $this->prepareFeatures($data)) {
			$product['features'] = $features;
		}
    	$id_lang = @Language::getIdByIso($data['Language']);
    	if ($id_lang) {
			if ($data['Name']) {
				$this->products[$globalRef]['name'][$id_lang] = $data['Name'];
				$link_rewrite = isset($data['LinkRewrite']) ? $data['LinkRewrite'] : Tools::link_rewrite($data['Name']);
				$this->products[$globalRef]['link_rewrite'][$id_lang] = $link_rewrite;
			}
			if ($data['Description']) {
				$this->products[$globalRef]['description'][$id_lang] = isset($data['Description']) ? $data['Description'] : '';
			}
			if ($data['Sumary']) {
				$this->products[$globalRef]['description_short'][$id_lang] = isset($data['Sumary']) ? $data['Sumary'] : '';
			}
			if ($data['MetaTitle']) {
				$this->products[$globalRef]['meta_title'][$id_lang] = isset($data['MetaTitle']) ? $data['MetaTitle'] : '';
			}
			if ($data['MetaDescription']) {
				$this->products[$globalRef]['meta_description'][$id_lang] = isset($data['MetaDescription']) ? $data['MetaDescription'] : '';
			}
			if ($data['MetaKeyword']) {
				$this->products[$globalRef]['meta_keywords'][$id_lang] = isset($data['MetaKeyword']) ? $data['MetaKeyword'] : '';
			}
    	}
        $this->fillExtraInput($this->extraInputs,$product,$data);
        if (isset($product['attributes']) && count($product['attributes']) > 0) {
        	$this->products[$globalRef]['combinations'][$data['Reference']] = $this->prepareCombinations($product,$data,true);
		}
	}
	/**
     * Get Images to import
     */
	public function fillExtraInput($inputs,&$product,$data) 
	{
		foreach ($inputs as $key => $value) {
			if (isset($data[$key])) {
				$product[$key] = $data[$key];
			} else {
				$product[$key] = $value;
			}
		}
	}
	/**
     * Get Images to import
     */
	public function prepareCombinations(&$product,$data,$default = false) 
	{
		$combinations['attributes'] = $product['attributes'];
		unset($product['attributes']);
		$combinations['images'] = $product['images'];
		unset($product['images']);
		$combinations['sname'] = $data['Name'];
		$combinations['ean13'] = isset($data['EAN13']) ? $data['EAN13'] : 0;
		$combinations['isbn'] = isset($data['ISBN']) ? $data['ISBN'] : 0;
		$combinations['upc'] = isset($data['UPC']) ? $data['UPC'] : 0;
		$combinations['quantity'] = isset($data['Quantity']) ? $data['Quantity'] : 0;
		$combinations['reference'] = isset($data['Reference']) ? $data['Reference'] : 0;
		$combinations['price'] = isset($data['PriceTaxInc']) ? $data['PriceTaxInc'] : 0;
		$combinations['default_on'] = $default;
		$combinations['weight'] = isset($data['Weight']) ? $data['Weight'] : 0;
		$combinations['minimal_quantity'] = isset($data['MinQty']) ? $data['MinQty'] : 0;
		$this->fillExtraInput($this->combinationsExtra,$combinations,$data);
        return $combinations;
	}
	/**
     * Get Images to import
     */
	public function prepareCustomFields($data) 
	{
		$inputs = [];
		$dataInputs = preg_filter('/^Input:(.*)/', '$1', array_keys($data));
        foreach ($dataInputs as $key => $input) {
        	$split = explode(':', $input);
        	if (count($split) >= 2) {
 	        	$inputOb['type'] = $split[1]; 
	        	$inputOb['name'] = $data["Input:{$input}"];
	            $inputs[$split[0]] = $inputOb;
        	}
        }
        return $inputs;
	}
	/**
     * Get Images to import
     */
	public function prepareAttacheds($data) 
	{
		$attacheds = [];
		$dataAttacheds = preg_filter('/^AttachedFile(.*)/', '$1', array_keys($data));
        foreach ($dataAttacheds as $key => $attached) {
        	if ($data["AttachedFile{$attached}"]) {
            	$attacheds[] = _ETER_IMPORT_DIR_.$data["AttachedFile{$attached}"];
        	}
        }
        return $attacheds;
	}
	/**
     * get Attributes to install
     */
	public function prepareAttributes($data) 
	{
		$features = [];
		$attrObj = preg_filter('/^Attribute:(.*)/', '$1', array_keys($data));
		$id_lang = @Language::getIdByIso($data['Language']);
		$id_lang = ($id_lang != 0) ? $id_lang :1;
        foreach ($attrObj as $key => $attribute) {
            if (isset($data["Attribute:{$attribute}"]) && $data["Attribute:{$attribute}"]) {
	            $languages = Language::getLanguages(false);
	            $value = [];
	            preg_match('#\[(.*?)\]#', $data["Attribute:{$attribute}"], $match);
	            if (isset($match[1])) {
					$valueName = trim($match[1]);
					$nameAttr = explode(':', $attribute);
	            	$this->attributes[$attribute][$valueName][$id_lang] = trim(str_replace("[{$valueName}]", "", $data["Attribute:{$attribute}"]));
		            $features[$nameAttr[0]] = $valueName;
	            }
            }
        }
        
        return $features;
	}
	/**
     * get Features to install
     */
	public function prepareFeatures($data) 
	{
		$features = [];
		$featuresObj = preg_filter('/^Feature:(.*)/', '$1', array_keys($data));
		$id_lang = Language::getIdByIso($data['Language']);
		$id_lang = ($id_lang != 0) ? $id_lang :1;
        foreach ($featuresObj as $key => $feature) {
            if (isset($data["Feature:{$feature}"]) && $data["Feature:{$feature}"]) {
	            $value = [];
	            preg_match('#\[(.*?)\]#', $data["Feature:{$feature}"], $match);
	            if (isset($match[1])) {
	            	$valueName = trim($match[1]);
	            	$this->features[$feature][$valueName][$id_lang] = trim(str_replace("[{$valueName}]", "", $data["Feature:{$feature}"]));
		            $features[$feature] = $valueName;
	            }
            }
        }
        return $features;
	}
	/**
     * Get Images to import
     */
	public function prepareProductImages($data) 
	{
		$images = [];
		$dataImages = preg_filter('/^Image(.*)/', '$1', array_keys($data));
        $i = 0;
        foreach ($dataImages as $key => $image) {
            $isCover = ($i == 0);
            $i++;
            if ($data["Image{$image}"]) {
            	$images[] = _ETER_IMPORT_DIR_.$this->theme.'/products/'.$data["Image{$image}"];
            }
        }
        return $images;
	}
	/**
     * Delete current categories
     */
	public function deleteCategories ()
	{
		$sql = 'SET FOREIGN_KEY_CHECKS = 0; 
				TRUNCATE TABLE '._DB_PREFIX_.'category_group ;
				TRUNCATE TABLE '._DB_PREFIX_.'category_lang;
				TRUNCATE TABLE '._DB_PREFIX_.'category_shop;
				TRUNCATE TABLE '._DB_PREFIX_.'category_product;
				TRUNCATE TABLE '._DB_PREFIX_.'category;
				SET FOREIGN_KEY_CHECKS = 1;';
		try {
			Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
        } catch(Exception $e){}
	}
	/**
     * Delete current categories
     */
	public function getIdParentByCode($code) 
	{
		$sql = 'SELECT id FROM '._DB_PREFIX_.'categories_import WHERE code like "%'.$code.'%"';
		return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
	}
	/**
     * Delete current categories
     */
	public function insertIntoTable($category) 
	{
		$data['id'] = $category->id;
		$data['code'] = $category->code;
		$data['background'] = $category->background;
		$data['icon'] = $category->icon;
		Db::getInstance()->insert('categories_import', $data);
	}
	/**
     * Delete current categories
     */
	public function createTableCategories ()
	{
		$sql = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'categories_import';
		Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
		$sql = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'categories_import (id INT NOT NULL, code VARCHAR (250), background VARCHAR (250), icon VARCHAR (250), PRIMARY KEY (id));';
		Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
	}
	/**
     * Delete current categories
     */
	public function deleteTableCategories ()
	{
		$sql = 'TRUNCATE TABLE '._DB_PREFIX_.'categories_import;';
		Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
	}
}