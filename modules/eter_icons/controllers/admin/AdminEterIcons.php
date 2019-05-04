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
* @package    Eter_Icons
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/

class AdminEterIconsController extends ModuleAdminController
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
        $this->uploadImageDir = _PS_MODULE_DIR_.'eter_icons/images/';
        if(!is_writable($this->uploadImageDir)) {
            $imageFolder = '<strong>'.$this->uploadImageDir.'</strong>';
            $this->errors[] =  sprintf($this->l('Please verify folder %s (Write Permission Needed).'),$imageFolder);
        }
        if(Tools::isSubmit('submiticon')){
            if($this->validateForm()) {
                if($this->postFormProcess()) {
                    $html = $this->renderList();
                    $this->confirmations[] = $this->l('The icon have been saved.');
                } else {
                    $html = $this->renderForm();
                    $this->errors[] = $this->l('The icon have not been saved.');
                }
            } else {
                $html = $this->renderForm();
            }
        } else if (Tools::isSubmit('add') || Tools::isSubmit('updateicons')) {
            $html = $this->renderForm();
        } else if (Tools::isSubmit('deleteicons')) {
            $id_icon = Tools::getValue('id_icon');
            if ($id_icon > 0) {
                $icon = new EterIcons($id_icon);
                if($icon->delete()) {
                    $this->confirmations[] = $this->l('The icon have been saved.');
                } else {
                    $this->errors[] = $this->l('The icon have not been deleted.');
                }
            } 
            $html = $this->renderList();
        } else {
            $html = $this->renderList();
        }
        $before_html = EterHelper::getInfo($this->module);
        $this->context->smarty->assign(array(
            'content' => $before_html.$html,
        ));
    }
    /**
    * Ajax process, reorder icon position when the user modify the rows position
    */
    public function ajaxPreProcess() {
        if (Tools::getValue('action') == "updatePositions") {
            $options = Tools::getValue('icon');
            foreach ($options as $key => $value) {
                $code = str_replace('tr_position_', '', $value);
                $position = explode('_', $code);
                $icon = new EterIcons((int)$position[0]);
                $icon->position = $key;
                $icon->save();
            }
        } 
        if (Tools::getValue('action') == "activeicons") {
            $icon = new EterIcons(Tools::getValue('id_icon'));
            $icon->active = ($icon->active) ? 0 : 1;
            $icon->save() ?
            die(json_encode(array('success' => true, 'text' => $this->l('The status has been updated successfully')))) :
            die(json_encode(array('success' => false, 'error' => true, 'text' => $this->l('Failed to update the status'))));
        } 

    }

    /**
    * Validate form values
    */
    public function validateForm() 
    {
        $validation = true;
        $id_icon = (bool)Tools::getValue('id_icon');
        if($error = $this->module->validateImage('image',$this->uploadImageDir,128,80,!$id_icon)) {
            $validation &= false;
            $this->errors[] = $error;
        }  
        $languages = $this->getLanguages();
        foreach ($languages as $lang) {
            if(empty(Tools::getValue('name_'.$lang['id_lang']))) {
                $validation &= false;
                $this->errors[] = $this->l('Please validate name').' - '.$lang['name'];
            }  
            if(empty(Tools::getValue('url_'.$lang['id_lang'])) || !Validate::isUrl(Tools::getValue('url_'.$lang['id_lang']))) {
                $validation &= false;
                $this->errors[] = $this->l('Please validate the icon url').' - '.$lang['name'];
            }  
        }
        return $validation;    
    }
    /**
    * Process after validetForm(), save or update icon
    */
    public function postFormProcess() {
        $id_icon = Tools::getValue('id_icon');
        $icon = null;
        if ($id_icon > 0) {
            $icon = new EterIcons($id_icon);
        } else {
            $icon = new EterIcons(); 
            $icon->position = 0;
        }
        if($image = $this->module->uploadImage('image',$this->uploadImageDir)) {
            $icon->image = $image;
        }

        $icon->active = Tools::getValue('active');
        $languages = $this->getLanguages();
        foreach ($languages as $lang) {
            $icon->name[$lang['id_lang']] = Tools::getValue('name_'.$lang['id_lang']);
            $icon->url[$lang['id_lang']] = Tools::getValue('url_'.$lang['id_lang']);
        }
        $res = (bool)$icon->save();
        return $res;
    }
    /**
    * Render icon list
    */
    public function renderList()
    {
        $fields_list = array(
            'id_icon' => array(
                'title' => $this->l('Id'),
                'search' => false,
                'orderby' => false,
            ),
            'color' => array(
                'title' => $this->l('Image'),
                'search' => false,
                'orderby' => false,
            ),
            'name' => array(
                'title' => $this->l('Name'),
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
        $helper_list->title = $this->l('icons');
        $helper_list->shopLinkType = '';
        $helper_list->table = 'icons';
        $helper_list->actions = array('edit', 'delete');
        $helper_list->identifier = 'id_icon';
        $helper_list->_defaultOrderBy = 'position';
        $helper_list->is_cms = true;
        $helper_list->toolbar_btn['new'] =  array(
            'href' => $this->context->link->getAdminLink('AdminEterIcons', true,[],['add' => true]),
            'desc' => $this->trans('Add new', array(), 'Admin.Actions')
        );
        /*This lines from 188 to 191 allow to create drag and dop*/
        $helper_list->position_identifier = 'position';
        $helper_list->position_group_identifier = 'position';
        $helper_list->orderBy = 'position';
        $helper_list->orderWay = 'ASC';

        $helper_list->currentIndex = $this->context->link->getAdminLink('AdminEterIcons');
        $helper_list->token = Tools::getAdminTokenLite('AdminEterIcons');
        $this->_helperlist = $helper_list;

        $data = $this->module->_icons->getData();
        foreach ($data as &$value) {
            $src = $this->context->link->getMediaLink('/modules/eter_icons/images/'.$value['image']);
            $value['color']['texture'] = $src;
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

        $data = EterIcons::getAddFieldsValues(Tools::getValue('id_icon'));
        if($data && $data['image']){
            $src = $this->context->link->getMediaLink('/modules/eter_icons/images/'.$data['image']);
            $imagehtml = '<img alt="preview" style="max-width:128px;" src="'.$src.'">';
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('icons'),
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
                        'desc' => $this->l('Image size (128 x 80)')
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
                        'label' => $this->l('Name'),
                        'name' => 'name',
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
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        if (Tools::getValue('id_icon')) {
            $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_icon');
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
        $helper->submit_action = 'submiticon';
        $helper->show_cancel_button = true;
        $helper->back_url =  $this->context->link->getAdminLink('AdminEterIcons', false);
        $helper->currentIndex = $this->context->link->getAdminLink('AdminEterIcons', false);
        $helper->token = Tools::getAdminTokenLite('AdminEterIcons');
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