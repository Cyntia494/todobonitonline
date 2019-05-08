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
* @package    Eter_Contact
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/


class AdminContactController extends ModuleAdminController
{

	public $bootstrap = true;
    public $uploadImageDir;
    private $popupdata;
    /**
     * Controller contructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->controllerName = "AdminContact";
        $this->inputvalidator = new EterInputsValidator($this->module);
    }
    /**
     * process post request
     */
    public function postProcess()
    {
        try {
            if(Tools::isSubmit('submitContact')) {
                $schema = $this->getPopUpSchema(true);
                $request = $this->inputvalidator->validateParameters($schema['form']['input']);
                $this->savePostData($request);
            }
        } catch (PrestaShopException $e) {
            $errors = explode("-|-", $e->getMessage());
            $this->errors = $errors;
        }
        return false;

    }
    /**
     * Initialize controller content
     */
    public function initContent()
    {
        parent::initContent();
        if (isset($this->context->cookie->confirmation)){
            $this->confirmations[] = $this->context->cookie->confirmation;
            $this->context->cookie->__unset('confirmation');
        }

        $html = $this->renderForm();
        $before_html = EterHelper::getInfo($this->module);
        $this->context->smarty->assign(array(
            'content' => $before_html.$html,
        ));
    }
    /**
     * Save popup post data
     */
    public function savePostData($request) 
    {
        try {
            Configuration::updateValue('CONTACT_FACEBOOK_ACCOUNT', $request->CONTACT_FACEBOOK_ACCOUNT);
            Configuration::updateValue('CONTACT_WHATSAPP_ACCOUNT', $request->CONTACT_WHATSAPP_ACCOUNT);
            Configuration::updateValue('CONTACT_PHONE_ACCOUNT', $request->CONTACT_PHONE_ACCOUNT);
            Configuration::updateValue('CONTACT_FORM_EMAILS', $request->CONTACT_FORM_EMAILS);
            Configuration::updateValue('CONTACT_FORM_COLOR', $request->CONTACT_FORM_COLOR);
            Configuration::updateValue('CONTACT_FONT_COLOR', $request->CONTACT_FONT_COLOR);
            
            $cssFile = _PS_MODULE_DIR_.'eter_contact/views/assets/css/colors.css';
            $baseFile = file_get_contents(_PS_MODULE_DIR_.'eter_contact/views/assets/css/colors.txt');
            $baseFile = str_replace("{back-color}", $request->CONTACT_FORM_COLOR,$baseFile);
            $baseFile = str_replace("{font-color}", $request->CONTACT_FONT_COLOR,$baseFile);
            file_put_contents($cssFile,$baseFile);
            $this->context->cookie->__set('confirmation', $this->l('The configuration has been saved'));
            Tools::redirect($this->context->link->getAdminLink($this->controllerName, true));
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();   
        }
    }
    /**
     * Render the admin advertisement form
     */
    public function renderForm()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this->module;
        $helper->submit_action = 'submitContact';
        $helper->currentIndex = $this->context->link->getAdminLink($this->controllerName, false);
        $helper->token = Tools::getAdminTokenLite($this->controllerName);
        $helper->fields_value = $this->getFormData();
        $helper->languages = EterInputsValidator::getLanguages();
        $helper->override_folder = '/';
        return $helper->generateForm(array($this->getPopUpSchema(true)));
    }
    /**
     * Preper form inputs
     */
    private function getPopUpSchema($includedata = false) 
    {
        $moduleurl = EterHelper::getModuleUrl('ps_emailsubscription');
        if ($includedata) {
            $data = $this->getFormData();
        } else {
            $data['CONTACT_IMAGE_DESKTOP'] = null;
        }
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Contact Configurator'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    'CONTACT_FACEBOOK_ACCOUNT' => array(
                        'type' => 'text',
                        'label' => $this->l('Facebook Account'),
                        'name' => 'CONTACT_FACEBOOK_ACCOUNT',
                        'validate' => 'isString',
                        'required' => false,
                        'class' => 'fixed-width-xl',
                        'lang' => false,
                    ),
                    'CONTACT_WHATSAPP_ACCOUNT' => array(
                        'type' => 'text',
                        'label' => $this->l('Whatsapp Number'),
                        'name' => 'CONTACT_WHATSAPP_ACCOUNT',
                        'validate' => 'isString',
                        'required' => false,
                        'class' => 'fixed-width-xl',
                        'lang' => false,
                    ),
                    'CONTACT_PHONE_ACCOUNT' => array(
                        'type' => 'text',
                        'label' => $this->l('Phone Number'),
                        'name' => 'CONTACT_PHONE_ACCOUNT',
                        'validate' => 'isString',
                        'required' => false,
                        'class' => 'fixed-width-xl',
                        'lang' => false,
                    ),
                    'CONTACT_FORM_EMAILS' => array(
                        'type' => 'text',
                        'label' => $this->l('Emails for contact form'),
                        'name' => 'CONTACT_FORM_EMAILS',
                        'validate' => 'isString',
                        'required' => false,
                        'class' => 'fixed-width-xl',
                        'lang' => false,
                    ),
                    'CONTACT_FORM_COLOR' => array(
                        'type' => 'color',
                        'label' => $this->l('Contact main color'),
                        'name' => 'CONTACT_FORM_COLOR',
                        'validate' => 'isString',
                        'required' => false,
                        'class' => 'fixed-width-xl',
                        'lang' => false,
                    ),
                    'CONTACT_FONT_COLOR' => array(
                        'type' => 'color',
                        'label' => $this->l('Contact font color'),
                        'name' => 'CONTACT_FONT_COLOR',
                        'validate' => 'isString',
                        'required' => false,
                        'class' => 'fixed-width-xl',
                        'lang' => false,
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );
        
    }

    /**
     * Prepare and set form data
     */
    private function getFormData() 
    {
        $schema = $this->getPopUpSchema();
        $languages = EterInputsValidator::getLanguages();
        foreach ($schema['form']['input'] as $key => $value) {
            if (isset($value['validate']) && $value['validate'] == "isImage") {
                $data[$key] = EterHelper::getModuleFormImage(Configuration::get($key),'eter_popup');
            } else if (isset($value['lang']) && $value['lang']) {
                foreach ($languages as $lang) {
                    $idlang = $lang['id_lang'];
                    $data[$key][$idlang] = Tools::getValue($key,Configuration::get($key,$idlang));
                }
            } else {
                $value = Tools::getValue($key,Configuration::get($key));
                $data[$key] = $value;
            }
        }
        return $data;
    }
    /**
     * Set admin media
     */
    public function setMedia()
    {
        parent::setMedia();
        $media = _PS_MODULE_DIR_.'eter_popup/views/assets/';
        $this->addJS($media.'js/module.js', 'all');
        $this->addCSS($media.'css/module.css', 'all');
    }
}
