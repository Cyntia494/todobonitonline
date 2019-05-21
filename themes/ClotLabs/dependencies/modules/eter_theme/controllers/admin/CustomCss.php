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
* @package    Eter_Theme
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/

class CustomCssController extends ModuleAdminController
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
        if(Tools::isSubmit('submitCss')){
            $csscode = Tools::getValue('csscode');
            if($this->postFormProcess($csscode)) {
                $this->confirmations[] = $this->l('The css have been saved.');
            } else {
                $this->errors[] = $this->l('The css have not been saved.');
            }
        }
        $cssfile = _PS_MODULE_DIR_.'eter_theme/views/assets/css/csseditor.css';
        if(!is_writable($cssfile)) {
            $imageFolder = '<strong>'.$cssfile.'</strong>';
            $this->errors[] =  sprintf($this->l('Please verify folder %s (Write Permission Needed).'),$imageFolder);
        }
        $html = $this->addCssEditor();
        $before_html = EterHelper::getInfo($this->module);
        $this->context->smarty->assign(array(
            'content' => $before_html.$html,
        ));
    }
    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();
        $this->page_header_toolbar_btn['modules-save-css'] = array(
            'href' => 'saveCss()',
            'desc' => $this->l('Save css'),
            'icon' => 'process-icon-save'
        );
        $this->toolbar_title = $this->l('Css Editor');
        $this->page_header_toolbar_title = $this->l('Css Editor');
    }
    /**
    * Display progess image
    */
    public function addCssEditor()
    {
        //$loader = _PS_BASE_URL_.'/modules/eter_imagesimport/views/assets/img/loader.gif';
        $this->context->controller->addCSS(_PS_MODULE_DIR_.'eter_theme/lib/addon/hint/show-hint.css', 'all');
        $this->context->controller->addCSS(_PS_MODULE_DIR_.'eter_theme/lib/codemirror.css', 'all');
        $this->context->controller->addJS(_PS_MODULE_DIR_.'eter_theme/lib/codemirror.js', 'all');

        $this->context->controller->addJS(_PS_MODULE_DIR_.'eter_theme/views/assets/js/admin.js', 'all');
        $this->context->controller->addJS(_PS_MODULE_DIR_.'eter_theme/lib/css.js', 'all');
        $this->context->controller->addJS(_PS_MODULE_DIR_.'eter_theme/addon/hint/show-hint.js', 'all');
        $this->context->controller->addJS(_PS_MODULE_DIR_.'eter_theme/addon/hint/css-hint.js', 'all');
        $cssFile = _PS_MODULE_DIR_.'eter_theme/views/assets/css/csseditor.css';
        try {
            $cssContent = file_get_contents($cssFile);
        } catch (Exception $e) {
            $cssContent = "";
        }

        $data['action'] = $this->context->link->getAdminLink('CustomCss', true);
        $data['cssContent'] = $cssContent;
        $this->context->smarty->assign($data);
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'eter_theme/views/templates/editor.tpl');
    }
    /**
    * Validate form values
    */
    public function postFormProcess($content) 
    {   
        $cssFile = _PS_MODULE_DIR_.'eter_theme/views/assets/css/csseditor.css';
        return file_put_contents($cssFile, $content);
    }


}
