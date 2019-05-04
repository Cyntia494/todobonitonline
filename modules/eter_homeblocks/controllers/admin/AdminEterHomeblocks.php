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
* @package    Eter_Homeblocks
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/
class AdminEterHomeblocksController extends ModuleAdminController
{
    /**
    * Define globlal variables
    */
    public $bootstrap = true;
    public $_path;

    /**
    * Initialize controller content
    */
    public function initContent()
    {
        parent::initContent();
        $this->uploadImageDir = _PS_MODULE_DIR_.'eter_homeblocks/images/';
        if(!is_writable($this->uploadImageDir)) {
            $imageFolder = '<strong>'.$this->uploadImageDir.'</strong>';
            $this->errors[] =  sprintf($this->l('Please verify folder %s (Write Permission Needed).'),$imageFolder);
        }
        if(Tools::isSubmit('submitBlock')){
            if($this->validateForm()) {
                if($this->postFormProcess()) {
                    $html = $this->renderList();
                    $this->confirmations[] = $this->l('The block have been saved.');
                } else {
                    $html = $this->renderForm();
                    $this->errors[] = $this->l('The block have not been saved.');
                }
            } else {
                $html = $this->renderForm();
            }
        } else if (Tools::isSubmit('add') || Tools::isSubmit('updateBlocks')) {
            $html = $this->renderForm();
        } else if (Tools::isSubmit('deleteBlocks')) {
            $id_block = Tools::getValue('id_block');
            if ($id_block > 0) {
                $block = new Block($id_block);
                if($block->delete()) {
                    $this->confirmations[] = $this->l('The block have been saved.');
                } else {
                    $this->errors[] = $this->l('The block have not been deleted.');
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
    * Ajax process, reorder homeblock position when the user modify the rows position
    */
    public function ajaxPreProcess() {
        if (Tools::getValue('action') == "updatePositions") {
            $options = Tools::getValue('block');
            foreach ($options as $key => $value) {
                $code = str_replace('tr_position_', '', $value);
                $position = explode('_', $code);
                $block = new Block((int)$position[0]);
                $block->position = $key;
                $block->save();
            }
        } 
        if (Tools::getValue('action') == "activeBlocks") {
            $block = new Block(Tools::getValue('id_block'));
            $block->active = ($block->active) ? 0 : 1;
            $block->save() ?
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
        $id_block = (bool)Tools::getValue('id_block');
        if($error = $this->module->validateImage('image',$this->uploadImageDir,600,650,!$id_block)) {
            $validation &= false;
            $this->errors[] = $error;
        }  
        $languages = $this->getLanguages();
        foreach ($languages as $lang) {
            if(empty(Tools::getValue('title_'.$lang['id_lang']))) {
                $validation &= false;
                $this->errors[] = $this->l('Please validate title').' - '.$lang['name'];
            }
            if(empty(Tools::getValue('details_'.$lang['id_lang']))) {
                $validation &= false;
                $this->errors[] = $this->l('Please validate details').' - '.$lang['name'];
            }
            if(empty(Tools::getValue('url_name_'.$lang['id_lang']))) {
                $validation &= false;
                $this->errors[] = $this->l('Please validate url title').' - '.$lang['name'];
            }  
            if(empty(Tools::getValue('url_'.$lang['id_lang'])) || !Validate::isUrl(Tools::getValue('url_'.$lang['id_lang']))) {
                $validation &= false;
                $this->errors[] = $this->l('Please validate the block url').' - '.$lang['name'];
            } 
        }
        return $validation;    
    }

    /**
    * Process after validateForm
    */
    public function postFormProcess() {
        $id_block = Tools::getValue('id_block');
        $block = null;
        if ($id_block > 0) {
            $block = new Block($id_block);
        } else {
            $block = new Block(); 
            $block->position = 0;
        }
        if($image = $this->module->uploadImage('image',$this->uploadImageDir)) {
            $block->image = $image;
        }

        $block->active = Tools::getValue('active');
        $languages = $this->getLanguages();
        foreach ($languages as $lang) {
            $block->title[$lang['id_lang']] = Tools::getValue('title_'.$lang['id_lang']);
            $block->details[$lang['id_lang']] = Tools::getValue('details_'.$lang['id_lang']);
            $block->url_name[$lang['id_lang']] = Tools::getValue('url_name_'.$lang['id_lang']);
            $block->url[$lang['id_lang']] = Tools::getValue('url_'.$lang['id_lang']);
        }
        $res = (bool)$block->save();
        return $res;
    }
    /**
    * Render homeblocks list
    */
    public function renderList()
    {
        $fields_list = array(
            'id_block' => array(
                'title' => $this->l('Id'),
                'search' => false,
                'orderby' => false,
            ),
            'image' => array(
                'title' => $this->l('Image'),
                'search' => false,
                'orderby' => false,
                'image' => '#',
            ),
            'title' => array(
                'title' => $this->l('Title'),
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
        $helper_list->override_folder = 'homeblocks/';
        $helper_list->title = $this->l('Blocks');
        $helper_list->shopLinkType = '';
        $helper_list->table = 'Blocks';
        $helper_list->actions = array('edit', 'delete');
        $helper_list->identifier = 'id_block';
        $helper_list->_defaultOrderBy = 'position';
        $helper_list->is_cms = true;
        $helper_list->toolbar_btn['new'] =  array(
            'href' => $this->context->link->getAdminLink('AdminEterHomeblocks', true,[],['add' => true]),
            'desc' => $this->trans('Add new', array(), 'Admin.Actions')
        );
        /*This lines from 188 to 191 allow to create drag and dop*/
        $helper_list->position_identifier = 'position';
        $helper_list->position_group_identifier = 'position';
        $helper_list->orderBy = 'position';
        $helper_list->orderWay = 'ASC';
        $helper_list->imageType = 'ASC';

        $helper_list->currentIndex = $this->context->link->getAdminLink('AdminEterHomeblocks');
        $helper_list->token = Tools::getAdminTokenLite('AdminEterHomeblocks');
        $this->_helperlist = $helper_list;

        $data =  Block::getData();
        foreach ($data as &$value) {
            $src = $this->context->link->getMediaLink('/modules/eter_homeblocks/images/'.$value['image']);
            $value['image_url'] = $src;
        }

        $helper_list->listTotal = count($data);

        return $helper_list->generateList($data, $fields_list);  
    }
    /**
    * Render config form
    */
    public function renderForm()
    {
        $data = new Block((int)Tools::getValue('id_block'));
        $imagehtml = "";

        if($data->image){
            $src = $this->context->link->getMediaLink('/modules/eter_homeblocks/images/'.$data->image);
            $imagehtml = '<img alt="preview" style="height:100px;" src="'.$src.'">';
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Block'),
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
                        'desc' => $this->l('Maximum image size: 600 x 650')
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enabled'),
                        'name' => 'active',
                        'is_bool' => true,
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
                        'type' => 'textarea',
                        'label' => $this->l('Details'),
                        'name' => 'details',
                        'autoload_rte' => true,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Link Text'),
                        'name' => 'url_name',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Url Target'),
                        'name' => 'url',
                        'lang' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        if (Tools::isSubmit('id_block')) {
            $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_block');
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->module = $this->module;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitBlock';
        $helper->show_cancel_button = true;
        $helper->back_url =  $this->context->link->getAdminLink('AdminEterHomeblocks');
        $helper->currentIndex = $this->context->link->getAdminLink('AdminEterHomeblocks');
        $helper->token = Tools::getAdminTokenLite('AdminEterHomeblocks');
        $language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $language->id,
                'iso_code' => $language->iso_code
            ),
            'fields_value' => Block::getAddFieldsValues(),
            'languages' => $this->getLanguages(),
            'id_language' => $this->context->language->id,
            'image_baseurl' => $this->_path.'images/'
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