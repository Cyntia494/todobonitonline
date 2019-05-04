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
* @package    Eter_ImageSlider
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/

class AdminEterImageSliderController extends ModuleAdminController
{
    public $bootstrap = true;
    public $path = "";
    public $render = true;
    /**
    * Initialize controller content
    */
    public function initContent()
    {
        $this->path = _PS_MODULE_DIR_.'eter_imageslider/images/';
        if(!is_writable($this->path)) {
            $imageFolder = '<strong>'.$this->path.'</strong>';
            $this->errors[] =  sprintf($this->l('Please verify folder %s (Write Permission Needed).'),$imageFolder);
        }
        if(Tools::isSubmit('submitSlide')){
            $this->render = false;
            if($this->validateForm()){
                if($this->postFormProcess()){
                    $this->confirmations[] = $this->l('The slide have been saved.');
                    $this->render = true;
                }
                else{
                    $this->errors[] = $this->l('The slide have not been saved.');
                }
            }
            else{
                $html = $this->renderAddForm();
            }
        }

        if(Tools::isSubmit('add') || Tools::isSubmit('updateimageslider')){
            $this->render = false;
            $html = $this->renderAddForm();
        }
        if(Tools::isSubmit('deleteimageslider')){
            $id_homeslider_slides = Tools::getValue('id_homeslider_slides');
            if($this->deleteSlide($id_homeslider_slides)){
                $this->confirmations[] = $this->l('Slide has been deleted');
                Eter_HomeSlide::reorderPosition();
            }else {
                $this->errors[] = $this->l('Slide has not been deleted');
            }   
        }
        if($this->render){
            $html = $this->renderList();
        }
        $before_html = EterHelper::getInfo($this->module);
        $this->context->smarty->assign(array(
            'content' => $before_html.$html,
        ));
    }

    /**
    * Validate form
    */
    public function validateForm() 
    {
        $res = true;
        $id_homeslider_slides = (bool)Tools::getValue('id_homeslider_slides');
        if($error = $this->module->validateImage('background',$this->path,1500,600,!$id_homeslider_slides)) {
            $res &= false;
            $this->errors[] = $error;
        }
        if($error = $this->module->validateImage('image',$this->path,400,496,false)) {
            $res &= false;
            $this->errors[] = $error;
        }
        
        $languages = $this->getLanguages();
        foreach ($languages as $lang) {
            if(empty(Tools::getValue('title_'.$lang['id_lang']))) {
                $res &= false;
                $this->errors[] = $this->l('Please validate title').' - '.$lang['name'];
            }
            if(empty(Tools::getValue('url_'.$lang['id_lang'])) || !Validate::isUrl(Tools::getValue('url_'.$lang['id_lang']))) {
                $res &= false;
                $this->errors[] = $this->l('Please validate the slide url').' - '.$lang['name'];
            }
            if(empty(Tools::getValue('legend_'.$lang['id_lang']))) {
                $res &= false;
                $this->errors[] = $this->l('Please validate caption').' - '.$lang['name'];
            }
            if(empty(Tools::getValue('description_'.$lang['id_lang']))) {
                $res &= false;
                $this->errors[] = $this->l('Please validate description').' - '.$lang['name'];
            }   
        }
        return $res;
    }

    /**
    * Save settings
    */
    public function postFormProcess() {
        $slide = null;
        $id_homeslider_slides = Tools::getValue('id_homeslider_slides');
        if ($id_homeslider_slides>0) {
            $slide = new Eter_HomeSlide($id_homeslider_slides);
        } else {
            $slide = new Eter_HomeSlide();
            $slide->position = $slide->getLastPosition()+1;
        }
        $slide->side = Tools::getValue('side');
        
        if($background = $this->module->uploadImage('background',$this->path)) {
            $slide->background = $background;
        }
        if($image = $this->module->uploadImage('image',$this->path)) {
            $slide->image = $image;
        }
        $slide->active = Tools::getValue('active');
        $languages = $this->getLanguages();
        foreach ($languages as $lang) {
            $slide->title[$lang['id_lang']] = Tools::getValue('title_'.$lang['id_lang']);
            $slide->url[$lang['id_lang']] = Tools::getValue('url_'.$lang['id_lang']);
            $slide->legend[$lang['id_lang']] = Tools::getValue('legend_'.$lang['id_lang']);
            $slide->description[$lang['id_lang']] = Tools::getValue('description_'.$lang['id_lang']);
        }
        $res = (bool)$slide->save();
        return $res;
    }

    public function deleteSlide ($id_homeslider_slides){
        $slide = null;
        if ($id_homeslider_slides > 0) {
            $slide = new Eter_HomeSlide($id_homeslider_slides);
            if($slide->delete()){
                return true;
            }
        } 
        return false;
    }

