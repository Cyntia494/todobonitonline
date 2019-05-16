<?php

class EterInputsValidator 
{ 
    /**
     * Constructor
     */
    public function __construct($module,$imagepath = null)
    {
        $this->module = $module;
        $this->imagepath = $imagepath;
        $this->validationErrors = [];
    }
    /**
     * Validate images folder
     */
    public function isImagePathWritable() 
    {
        return is_writable($this->imagepath);
    }
    /**
     * Return folder error
     */
    public function getImagePathError() 
    {
        return sprintf($this->l('Please verify folder %s (Write Permission Needed).'),$this->imagepath);
    }
    /**
    * Validate Parameter
    */ 
    public function validateParameters($parameters) 
    {
        $params = [];
        $errors = [];
        $languages = $this->getLanguages();
        foreach ($parameters as $key => $parameter) {
            $value = Tools::getValue($key);
            $label = $parameter['label'];
            $isLang = isset($parameter['lang']) && $parameter['lang'];
            $isrequired = isset($parameter['required']) && $parameter['required'];
            if (isset($parameter['depends'])) {
                foreach ($parameter['depends'] as $dpekey => $options) {
                    if (!in_array($params[$dpekey],$options)) {
                        $isrequired = false;
                    }
                }
            }
            if ($isrequired && !$isLang && !$value) {
                $this->validationErrors[] =  sprintf($this->l("The pararameter %s is required"),$label);
                continue;
            }
            if (isset($parameter['validate'])) {
                $validation = $parameter['validate'];
                if ($validation == "isImage" && $parameter['required']) {
                    if (!$this->validateImage($key,$isrequired,$parameter['width'],$parameter['height'])) {
                         continue;
                    }
                    $value = true;
                }  else if ($validation && $isLang) {
                    $langvalue = [];
                    foreach ($languages as $lang) {
                        $langkey = $key.'_'.$lang['id_lang'];
                        $valuelang = Tools::getValue($langkey);
                        if ($isrequired && !$valuelang) {
                            $this->validationErrors[] =  sprintf($this->l("The pararameter %s is required for %s language"),$label,$lang['name']);
                            continue;
                        }
                        if ($valuelang && $validation && !Validate::$validation($valuelang)) {
                            $this->validationErrors[] =  sprintf($this->l("The pararameter %s is invalid for %s language"),$label,$lang['name']);
                            continue;
                        }
                        $langvalue[$lang['id_lang']] = $valuelang;
                    }
                    $value = $langvalue;
                } else if ($value && $validation && $validation != "isImage" && !Validate::$validation($value)) {
                    $this->validationErrors[] =  sprintf($this->l("The pararameter %s is invalid"),$label);
                    continue;
                }
            }
            $params[$key] = $value;
        }
        if (count($this->validationErrors) >= 1) {
            $message = implode("-|-", $this->validationErrors);
            throw new PrestaShopException($message);
        }
        return (object)$params;
    }
    /**
     * Load available languages
     */
    public static function getLanguages()
    {
        $lang_exists = false;
        $languages = Language::getLanguages(true);
        $default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)($language['id_lang'] == $default_form_language);
        }
        return $languages;
    }
    /**
     * Upload image
     */
    public function validateImage($name,$isrequired,$width,$height,$max_size=1000000) 
    {
        if($isrequired) {
            if (empty($_FILES[$name]['name'])) {
                $this->validationErrors[] = sprintf($this->module->l('The image %s field is empty'),$name);
                return false;
            }
        }
        if (!empty($_FILES[$name]['name'])) {
            // Check image validity
            if ($error = ImageManager::validateUpload($_FILES[$name], Tools::getMaxUploadSize($max_size))) {
                $this->validationErrors[] =  $error;
                return false;
            }
            // Check image size
            if($width || $height) {
                $size = getimagesize($_FILES[$name]['tmp_name']);
                if($size[0] != $width && $size[1] != $height) {
                    $this->validationErrors[] = sprintf($this->l('Please review the image %s size %s x %s pixels'),$name,$width,$height);
                    return false;
                }
            }
            // Check directory
            if (!file_exists($this->imagepath)) {
                $success = @mkdir($this->imagepath, 0775, true);
                $chmod = @chmod($this->imagepath, 0775);
                if (!$success && !$chmod) {
                    $this->validationErrors[] = sprintf($this->l('Please review access in the server path %s to upload files'),$this->imagepath);
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }
    /**
     * Upload image
     */
    public function uploadImage($fileName,$name = null) {
        try {
            $path = $this->imagepath;
            $chmod = @chmod($path, 0775);
            $ext = pathinfo($_FILES[$fileName]['name'], PATHINFO_EXTENSION);
            if (is_null($name)) {
                $name = Tools::link_rewrite(pathinfo($_FILES[$fileName]['name'], PATHINFO_FILENAME));
            }
            $uploadName = $name.'.'.$ext;

            if (move_uploaded_file($_FILES[$fileName]['tmp_name'], $path.$uploadName)) {
                return $uploadName;
            } else {
                return false;
            }
        } catch (Exception $e) {
            die("iuhu");
            return false;
        }
    }
    /**
     * translates
     */
    private function l($text)
    {
        return $this->module->l($text);
    }
}    