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

class EterlabsConfigController extends ModuleAdminController
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
        $this->uploadImageDir = _PS_MODULE_DIR_.'eter_advertisement/images/';
        if(Tools::isSubmit('submitCard')){
            if($this->postFormProcess()) {
                $this->confirmations[] = $this->l('The Configuration have been saved.');
            } else {
                $this->errors[] = $this->l('The Configuration have not been saved.');
            }
        }
        $html = $this->renderForm();
        $before_html = EterHelper::getInfo($this->module);
        $this->context->smarty->assign(array(
            'content' => $before_html.$html,
        ));
    }
    /**
    * Validate form values
    */
    public function postFormProcess() 
    {   
        
        if (Tools::isSubmit('save_general')) {
            Configuration::updateValue('ETERTHEME_GENERAL_ADD', Tools::getValue('listing_add'));
            return true;
        } else if (Tools::isSubmit('save_currency')) {
            Configuration::updateValue('ETERTHEME_CURRENCY_ACTIVE', Tools::getValue('currency_active'));
            Configuration::updateValue('ETERTHEME_CURRENCY_FORMAT', Tools::getValue('format'));
            Configuration::updateValue('ETERTHEME_CURRENCY_DECIMAL', Tools::getValue('decimal'));
            Configuration::updateValue('ETERTHEME_CURRENCY_GROUP', Tools::getValue('group'));
            return true;
        } else if  (Tools::isSubmit('save_copy')) {
            Configuration::updateValue('ETERTHEME_THEME_COPYRIGHT', Tools::getValue('copyright'));
            return true;
        } else if (Tools::isSubmit('save_colors')) {
            Configuration::updateValue('ETERTHEME_COLOR_ACTIVE', Tools::getValue('active'));
            $line = $this->getBGColorLine('headertopcolor',Tools::getValue('headertopcolor'));
            Configuration::updateValue('ETERTHEME_COLOR_HEADERTOP', Tools::getValue('headertopcolor'));

            $line .= $this->getBGColorLine('maincolor',Tools::getValue('maincolor'));
            Configuration::updateValue('ETERTHEME_COLOR_MAINCOLOR', Tools::getValue('maincolor'));

            $line .= "\n".$this->getColorLine('menulink',Tools::getValue('menulink'));
            Configuration::updateValue('ETERTHEME_COLOR_HEADERMENULIK', Tools::getValue('menulink'));

            $line .= "\n".$this->getColorLineHover('menulink',Tools::getValue('menulinkhover'));
            Configuration::updateValue('ETERTHEME_COLOR_HEADERMENULIKHOVER', Tools::getValue('menulinkhover'));

            $line .= "\n".$this->getBGColorLine('footer',Tools::getValue('footer'));
            Configuration::updateValue('ETERTHEME_COLOR_FOOTER', Tools::getValue('footer'));

            $line .= "\n".$this->getColorLine('footerlinks',Tools::getValue('footerlinks'));
            Configuration::updateValue('ETERTHEME_COLOR_FOOTERLINKS', Tools::getValue('footerlinks'));

            $line .= "\n".$this->getBGColorLine('homeblock',Tools::getValue('homeblock'));
            Configuration::updateValue('ETERTHEME_COLOR_HOMEBLOCK', Tools::getValue('homeblock'));

            $line .= "\n".$this->getBGColorLine('menunavcolor',Tools::getValue('menunavcolor'));
            Configuration::updateValue('ETERTHEME_COLOR_NAVCOLOR', Tools::getValue('menunavcolor'));

            $line .= "\n".$this->getColorLine('headericoncolor',Tools::getValue('headericoncolor'));
            Configuration::updateValue('ETERTHEME_COLOR_HEADERICONCOLOR', Tools::getValue('headericoncolor'));

            $line .= "\n".$this->getBGColorLine('footerlinkstitles',Tools::getValue('footerlinkstitles'));
            Configuration::updateValue('ETERTHEME_COLOR_FOOTERLINKSTITLES', Tools::getValue('footerlinkstitles'));

            $line .= "\n".$this->getBGColorLine('footersubscribe',Tools::getValue('footersubscribe'));
            Configuration::updateValue('ETERTHEME_COLOR_FOOTERSUBSCRIBE', Tools::getValue('footersubscribe'));

            $line .= $this->getKitLabsCss(); 
            $cssFile = _PS_MODULE_DIR_.'eter_theme/views/assets/css/custom.css';
            return file_put_contents( $cssFile, $line);
        } else if (Tools::isSubmit('save_demo')) {
            Configuration::updateValue('ETERTHEME_DEMO_ACTIVE', Tools::getValue('demo_active'));
            Configuration::updateValue('ETERTHEME_DEMO_URL', Tools::getValue('demo_url'));
            return true;
        }
    }
    /**
    * Return css style
    */
    public function getKitLabsCss() {
        $dotscolor = Configuration::get('ETERTHEME_COLOR_MAINCOLOR');
        $css = "\n.herohome.owl-carousel .owl-dots .owl-dot.active span {background-color:$dotscolor!important}";
        $css .= "\n.tap {background-color:$dotscolor!important}";
        $css .= "\n.home-blockslider .details a {color:$dotscolor!important}";
        $css .= "\n.home-blockslider .details a:hover {color:#ffffff!important;background-color:$dotscolor!important;}";
        $css .= "\n.new-product-flags li span {background-color:$dotscolor!important}";
        $css .= "\n.herohome.owl-carousel .owl-nav i:hover {color:$dotscolor!important}";
        $css .= "\n#boedoslide .banner-blockslider {border-top: 2px solid $dotscolor!important}";
        return $css;
    }
    /**
    * Return bg color for line
    */
    private function getBGColorLine($class,$color) {
        if ($color) {
            return ".etertheme-{$class} {background-color: {$color}!important}";
        }
    }
    /**
    * Get color line
    */
    private function getColorLine($class,$color) {
        if ($color) {
            return ".etertheme-{$class} {color: {$color}!important}";
        }
    }
    /**
    * Get line hover
    */
    private function getColorLineHover($class,$color) {
        if ($color) {
            return ".etertheme-{$class}:hover {color: {$color}!important}";
        }
    }
    /**
    * Render config form
    */
    public function renderForm()
    {
        $imagehtml = "";
        $data = [];
        $languages = Language::getLanguages(false);
        $data['headertopcolor'] = Configuration::get('ETERTHEME_COLOR_HEADERTOP');
        $data['menulink'] = Configuration::get('ETERTHEME_COLOR_HEADERMENULIK');
        $data['menulinkhover'] = Configuration::get('ETERTHEME_COLOR_HEADERMENULIKHOVER');
        $data['active'] = Configuration::get('ETERTHEME_COLOR_ACTIVE');
        $data['homeblock'] = Configuration::get('ETERTHEME_COLOR_HOMEBLOCK');
        $data['menunavcolor'] = Configuration::get('ETERTHEME_COLOR_NAVCOLOR');
        $data['headericoncolor'] = Configuration::get('ETERTHEME_COLOR_HEADERICONCOLOR');
        $data['footer'] = Configuration::get('ETERTHEME_COLOR_FOOTER');
        $data['footerlinks'] = Configuration::get('ETERTHEME_COLOR_FOOTERLINKS');
        $data['footersubscribe'] = Configuration::get('ETERTHEME_COLOR_FOOTERSUBSCRIBE');
        $data['footerlinkstitles'] = Configuration::get('ETERTHEME_COLOR_FOOTERLINKSTITLES');
        $data['maincolor'] = Configuration::get('ETERTHEME_COLOR_MAINCOLOR');

        //Currency
        $data['currency_active'] = Configuration::get('ETERTHEME_CURRENCY_ACTIVE');
        $data['format'] = Configuration::get('ETERTHEME_CURRENCY_FORMAT');
        $data['decimal'] = Configuration::get('ETERTHEME_CURRENCY_DECIMAL');
        $data['group'] = Configuration::get('ETERTHEME_CURRENCY_GROUP');
        $data['copyright'] = Configuration::get('ETERTHEME_THEME_COPYRIGHT');

        //Demos
        $data['demo_active'] = Configuration::get('ETERTHEME_DEMO_ACTIVE');
        $data['demo_url'] = Configuration::get('ETERTHEME_DEMO_URL');

        //General
        $data['listing_add'] =  Configuration::get('ETERTHEME_GENERAL_ADD');

        /*$fields_form[] = [
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Theme Colors'),
                    'icon' => 'icon-cogs'
                ),
                'tabs' => array(
                    'maincolor' => 'MainColors',
                    'homec' => 'Home',
                    'headec' => 'Header',
                    'footerc' => 'Footer',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use this colors Configuration'),
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
                        'type' => 'color',
                        'tab' => 'maincolor',
                        'label' => $this->l('Main Color'),
                        'desc' => $this->l('To apply this color user the class "etertheme-main"'),
                        'name' => 'maincolor',
                        'required' => true,
                    ),
                    array(
                        'type' => 'color',
                        'tab' => 'headec',
                        'label' => $this->l('Header Color'),
                        'desc' => $this->l('To apply this color user the class "etertheme-headertopcolor"'),
                        'name' => 'headertopcolor',
                        'required' => true,
                    ),
                    array(
                        'type' => 'color',
                        'tab' => 'headec',
                        'label' => $this->l('Header Icon Color'),
                        'desc' => $this->l('To apply this color user the class "etertheme-headericoncolor"'),
                        'name' => 'headericoncolor',
                        'required' => true,
                    ),
                    array(
                        'type' => 'color',
                        'tab' => 'headec',
                        'label' => $this->l('Menu link'),
                        'desc' => $this->l('To apply this color user the class "etertheme-menulink"'),
                        'name' => 'menulink',
                        'required' => true,
                    ),
                    array(
                        'type' => 'color',
                        'tab' => 'headec',
                        'label' => $this->l('Menu link (hover)'),
                        'name' => 'menulinkhover',
                        'required' => true,
                    ),
                    array(
                        'type' => 'color',
                        'tab' => 'headec',
                        'label' => $this->l('Menu Nav color'),
                        'desc' => $this->l('To apply this color user the class "etertheme-menunavcolor"'),
                        'name' => 'menunavcolor',
                        'required' => true,
                    ),
                    array(
                        'type' => 'color',
                        'tab' => 'footerc',
                        'label' => $this->l('Footer'),
                        'desc' => $this->l('To apply this color user the class "etertheme-footer"'),
                        'name' => 'footer',
                        'required' => true,
                    ),
                    array(
                        'tab' => 'footerc',
                        'type' => 'color',
                        'label' => $this->l('Footer Links Titles'),
                        'desc' => $this->l('To apply this color user the class "etertheme-footerlinkstitles"'),
                        'name' => 'footerlinkstitles',
                        'required' => true,
                    ),
                    array(
                        'type' => 'color',
                        'tab' => 'footerc',
                        'label' => $this->l('Footer Links'),
                        'name' => 'footerlinks',
                        'required' => true,
                    ),
                    array(
                        'type' => 'color',
                        'tab' => 'footerc',
                        'label' => $this->l('Subscribe Button'),
                        'name' => 'footersubscribe',
                        'required' => true,
                    ),
                    array(
                        'type' => 'color',
                        'tab' => 'homec',
                        'label' => $this->l('HomeBlock color'),
                        'desc' => $this->l('This color apply in ClotLabs Theme'),
                        'name' => 'homeblock',
                        'required' => true,
                    ),

                ),
                'submit' => array(
                    'name' => 'save_colors',
                    'title' => $this->l('Save'),
                )
            ),
        ];
        */
        $fields_form[] = [
            'form' => array(
                'legend' => array(
                    'title' => $this->l('General'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Activate listing add'),
                        'name' => 'listing_add',
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
                ),
                'submit' => array(
                    'name' => 'save_general',   
                    'title' => $this->l('Save'),
                )
            ),   
        ];
        $currency = $this->context->currency;
        $currencyCode = is_array($currency) ? $currency['iso_code'] : $currency->iso_code;

        $format[] = ['format' => "{currency} ¤#,##0.00", 'name' => "{$currencyCode} $1,500.50"];
        $format[] = ['format' => "{currency} #,##0.00", 'name' => "{$currencyCode} 1,500.50"];
        $format[] = ['format' => "¤#,##0.00 {currency}", 'name' => "$1,500.50 {$currencyCode}"];
        $format[] = ['format' => "#,##0.00 {currency}", 'name' => "1,500.50 {$currencyCode}"];
        $format[] = ['format' => "¤#,##0.00", 'name' => "$1,500.50"];
        $format[] = ['format' => "#,##0.00", 'name' => "1,500.50"]; 

        $decimal[] = ['format' => ".", 'name' => "."];
        $decimal[] = ['format' => ",", 'name' => ","];
        $decimal[] = ['format' => "", 'name' => "NULL"];
        $fields_form[] = [
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Currency format'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Use this currency configuration'),
                        'name' => 'currency_active',
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
                        'type' => 'select',                              
                        'label' => $this->l('Currency format'),         
                        'name' => 'format',                     
                        'required' => true,                              
                        'options' => array(
                            'query' => $format,                           
                            'id' => 'format',                           
                            'name' => 'name'                               
                        )
                    ), 
                    array(
                        'type' => 'select',                              
                        'label' => $this->l('Decimal symbol'),         
                        'name' => 'decimal',                     
                        'required' => true,                              
                        'options' => array(
                            'query' => $decimal,                           
                            'id' => 'format',                           
                            'name' => 'name'                               
                        )
                    ), 
                    array(
                        'type' => 'select',                              
                        'label' => $this->l('Group symbol'),         
                        'name' => 'group',                     
                        'required' => true,                              
                        'options' => array(
                            'query' => $decimal,                           
                            'id' => 'format',                           
                            'name' => 'name'                               
                        )
                    ),  
                ),
                'submit' => array(
                    'name' => 'save_currency',   
                    'title' => $this->l('Save'),
                )
            ),   
        ];
        $fields_form[] = [
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Copyright'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Copyright'),
                        'name' => 'copyright',
                        'lang' => false,
                    ),  
                ),
                'submit' => array(
                    'name' => 'save_copy',   
                    'title' => $this->l('Save'),
                )
            ),   
        ];
        
        $fields_form[] = [
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Demo flag'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Is demo'),
                        'name' => 'demo_active',
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
                        'label' => $this->l('Url'),
                        'name' => 'demo_url',
                        'required' => true,
                    ),
                ),
                'submit' => array(
                    'name' => 'save_demo',   
                    'title' => $this->l('Save'),
                )
            ),   
        ];
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
        $helper->back_url =  $this->context->link->getAdminLink('EterlabsConfig', false);
        $helper->currentIndex = $this->context->link->getAdminLink('EterlabsConfig', false);
        $helper->token = Tools::getAdminTokenLite('EterlabsConfig');
        $language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $language->id,
                'iso_code' => $language->iso_code
            ),
            'fields_value' => $data,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        $helper->override_folder = '/';
        $languages = Language::getLanguages(false);
        return $helper->generateForm($fields_form);
    }
}
