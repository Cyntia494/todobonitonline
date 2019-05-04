<?php

if (!defined('_PS_VERSION_')) {
	exit;
}
class CategoriesImporter
{
	/**
     * Prepare categories data
     */
	public function prepareData($theme) 
	{
		$this->theme = $theme;
		$this->categories = [];
		try {		   
			$url = _ETER_IMPORT_DIR_.$theme.'/categories.csv';
            $handle = fopen($url, "r");
            if ($handle !== FALSE) {           		
                $header = null;
                while (($row = fgetcsv($handle, 10000, ",")) !== FALSE) {
                   if(!$header) {
                       $header = $row;
                    } else if(is_array($row) && is_array($header)){
                        $data = array_combine($header, $row);
                        if (isset($this->categories[$data['id']])) {
                        	$this->updateCategory($data);
                        } else {
                      		$this->createCategory($data);
                        }
                    } 
                }               
            }
        } catch (Exception $e) {}
        $returnCats = [];
        foreach ($this->categories as $key => $value) {
        	$returnCats[] = $this->categories[$key];
        }
        return $returnCats;
	}
	/**
     * Clear data
     */
	public function cleanData() 
	{
		$this->deleteCategories();
		$this->createTableCategories();
		$this->deleteTableCategories();
		Configuration::updateValue('MOD_BLOCKTOPMENU_ITEMS', "");
		return ['success' => true];
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
			if($data['id_parent'] == "home") {
				$mainmenu = explode(',',Configuration::get('MOD_BLOCKTOPMENU_ITEMS'));
				$mainmenu[] = "CAT".$category->id;
				$categories = trim(implode(',', $mainmenu),',');
				Configuration::updateValue('MOD_BLOCKTOPMENU_ITEMS', $categories);
			}
        	@unlink(_PS_CAT_IMG_DIR_.$category->id.'.jpg');
        	@unlink(_PS_CAT_IMG_DIR_.$category->id.'.png');
            $from = $category->image;
        	$to =  _PS_CAT_IMG_DIR_.$category->id.'.jpg';
			@copy($from,$to);
			return ['success' => true];
        } else {
			return ['success' => false];
		}
	}
	/**
     * Update lang category attributes
     */
	public function updateCategory($data) 
	{
		if (isset($data['iso_code'])) {
			$id_lang = Language::getIdByIso($data['iso_code']);
			$this->categories[$data['id']]['name'][$id_lang] = $data['name'];
			$this->categories[$data['id']]['link_rewrite'][$id_lang] = Tools::link_rewrite($data['name']);
			$this->categories[$data['id']]['description'][$id_lang] = $data['description'];
			$this->categories[$data['id']]['meta_title'][$id_lang] = $data['meta_title'];
			$this->categories[$data['id']]['meta_description'][$id_lang] = $data['meta_description'];
			$this->categories[$data['id']]['meta_keywords'][$id_lang] = $data['meta_keywords'];
		}

	}
	/**
     * Creat main category array
     */
	public function createCategory($data) 
	{
		$category = []; 
		$category['id_category'] = $data['id'];
		$category['active'] = $data['active'];
		$category['id_parent'] = $data['parent'];
		$category['code'] = $data['code'];
		$category['is_root_category'] = 0;
		$category['image'] = _ETER_IMPORT_DIR_.$this->theme.'/categories/'.$data['id'].'.jpg';
		$category['background'] = _ETER_IMPORT_DIR_.$this->theme.'/categories/background/'.$data['background'];
		$category['icon'] = _ETER_IMPORT_DIR_.$this->theme.'/categories/icons/'.$data['menu_icon'];
		$category['sname'] = $data['name'];
		$category['url'] = Context::getContext()->link->getAdminLink('AdminDemos',true,[],['ajax'=>1,'installCategory'=>1]);
		$languages = Language::getLanguages(false);
		foreach ($languages as $language) {
        	$id_lang = $language['id_lang'];
			$category['name'][$id_lang] = $data['name'];
			$category['link_rewrite'][$id_lang] = Tools::link_rewrite($data['name']);
			$category['description'][$id_lang] = $data['description'];
			$category['meta_title'][$id_lang] = $data['meta_title'];
			$category['meta_description'][$id_lang] = $data['meta_description'];
			$category['meta_keywords'][$id_lang] = $data['meta_keywords'];
        }
        $this->categories[$data['id']] = $category;
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