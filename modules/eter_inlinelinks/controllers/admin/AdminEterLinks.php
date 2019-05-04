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
* @package    Eter_Inlinelinks
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/
class AdminEterLinksController extends ModuleAdminController
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
        $this->uploadImageDir = _PS_MODULE_DIR_.'eter_inlinelinks/images/';
        if(!is_writable($this->uploadImageDir)) {
            $imageFolder = '<strong>'.$this->uploadImageDir.'</strong>';
            $this->errors[] =  sprintf($this->l('Please verify folder %s (Write Permission Needed).'),$imageFolder);
        }
        if(Tools::isSubmit('submitLinks')){
            if($this->validateForm()) {
                if($this->postFormProcess()) {
                    $html = $this->renderList();
                    $this->confirmations[] = $this->l('The link have been saved.');
                } else {
                    $html = $this->renderForm();
                    $this->errors[] = $this->l('The link have not been saved.');
                }
            } else {
                $html = $this->renderForm();
            }
        } else if (Tools::isSubmit('submitConfig')) {
            $languages = $this->getLanguages();
            $data = [];
            $res = true;
            foreach ($languages as $lang) {
                $key = 'ETER_INLINELINK_TITLE_'.$lang['id_lang'];
                $value = Tools::getValue($key);
                $res &= Configuration::updateValue($key,$value);
                if (!$value) {
                   $this->warnings[] = $this->l('We recomend to field the languaje.').$lang['name'];
                }
            }
            if ($res) {
                $this->confirmations[] = $this->l('The title have been saved.');
            } else {
                $this->errors[] = $this->l('The title have not been saved.');
            }
            $html = $this->renderConfigForm();
            $html .= $this->renderList();
        } else if (Tools::isSubmit('add') || Tools::isSubmit('updateLinks')) {
            $html = $this->renderForm();
        } else if (Tools::isSubmit('deleteLinks')) {
            $id_link = Tools::getValue('id_link');
            if ($id_link > 0) {
                $link = new EterLinks($id_link);
                if($link->delete()) {
                    $this->confirmations[] = $this->l('The link have been saved.');
                } else {
                    $this->errors[] = $this->l('The link have not been deleted.');
                }
            } 
            $html = $this->renderConfigForm();
            $html .= $this->renderList();
        } else {
            $html = $this->renderConfigForm();
            $html .= $this->renderList();
        }
        $before_html = EterHelper::getInfo($this->module);
        $this->context->smarty->assign(array(
            'content' => $before_html.$html,
        ));
    }
    /**
    * Ajax process, reorder link position when the user modify the rows position
    */
    public function ajaxPreProcess() {
        if (Tools::getValue('action') == "updatePositions") {
            $options = Tools::getValue('link');
            foreach ($options as $key => $value) {
                $code = str_replace('tr_position_', '', $value);
                $position = explode('_', $code);
                $link = new EterLinks((int)$position[0]);
                $link->position = $key;
                $link->save();
            }
        } 
        if (Tools::getValue('action') == "activeLinks") {
            $link = new EterLinks(Tools::getValue('id_link'));
            $link->active = ($link->active) ? 0 : 1;
            $link->save() ?
            die(json_encode(array('success' => true, 'text' => $this->l('The status has been updated successfully')))) :
            die(json_encode(array('success' => false, 'error' => true, 'text' => $this->l('Failed to update the status'))));
        } 

    }
    /**
    * Render configuration form
    */
    public function renderConfigForm()
    {
        $languages = $this->getLanguages();
        $data = [];
        foreach ($languages as $lang) {
            $data['ETER_INLINELINK_TITLE'][$lang['id_lang']] = Configuration::get('ETER_INLINELINK_TITLE_'.$lang['id_lang']);
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Inlines links text'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Inline link text'),
                        'name' => 'ETER_INLINELINK_TITLE',
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
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = (int)Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitConfig';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminEterLinks');
        $helper->token = Tools::getAdminTokenLite('AdminEterLinks');
        $language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $language->id,
                'iso_code' => $language->iso_code
            ),
            'fields_value' => $data,
            'languages' => $this->getLanguages(),
            'id_language' => $this->context->language->id);

        return $helper->generateForm(array($fields_form));
    }
    /**
    * Validate form values
    */
    public function validateForm() 
    {
        $validation = true;
        $id_link = (bool)Tools::getValue('id_link');
        if($error = $this->module->validateImage('icon',$this->uploadImageDir,100,100,!$id_link)) {
            $validation &= false;
            $this->errors[] = $error;
        }  
        $languages = $this->getLanguages();
        foreach ($languages as $lang) {
            if(empty(Tools::getValue('title_'.$lang['id_lang']))) {
                $validation &= false;
                $this->errors[] = $this->l('Please validate title').' - '.$lang['name'];
            }  
            if(empty(Tools::getValue('url_'.$lang['id_lang'])) || !Validate::isUrl(Tools::getValue('url_'.$lang['id_lang']))) {
                $validation &= false;
                $this->errors[] = $this->l('Please validate the link url').' - '.$lang['name'];
            }  
        }
        return $validation;    
    }
    /**
    * Proces after validate form
    */
    public function postFormProcess() {
        $id_link = Tools::getValue('id_link');
        $link = null;
        if ($id_link > 0) {
            $link = new EterLinks($id_link);
        } else {
            $link = new EterLinks(); 
            $link->position = $this->module->_link->getNewLastPosition();
        }
        if($image = $this->module->uploadImage('icon',$this->uploadImageDir)) {
            $link->icon = $image;
        }

        $link->active = Tools::getValue('active');
        $languages = $this->getLanguages();
        foreach ($languages as $lang) {
            $link->title[$lang['id_lang']] = Tools::getValue('title_'.$lang['id_lang']);
            $link->url[$lang['id_lang']] = Tools::getValue('url_'.$lang['id_lang']);
        }
        $res = (bool)$link->save();
        return $res;
    }
    /**
    * Render link list
    */
    public function renderList()
    {
        $fields_list = array(
            'id_link' => array(
                'title' => $this->l('Id'),
                'search' => false,
                'orderby' => false,
            ),
            'color' => array(
                'title' => $this->l('Icon'),
                'search' => false,
                'orderby' => false,
            ),
            'title' => array(
                'title' => $this->l('title'),
                'search' => false,
                'badge_success' => true,
                'orderby' => false,
            ),
            'position' => array(
                'title' => $this->l('Orden'),
                'position' => 'position',
                'align' => 'center',
                'class' => 'fixed-width-md',
                'search' => false,
                'orderby' => false,
            ),
            'active' => array(
                'title' => $this->trans('Displayed', array(), 'Admin.Global'),
                'active' => 'active',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'ajax' => true,
                'search' => false,
                'orderby' => false
            )
        );
        if (!Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')) {
            unset($fields_list['shop_name']);
        }
        $helper_list = new HelperList();
        $helper_list->module = $this->module;
        $helper_list->title = $this->l('Links');
        $helper_list->shopLinkType = '';
        $helper_list->table = 'Links';
        $helper_list->actions = array('edit', 'delete');
        $helper_list->identifier = 'id_link';
        $helper_list->_defaultOrderBy = 'position';
        $helper_list->is_cms = true;
        $helper_list->imageType = '';
        $helper_list->toolbar_btn['new'] =  array(
            'href' => $this->context->link->getAdminLink('AdminEterLinks', true,[],['add' => true]),
            'desc' => $this->trans('Add new', array(), 'Admin.Actions')
        );
        /*This lines from 188 to 191 allow to create drag and dop*/
        $helper_list->position_identifier = 'position';
        $helper_list->position_group_identifier = 'position';
        $helper_list->orderBy = 'position';
        $helper_list->orderWay = 'ASC';

        $helper_list->currentIndex = $this->context->link->getAdminLink('AdminEterLinks');
        $helper_list->token = Tools::getAdminTokenLite('AdminEterLinks');
        $this->_helperlist = $helper_list;

        $data = $this->module->_link->getData();
        foreach ($data as &$value) {
            $src = $this->context->link->getMediaLink('/modules/eter_inlinelinks/images/'.$value['icon']);
            $value['color']['texture'] = $src;
            $value['name'] = "";
        }

        $helper_list->listTotal = count($data);

        return $helper_list->generateList($data, $fields_list);  
    }
    /**
    * Render config form
    */
    public function renderForm()
    {
        $imagehtml = "";
        $data = [];

        $data = EterLinks::getAddFieldsValues(Tools::getValue('id_link'));
        if($data && $data['icon']){
            $src = $this->context->link->getMediaLink('/modules/eter_inlinelinks/images/'.$data['icon']);
            $imagehtml = '<img alt="preview" style="max-width:250px;" src="'.$src.'">';
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Cards'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'file',
                        'label' => $this->l('Image'),
                        'name' => 'icon',
                        'required' => true,
                        'display_image' => true,
                        'image' => $imagehtml ? $imagehtml : false,
                        'desc' => $this->l('Image size (100 x 100)')
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enabled'),
                        'name' => 'active',
                        'is_bool' => true,
                        'required' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Title'),
                        'name' => 'title',
                        'required' => true,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Url Target'),
                        'name' => 'url',
                        'required' => true,
                        'lang' => true,
                    ),
                ),
                'buttons' => array(
                    array(
                        'title' => $this->l('Cancel'),
                        'href' => $this->context->link->getAdminLink('AdminEterLinks',true),
                        'icon' => "process-icon-cancel"
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        if (Tools::getValue('id_link')) {
            $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_link');
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitLinks';
        $helper->show_cancel_button = false;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminEterLinks', false);
        $helper->token = Tools::getAdminTokenLite('AdminEterLinks');
        $language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $language->id,
                'iso_code' => $language->iso_code
            ),
            'fields_value' => $data,
            'languages' => $this->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        $helper->override_folder = '/';
        return $helper->generateForm(array($fields_form));
    }
    /**
     * @return array
     */
    public function getLanguages()
    {
        $cookie = $this->context->cookie;
        $this->allow_employee_form_lang = (int)Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        if ($this->allow_employee_form_lang && !$cookie->employee_form_lang) {
            $cookie->employee_form_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        }

        $lang_exists = false;
        $this->_languages = Language::getLanguages(true);
        foreach ($this->_languages as $lang) {
            if (isset($cookie->employee_form_lang) && $cookie->employee_form_lang == $lang['id_lang']) {
                $lang_exists = true;
            }
        }

        $this->default_form_language = $lang_exists ? (int)$cookie->employee_form_lang : (int)Configuration::get('PS_LANG_DEFAULT');

        foreach ($this->_languages as $k => $language) {
            $this->_languages[$k]['is_default'] = (int)($language['id_lang'] == $this->default_form_language);
        }

        return $this->_languages;
    }
}