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
* @author     Eterlabs <contacto@eterlabs.com>
*/

class eter_contactsendModuleFrontController extends ModuleFrontController
{
    /**
    *  Initialize controller
    *  @see FrontController::init()
    */
    public function init() {
        $this->process();
    }
    /**
    * Start forms process
    * @see FrontController::postProcess()
    */
    public function postProcess()
    {
        if(Tools::isSubmit('contact-form')) {
            try {
                if(Configuration::get('CONTACT_FORM_EMAILS')) {
                    $id_lang = $this->context->language->id;
                    $template_name = 'contact';

                    $templateVars['{name}'] = Tools::getValue('name');  
                    $templateVars['{email}'] = Tools::getValue('email');
                    $templateVars['{message}'] = Tools::getValue('message');  

                    $title = $this->l('Contact form');
                    $toName = $this->l('Customers');
                    $mails = Configuration::get('CONTACT_FORM_EMAILS');

                    $from = Configuration::get('PS_SHOP_EMAIL');
                    $fromName = $this->l('Site contacts');

                    $templatePath = _PS_MODULE_DIR_.'eter_contact/mails/';
                    $mailsarray = explode(',', $mails);
                    foreach ($mailsarray as $value) {
                        $to = $value;
                        Mail::Send($id_lang, $template_name, $title, $templateVars, $to, $toName, $from, $fromName,null,null,$templatePath);
                    }

                    $this->response(true,$this->l('The email was sended.'));
                } else {
                    $this->response(false,$this->l('there is not a sender email'));
                }
            } catch (Exception $e) {
                $this->response(false,$this->l('An error occurred while sending the email'));
            }
        } else {
            $this->response(false,$this->l('Not valid form submited'));
        }
    }
    /**
    * Send response by ajax
    */
    protected function response($success,$message) {
        header('Content-Type: application/json');
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->ajaxDie(Tools::jsonEncode([
            'success' => $success,
            'message' => $message
        ]));
    }
}
