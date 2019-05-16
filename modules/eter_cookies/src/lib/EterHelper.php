<?php

class EterHelper { 
    /**
     * List Paginations
     */
    public function getModuleFormImage($image,$module)
    {
        if($image){
            $src = Context::getContext()->link->getMediaLink("/modules/{$module}/images/{$image}");
            return "<img alt=\"preview\" style=\"max-width:250px;\" src=\"{$src}\">";
        }
        return false;
    }
    /**
     * List Paginations
     */
    public function getModuleUrl($module,$data = [])
    {
        $data['configure'] = $module;
        $data['token'] = Tools::getAdminTokenLite('AdminModules');
        return Context::getContext()->link->getAdminLink('AdminModules', false,null,$data);
    }
    /**
     * Create admin menu
     */
    public static function addModuleTabMenu($classparent,$classuri,$classname,$module,$tabposition = 1,$icon = '') 
    {
        if(Tab::getIdFromClassName($classuri) > 0) {
            return true;
        } 
        $parentTabID = Tab::getIdFromClassName($classparent); 
        if ($classparent == 'ETERLABSMODULES' && !$parentTabID) {
            $position = self::getPositionFromClassName("SELL");
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = 'ETERLABSMODULES'; 
            $tab->name = array();
            $tab->icon = '';
            $tab->module = 'eterlabs_modules';
            foreach (Language::getLanguages() as $lang){
                $tab->name[$lang['id_lang']] = 'Eterlabs Modules';
            }
            $tab->id_parent = 0;
            if ($tab->add()) {
                $tab->position = $position;
                $tab->save();
                $parentTabID = $tab->id;
            }
        } 

        if($parentTabID >= 0) {
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = $classuri; 
            $tab->name = array();
            $tab->icon = $icon;
            $tab->position = $tabposition;
            foreach (Language::getLanguages() as $lang){
                $tab->name[$lang['id_lang']] = $classname;
            }
            $tab->id_parent = $parentTabID;
            $tab->module = $module;
            if ($tab->add()) {
                $tab->position = $tabposition;
                return $tab->save();
            }
        } else {
            return false;
        }
    }
    /**
     * Get tab childs
     */
    public static function getPositionFromClassName($class_name) 
    {
        $query = new DbQuery();
        $query->select("position");
        $query->from("tab", "tab");
        $query->where("tab.class_name like \"{$class_name}\"");
        $value = (int)Db::getInstance()->getValue($query);
        return $value;
    }
    /**
     * Remove admin menu
     */
    public static function removeModuleTabMenu($classuri) 
    {
        $tabID = Tab::getIdFromClassName($classuri); 
        if ($tabID && self::isAllowdToRemoveModuleTab($tabID)) {
            $tab = new Tab($tabID);
            return $tab->delete();
        }
        return true;
    }
    /**
     * Get tab childs
     */
    public static function isAllowdToRemoveModuleTab($id_tab) 
    {
        $query = new DbQuery();
        $query->select("count(*)");
        $query->from("tab", "tab");
        $query->where("tab.id_parent = {$id_tab}");
        $value = (int)Db::getInstance()->getValue($query);
        return ($value == 0);
    }
    /**
     * Validate if column exist
     */
    public static function columnExist($table,$column) 
    {
        $sql = 'SELECT * 
            FROM information_schema.COLUMNS 
            WHERE TABLE_SCHEMA = "'._DB_NAME_.'" 
            AND TABLE_NAME = "'._DB_PREFIX_.$table.'" 
            AND COLUMN_NAME = "'.$column.'"';

        return (bool)Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
    }
    /**
     * Add Colum
     */
    public static function addColumn($table,$column,$type) 
    {
        if (!self::columnExist($table,$column)) {
            $table = _DB_PREFIX_.$table;
            $sql = "ALTER TABLE {$table} ADD COLUMN {$column} {$type} NOT NULL";
            return Db::getInstance()->execute($sql);
        } 
        return true;
    }
    /**
     * Remove Colum
     */
    public static function removeColumn($table,$column) 
    {
        if (self::columnExist($table,$column)) {
            $table = _DB_PREFIX_.$table;
            $sql = "ALTER TABLE {$table} DROP COLUMN {$column}";
            return Db::getInstance()->execute($sql);
        } 
        return true;
    }
    /**
    * Build secure data
    */
    public static function getInfo($module)  
    {
        try {
            $headers = [];
            $url = $module->author_uri;
            if (defined('_PS_LICENSE_URL')) {
                $url = _PS_LICENSE_URL;
            }
            $post['domain'] = Tools::getShopDomain();
            $post['license'] = $module->secure_key;
            $post['product'] = $module->name;   

            $url = $url.'/v1/api/modules/key?'.http_build_query($post);
            $response = json_decode(Tools::file_get_contents($url,false));
            if ($response && property_exists($response,'success') && $response->success) {
                return $response->html;
            } 
            throw new Exception("Error conecting with eterlabs");
        } catch (Exception $e) {
            return false;              
        }
    }
}    