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
class AdminEterBlogsController extends ModuleAdminController 
{
    /**
    * Define globlal variables
    */
    public $html;
    public $name;
    public $bootstrap = true;
    public $saved = false;
    public $uploadImageDir;
    /**
    * Initialize controller content
    */
    public function init()
    {   
        parent::init();
        $this->uploadImageDir = _PS_MODULE_DIR_.'eter_blog/images/';
    }
    /**
    * Initialize controller content
    */
    public function initContent()
    {   
        $html = "";
        $data = SectionCategory::getData(); 
        if(!is_writable($this->uploadImageDir)) {
            $imageFolder = '<strong>'.$this->uploadImageDir.'</strong>';
            $this->errors[] =  sprintf($this->l('Please verify folder %s (Write Permission Needed).'),$imageFolder);
        }
        if(count($data) == 0) {
            $href = $this->context->link->getAdminLink('AdminEterCategory',true);
            $create = '<a href="'.$href.'">'.$this->l('create category').'</a>';
            $this->warnings[] = sprintf($this->l('First you need to create a category %s.'),$create);
        }
        if(!is_writable($this->uploadImageDir)) {
            $imageFolder = '<strong>'.$this->uploadImageDir.'</strong>';
            $this->errors[] =  sprintf($this->l('Please verify folder %s (Write Permission Needed).'),$imageFolder);
        }
        if(Tools::isSubmit('submitBulkdeleteSectionBlog')) {
            $blogs = Tools::getValue('SectionBlogBox');
            foreach ($blogs as $blog) {
                $blogObj = new SectionBlog($blog);
                $blogObj->delete();
            }
            $this->confirmations[] = $this->l('Blogs have been deleted');
            $html = $this->renderList();
        } else if(Tools::isSubmit('submitData')) {
            if ($this->saved) {
                $html = $this->renderList();
            } else {
                $html = $this->renderAddForm();
            }
        } else if (Tools::isSubmit('add') || (Tools::isSubmit('updateSectionBlog'))) {
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
        if (Tools::isSubmit('submitData')) {
            if($this->postvalidation()) {
                $res = $this->saveData(); 
                if (!$res) {
                    $this->errors[] = $this->l('Blog could not be save.');
                } else {
                    $this->confirmations[] = $this->l('Blog was saved');
                    $this->saved = true;
                }
            }          
        } else if (Tools::isSubmit('deleteSectionBlog')) {
            $id = Tools::getValue('id_blog');
            $data = new SectionBlog($id);
            $res = $data->delete();
            if (!$res) {
                $this->errors[] = $this->l('Blog could not be save.');
            } else {
                $this->confirmations[] = $this->l('Blog was deleted');
            }
        }
    }
    /*
    * SaveData
    */
    protected function saveData()
    {
        $errors = array();
        if ($idBlog = Tools::getValue('id_blog')) {
            $blog = new SectionBlog($idBlog);
        } else {
            $blog = new SectionBlog();
            $blog->created_at = date('Y-m-d');
        }

        if($image = $this->module->_media->uploadImage('banner',$this->uploadImageDir)) {
            $blog->banner = $image;
        }

        $blog->active = Tools::getValue('active');
        $blog->category = Tools::getValue('category');
        $blog->products = Tools::getValue('products');
        $languages = $this->getLanguages();
        foreach ($languages as $language) {
            $lang = $language['id_lang'];
            $blog->url[$language['id_lang']] = str_replace(" ", "-", Tools::getValue('url_'.$language['id_lang']));         
            $blog->name[$language['id_lang']] = Tools::getValue('name_'.$language['id_lang']);
            $blog->details[$language['id_lang']] = Tools::getValue('details_'.$language['id_lang']);
            $blog->html[$language['id_lang']] = Tools::getValue('html_'.$language['id_lang']);
        }
        return $blog->save();
    }

    /* 
    *  Form validation 
    */
    public function postvalidation()
    {
        $languages = $this->getLanguages();
        if (Tools::isSubmit('submitData')) {
            $has_image = !(bool)Tools::getValue('has_image');
            if($error = $this->module->_media->validateImage('banner',$this->uploadImageDir,770,433,$has_image,$this->module)) {
                $this->errors[] = $error;
            } 

            if(!Tools::getValue('category')) {
                $this->errors[] = $this->l('Please select a category for the post.');
            }  

            foreach ($languages as $language) {
                if (Tools::getValue('url_'.$language['id_lang']) == "" || !Validate::isUrl(Tools::getValue('url_'.$language['id_lang']))) {
                    $this->errors[] = $this->l('Please verify url fields.');
                } 
                if (Tools::getValue('name_' . $language['id_lang']) == null) {
                    $this->errors[] = $this->l('Please verify name lang fields.');
                }
                if (Tools::getValue('details_' . $language['id_lang']) == null) {
                    $this->errors[] = $this->l('Please verify resumen lang fields.');
                }
                if (Tools::getValue('html_' . $language['id_lang']) == null) {
                    $this->errors[] = $this->l('Please verify resumen lang fields.');
                }
            }    
        }
        if (count($this->errors)) {
            return false;
        } 
        return true;
    }  
    /**
     * Ajax process, reorder brands position when the user modify the rows position
     */
    public function ajaxPreProcess() {
        if (Tools::getValue('action') == "activeSectionBlog") {
            $blog = new SectionBlog(Tools::getValue('id_blog'));
            $blog->active = ($blog->active) ? 0 : 1;
            $blog->save() ?
                die(json_encode(array('success' => true, 'text' => $this->l('The status has been updated successfully')))) :
                die(json_encode(array('success' => false, 'error' => true, 'text' => $this->l('Failed to update the status'))));
        } 

    }
    /*
    *  Render List
    */
    public function renderList()
    {
        $fields_list = array(
            'id_blog' => array(
                'title' => $this->l('ID BLOG'),
                'search' => false,
            ),
            'name' => array(
                'title' => $this->l('NAME'),
                'search' => true,
            ),
            'url' => array(
                'title' => $this->l('URL'),
                'search' => false,
            ),
            'active' => array(
                'title' => $this->trans('Displayed', array(), 'Admin.Global'),
                'active' => 'active',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'search' => false,
                'ajax' => true,
                'orderby' => false
            ),
        );
        if (!Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')) {
            unset($fields_list['shop_name']);
        }
        $helper_list = new HelperList();
        $helper_list->module = $this;
        $helper_list->title = $this->l('Blog');
        $helper_list->shopLinkType = '';
        $helper_list->table = "SectionBlog";
        $helper_list->no_link = true;

        $helper_list->actions = array('edit', 'delete');
        $helper_list->identifier = 'id_blog';
        $helper_list->toolbar_btn['new'] =  array(
            'href' => $this->context->link->getAdminLink('AdminEterBlogs', true,[],['add' => true]),
            'desc' => $this->trans('Add new', array(), 'Admin.Actions')
        );
        $helper_list->bulk_actions = array('delete' => array(
            'text' => $this->trans('Delete selected', array(), 'Admin.Notifications.Info'),
            'confirm' => $this->trans('Delete selected items?', array(), 'Admin.Notifications.Info'), 'icon' => 'icon-trash')
        );
        $helper_list->currentIndex = $this->context->link->getAdminLink('AdminEterBlogs');
        $helper_list->token = Tools::getAdminTokenLite('AdminEterBlogs');
        $this->_helperlist = $helper_list;

        $data = SectionBlog::getBlogData();
        $helper_list->listTotal = count($data);

        return $helper_list->generateList($data, $fields_list);  
    }
    /* 
    *  Render the admin form
    */
    public function renderAddForm()
    {
        $image2 = "";
        $data = [];
        $data = $this->module->_blogs->getAddFieldsValues();

        if($img = $data['banner']){
            $src = $this->context->link->getMediaLink('/modules/eter_blog/images/'.$data['banner']);
            $image2 = '<img alt="preview" style="width:250px;" src="'.$src.'">';
        }
        $data['has_image'] = (bool)$data['banner'];
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Blog'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'file',
                        'label' => $this->l('Banner'),
                        'name' => 'banner',
                        'required' => true,
                        'display_image' => true,
                        'image' => $image2 ? $image2 : false,
                        'desc' => $this->l('Image size (770 x 433)')
                    ), 
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable'),
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
                            ),
                        ),
                    ),   
                    array(
                        'type' => 'text',
                        'label' => $this->l('Name'),
                        'required' => true,
                        'name' => 'name',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'select',                              
                        'label' => $this->l('Category:'),         
                        'desc' => $this->l('Select section to append'),  
                        'name' => 'category',                     
                        'required' => true,                              
                        'options' => array(
                            'query' => $this->module->_categories->getData(),                           
                            'id' => 'id_category',                           
                            'name' => 'name'                               
                        )
                    ), 
                    array(
                        'type' => 'text',
                        'label' => $this->l('Resume'),
                        'name' => 'details',
                        'required' => true,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Html'),
                        'name' => 'html',
                        'autoload_rte' => true,
                        'required' => true,
                        'lang' => true,
                    ), 
                    array(
                        'type' => 'text',
                        'label' => $this->l('Url Rewrite'),
                        'name' => 'url',
                        'required' => true,
                        'lang' => true,
                        'desc' => $this->l('this url rewrite will be used to search your post, content example: prestashop-module --> url exaple www.eterlabs.com/blog/post/prestashop-module')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Products'),
                        'name' => 'products',
                        'required' => false,
                        'lang' => false,
                        'desc' => $this->l('Type products id separated by ",". Example: "1,2,67,4"')
                    ),
                ),
                'buttons' => array(
                    array(
                        'title' => $this->l('Cancel'),
                        'href' => $this->context->link->getAdminLink('AdminEterBlogs',true),
                        'icon' => "process-icon-cancel"
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );
        if (Tools::isSubmit('id_blog')) {
            $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_blog');
        }
        $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'has_image');
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
        $helper->currentIndex = $this->context->link->getAdminLink('AdminEterBlogs',false);
        $helper->token = Tools::getAdminTokenLite('AdminEterBlogs');
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