    /**
    * Ajax proceess
    */
    public function ajaxPreProcess() {
        if (Tools::getValue('action') == "activeimageslider") {
            $slide = new Eter_HomeSlide(Tools::getValue('id_homeslider_slides'));
            $slide->active = ($slide->active) ? 0 : 1;
            $slide->save() ?
            die(json_encode(array('success' => true, 'text' => $this->l('The status has been updated successfully')))) :
            die(json_encode(array('success' => false, 'error' => true, 'text' => $this->l('Failed to update the status'))));
        }
        if (Tools::getValue('action') == "updatePositions") {
            $options = Tools::getValue('homeslider_slides');
            foreach ($options as $key => $value) {
                $code = str_replace('tr_position_', '', $value);
                $position = explode('_', $code);
                $slide = new Eter_HomeSlide((int)$position[0]);
                $slide->position = $key;
                $slide->save();
            }
        }  
    }
    /**
    * Render list
    */
    public function renderList()
    {
        $fields_list = array(
            'id_homeslider_slides' => array(
                'title' => $this->l('Id'),
                'search' => false,
                'orderby' => false,
            ),
            'background' => array(
                'title' => $this->l('Image'),
                'search' => false,
                'orderby' => false,
                'image_back' => '#',
                'image' => '#',
            ),
            'main' => array(
                'title' => $this->l('Main Image'),
                'search' => false,
                'orderby' => false,
                'image_main' => '#',
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
        $helper_list->override_folder = 'imageslider/';
        $helper_list->title = $this->l('Image Slider');
        $helper_list->shopLinkType = '';
        $helper_list->table = 'imageslider';
        $helper_list->actions = array('edit', 'delete');
        $helper_list->identifier = 'id_homeslider_slides';
        $helper_list->_defaultOrderBy = 'position';
        $helper_list->is_cms = true;
        $helper_list->toolbar_btn['new'] =  array(
            'href' => $this->context->link->getAdminLink('AdminEterImageSlider', true,[],['add' => true]),
            'desc' => $this->trans('Add new', array(), 'Admin.Actions')
        );
        /*This lines from 188 to 191 allow to create drag and dop*/
        $helper_list->position_identifier = 'position';
        $helper_list->position_group_identifier = 'position';
        $helper_list->orderBy = 'position';
        $helper_list->orderWay = 'ASC';
        $helper_list->imageType = 'ASC';

        $helper_list->currentIndex = $this->context->link->getAdminLink('AdminEterImageSlider');
        $helper_list->token = Tools::getAdminTokenLite('AdminEterImageSlider');
        $this->_helperlist = $helper_list;

        $data =  Eter_HomeSlide::getData();
        foreach ($data as &$value) {
            $src = $this->context->link->getMediaLink('/modules/eter_imageslider/images/'.$value['background']);
            $value['image_back'] = $src;
            $src = $this->context->link->getMediaLink('/modules/eter_imageslider/images/'.$value['image']);
            $value['image_main'] = $src;
        }

        $helper_list->listTotal = count($data);

        return $helper_list->generateList($data, $fields_list);  
    }

    public function renderAddForm()
    {
        $imagehtml = "";
        $backhtml = "";
        if (Tools::isSubmit('id_homeslider_slides')) {
            $data = new Eter_HomeSlide((int)Tools::getValue('id_homeslider_slides'));
            $imagehtml = "";
            if($data->image){
                $src = $this->context->link->getMediaLink('/modules/eter_imageslider/images/'.$data->image);
                $imagehtml = '<img alt="preview" style="height:100px;" src="'.$src.'">';
            }
            $backhtml = "";
            if($data->background){
                $src = $this->context->link->getMediaLink('/modules/eter_imageslider/images/'.$data->background);
                $backhtml = '<img alt="preview" style="height:100px;" src="'.$src.'">';
            }
        }

        $sides[] = ['id_side' => 1, 'name' => 'left'];
        $sides[] = ['id_side' => 2, 'name' => 'Right'];
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Slide information'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Active'),
                        'name' => 'active',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            )
                        ),
                    ),
                    array(
                        'type' => 'select',                              
                        'label' => $this->l('Content Position'),         
                        'name' => 'side',                     
                        'required' => true,                              
                        'options' => array(
                            'query' => $sides,                           
                            'id' => 'id_side',                           
                            'name' => 'name'                               
                        )
                    ), 
                    array(
                        'type' => 'file',
                        'label' => $this->l('Background Image'),
                        'name' => 'background',
                        'required' => true,
                        'display_image' => true,
                        'image' => $backhtml ? $backhtml : false,
                        'desc' => $this->l('Maximum image size: 1500 x 600')
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Main Image'),
                        'name' => 'image',
                        'required' => true,
                        'display_image' => true,
                        'image' => $imagehtml ? $imagehtml : false,
                        'desc' => $this->l('Maximum image size: 400 x 496')
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
                        'label' => $this->l('Description'),
                        'name' => 'description',
                        'required' => true, 
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Target URL'),
                        'name' => 'url',
                        'required' => true,
                        'required' => true, 
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Button Text'),
                        'name' => 'legend',
                        'required' => true, 
                        'lang' => true,
                    ),
                    
                ),
                'buttons' => array(
                    array(
                        'title' => $this->l('Cancel'),
                        'href' => $this->context->link->getAdminLink('AdminEterImageSlider',true),
                        'icon' => "process-icon-cancel"
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        if (Tools::isSubmit('id_homeslider_slides')) {
            $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_homeslider_slides');
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
        $helper->submit_action = 'submitSlide';
        $helper->show_cancel_button = false;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminEterImageSlider');
        $helper->token = $this->context->link->getAdminLink('AdminEterImageSlider');
        $language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $language->id,
                'iso_code' => $language->iso_code
            ),
            'fields_value' => Eter_HomeSlide::getAddFieldsValues(),
            'languages' => $this->getLanguages(),
            'id_language' => $this->context->language->id,
            'image_baseurl' => $this->path.'images/'
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