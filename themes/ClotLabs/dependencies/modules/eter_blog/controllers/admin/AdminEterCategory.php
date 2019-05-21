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
* @package    Eter_Blogs
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/
class AdminEterCategoryController extends ModuleAdminController 
{
    /**
    * Define globlal variables
    */
    public $name;
    public $tab;
    public $html;
    public $bootstrap = true;
    public $saved = false;
    public $uploadImageDir;
    /**
    * Initialize controller content
    */
    public function initContent()
    {
        $html = "";
        $this->uploadImageDir = _PS_MODULE_DIR_.'eter_blog/images/';
        if(!is_writable($this->uploadImageDir)) {
            $imageFolder = '<strong>'.$this->uploadImageDir.'</strong>';
            $this->errors[] =  sprintf($this->l('Please verify folder %s (Write Permission Needed).'),$imageFolder);
        }
        if(Tools::isSubmit('submitData')) {
            if ($this->saved) {
                $html = $this->renderList();
            } else {
                $html = $this->renderAddForm();
            }
        } else if (Tools::isSubmit('add') || (Tools::isSubmit('updateSectionCategory'))) {
            $html = $this->renderAddForm();
        } else {
            $html = $this->renderList();
        }
        $before_html = EterHelper::getInfo($this->module);
        $this->context->smarty->assign(array(
            'content' => $before_html.$html,
        ));
    }
    /* 
    * Process data requests 
    */
    public function postProcess()
    {
        if(Tools::isSubmit('submitBulkdeleteSectionCategory')) {
            try {
                $categories = Tools::getValue('SectionCategoryBox');
                foreach ($categories as $category) {
                    $categoryObj = new SectionCategory($category);
                    $categoryObj->delete();
                }
                $this->confirmations[] = $this->l('Categories have been deleted');
                $html = $this->renderList();
            } catch (Exception $e) {
                $this->errors[] = $this->l('Some posts have dependencies on this category.');
            }
        } else if (Tools::isSubmit('submitData')) {
            if($this->postvalidation()) {
                $res = $this->saveData(); 
                if (!$res) {
                    $this->errors[] = $this->l('Category could not be save.');
                } else {
                    $this->confirmations[] = $this->l('Category was saved');
                    $this->saved = true;
                }
            }          
        } else if (Tools::isSubmit('deleteSectionCategory')) {
            try {
                $id = Tools::getValue('id_category');
                $data = new SectionCategory($id);
                $res = $data->delete();
                if (!$res) {
                    $this->errors[] = $this->l('Category could not be save.');
                } else {
                    $this->confirmations[] = $this->l('Category was deleted');
                }
            } catch (Exception $e) {
                $this->errors[] = $this->l('Some posts have dependencies on this category.');
            }
        }
    }
    /*
    * SaveData
    */
    protected function saveData()
    {
        $languages = $this->getLanguages();
        if ($idCategory = Tools::getValue('id_category')) {
            $Category = new SectionCategory($idCategory);
        } else {
            $Category = new SectionCategory();
        }
        $Category->active = 1;
        foreach ($languages as $language) {
            $lang = $language['id_lang'];
            $Category->url[$language['id_lang']] = str_replace(" ", "-", Tools::getValue('url_'.$language['id_lang']));         
            $Category->name[$language['id_lang']] = Tools::getValue('name_'.$language['id_lang']);
            $Category->resumen[$language['id_lang']] = "";
        }
        return $Category->save();
    }
    /* 
    * Form validation 
    */
    public function postvalidation()
    {
        $languages = $this->getLanguages();
        if (Tools::isSubmit('submitData')) {
            foreach ($languages as $language) {
                if (!Validate::isUrl(Tools::getValue('url_'. $language['id_lang']))) {
                    $this->errors[] = $this->l('Please verify url fields.');
                }
                if (Tools::getValue('name_' . $language['id_lang']) == null) {
                    $this->errors[] = $this->l('Please verify name lang fields.');
                }
            }    
            if (count($this->errors)) {
                return false;
            } 
        }
        return true;
    }  
    /*
    * Render List
    */
    public function renderList()
    {
        $fields_list = array(
            'id_category' => array(
                'title' => $this->l('ID CATEGORY'),
                'search' => false,
            ),
            'url' => array(
                'title' => $this->l('URL'),
                'search' => false,
            ),
            'name' => array(
                'title' => $this->l('NAME'),
                'search' => false,
            )
        );
        if (!Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')) {
            unset($fields_list['shop_name']);
        }
        $helper_list = new HelperList();
        $helper_list->module = $this->module;
        $helper_list->title = $this->l('categories');
        $helper_list->shopLinkType = '';
        $helper_list->table = "SectionCategory";
        $helper_list->actions = array('edit', 'delete');
        $helper_list->identifier = 'id_category';
        $helper_list->toolbar_btn['new'] =  array(
            'href' => $this->context->link->getAdminLink('AdminEterCategory', true,[],['add' => true]),
            'desc' => $this->trans('Add new', array(), 'Admin.Actions')
        );
        $helper_list->bulk_actions = array('delete' => array(
            'text' => $this->trans('Delete selected', array(), 'Admin.Notifications.Info'),
            'confirm' => $this->trans('Delete selected items?', array(), 'Admin.Notifications.Info'), 'icon' => 'icon-trash')
        );
        $helper_list->currentIndex = $this->context->link->getAdminLink('AdminEterCategory');
        $helper_list->token = Tools::getAdminTokenLite('AdminEterCategory');
        $this->_helperlist = $helper_list;

        $data = SectionCategory::getData();
        $helper_list->listTotal = count($data);

        return $helper_list->generateList($data, $fields_list);
    }
    /* 
    * Render config form 
    */
    public function renderAddForm()
    {
        $image = "";
        $image1 = "";
        $data = [];
        $data = SectionCategory::getAddFieldsValues();

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Category'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Name'),
                        'name' => 'name',
                        'required' => true,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Url Rewrite'),
                        'name' => 'url',
                        'required' => true,
                        'lang' => true,
                        'desc' => $this->l('this url rewrite will be used to search your blog category, content example: prestashop-module --> url exaple www.eterlabs.com/blog/prestashop-module')
                    ),
                ),
                'buttons' => array(
                    array(
                        'title' => $this->l('Cancel'),
                        'href' => $this->context->link->getAdminLink('AdminEterCategory',true),
                        'icon' => "process-icon-cancel"
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );
        if (Tools::getValue('id_category')) {
            $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_category');
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
        $helper->submit_action = 'submitData';
        $helper->show_cancel_button = false;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminEterCategory',false);
        $helper->token = Tools::getAdminTokenLite('AdminEterCategory');
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
