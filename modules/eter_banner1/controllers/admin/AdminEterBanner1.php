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
* @package    Eter_Banner1
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/

class AdminEterBanner1Controller extends ModuleAdminController
{   
    /**
    * Define globlal variables
    */
    public $bootstrap = true;
    public $uploadImageDir;
    /**
    * Initialize controller content
    */
    public function initContent()
    {
        parent::initContent();
        $this->uploadImageDir = _PS_MODULE_DIR_.'eter_banner1/images/';
        if(!is_writable($this->uploadImageDir)) {
            $imageFolder = '<strong>'.$this->uploadImageDir.'</strong>';
            $this->errors[] =  sprintf($this->l('Please verify folder %s (Write Permission Needed).'),$imageFolder);
        }
        if(Tools::isSubmit('submitCard')){
            if($this->validateForm()) {
                if($this->postFormProcess()) {
                    $this->confirmations[] = $this->l('The Configuration have been saved.');
                } else {
                    $this->errors[] = $this->l('The Configuration have not been saved.');
                }
            } 
        }
        $html = EterHelper::getInfo($this->module);
        $html .= $this->renderForm();
        $this->context->smarty->assign(array(
            'content' => $html,
        ));
    }
    /**
    * Validate form values
    */
    public function validateForm() 
    {
        $validation = true;
        $validate = isset($_FILES['image']);
        $validate_mobile = isset($_FILES['image_mobile']);
        if($error = $this->module->validateImage('image',$this->uploadImageDir,1500,140,!$validate)){
            $validation &= false;
            $this->errors[] = $error.$this->l(" for field Image");
        }  
        if($error = $this->module->validateImage('image_mobile',$this->uploadImageDir,500,150,!$validate_mobile)){
            $validation &= false;
            $this->errors[] = $error.$this->l(" for field Image Mobile");
        }

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            if(empty(Tools::getValue('url_'.$lang['id_lang'])) || !Validate::isUrl(Tools::getValue('url_'.$lang['id_lang']))) {
                $validation &= false;
                $this->errors[] = $this->l('Please validate the url').' - '.$lang['name'];
            }  
        }
        return $validation;    
    }
    /**
    * Process after validate form, save img and update banner's name
    */
    public function postFormProcess() {
        $res = true;
        if($image = $this->module->uploadImage('image',$this->uploadImageDir)) {
            $res &= Configuration::updateValue('BANNER1_IMAGE_NAME', $image);
        }
        if($image_mobile = $this->module->uploadImage('image_mobile',$this->uploadImageDir)) {
            $res &= Configuration::updateValue('BANNER1_IMAGE_NAME_MOBILE', $image_mobile);
        } 
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $res &= Configuration::updateValue('BANNER1_URL_L'.$lang['id_lang'], Tools::getValue('url_'.$lang['id_lang']));
        }
        return $res;
    }
    /**
    * Render config form
    */
    public function renderForm()
    {
        $imagehtml = "";
        $imagehtml_mobile =  "";
        $data = [];
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $lang = $lang['id_lang'];
            $data['url'][$lang] = Configuration::get('BANNER1_URL_L'.$lang);
        }
        if($image = Configuration::get('BANNER1_IMAGE_NAME')){
            $src = $this->context->link->getMediaLink('/modules/eter_banner1/images/'.$image);
            $imagehtml = '<img alt="preview" style="max-width:250px;" src="'.$src.'">';
        }
        if( $image_mobile =  Configuration::get('BANNER1_IMAGE_NAME_MOBILE')){
            $src_mobile = $this->context->link->getMediaLink('/modules/eter_banner1/images/'.$image_mobile);
            $imagehtml_mobile = '<img alt="preview" style="max-width:250px;" src="'.$src_mobile.'">';
        }
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Banner 1'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'file',
                        'label' => $this->l('Image'),
                        'name' => 'image',
                        'required' => true,
                        'display_image' => true,
                        'image' => $imagehtml ? $imagehtml : false,
                        'desc' => $this->l('Image size (1500 x 140)')
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Image Mobile'),
                        'name' => 'image_mobile',
                        'required' => true,
                        'display_image' => true,
                        'image' => $imagehtml_mobile ? $imagehtml_mobile : false,
                        'desc' => $this->l('Image size (500 x 150)')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Url Target'),
                        'name' => 'url',
                        'required' => true,
                        'lang' => true,
                    ),

                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitCard';
        $helper->show_cancel_button = false;
        $helper->back_url =  $this->context->link->getAdminLink('AdminEterBanner1', false);
        $helper->currentIndex = $this->context->link->getAdminLink('AdminEterBanner1', false);
        $helper->token = Tools::getAdminTokenLite('AdminEterBanner1');
        $language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $language->id,
                'iso_code' => $language->iso_code
            ),
            'fields_value' => $data,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        $helper->override_folder = '/';
        $languages = Language::getLanguages(false);
        return $helper->generateForm(array($fields_form));
    }
}