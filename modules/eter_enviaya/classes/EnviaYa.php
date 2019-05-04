<?php
use \Symfony\Component\Debug\Exception\ContextErrorException;
class EnviaYa
{
    private static $rates = null;
    private static $services = null;
    /**
    * Crear session data
    */
    public static function clear() 
    {
        self::setValue('crear_rates',true);
        self::setValue('services',null);
    }
    /**
    * Crear session data
    */
    public static function setValue($key,$value) 
    {
        if (session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $_SESSION[$key] = $value;
    }
    /**
    * Crear session data
    */
    public static function getValue($key) 
    {
        if (session_status() === PHP_SESSION_NONE){
            session_start();
        }
        if(isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }
    /**
    * Get Carriers
    */
    public static function getCarriersWebService($data)
    {
        if(self::getValue('crear_rates') && self::getValue('old_parcels') !== $data) {
            self::setValue('rates',null);
            self::setValue('crear_rates',false);
            self::setValue('old_parcels',$data);
        }
        $avilableServices = self::getValue('rates');
        if(!count($avilableServices)) {
            $url = "https://enviaya.com.mx/api/v1/rates";
            $response = self::callWS($url,$data);
            $avilableServices = [];
            if ($response) {
                foreach ($response as $key => $services) {
                    if (is_array($services) && $key != "warning") {
                        foreach ($services as $service) {
                            try {
                                if (property_exists($service,"carrier_service_code")) {
                                    $code = $service->carrier.'-'.$service->carrier_service_code;
                                    $avilableServices[$code] = (array)$service;
                                }
                            } catch(ContextErrorException $e) {}
                        }
                    }
                }
            }
            self::setValue('rates',$avilableServices);
        } 
        return $avilableServices;
    }
    /**
    * Get Available Services
    */
    public static function getCarriersServices()
    {
        $services = self::getValue('services');
        if(!count($services)) {
            $url = "https://enviaya.com.mx/api/v1/get_services";
            $carriers = self::callWs($url,[],false);
            foreach ($carriers->services as $carrier) {
                foreach ($carrier as $servindex => $service) {
                    foreach ($service as $itemindex => $item) {
                        foreach ($item as $code => $shppingname) {
                            if ($code) {
                                $servicecode = strtolower(str_replace(' ','', trim($servindex)))."-".str_replace(' ','', trim($code));
                                $services[$servicecode]['code'] = $servicecode;
                                $services[$servicecode]['service_code'] = $code;
                                $services[$servicecode]['carrier'] = $servindex;
                                $services[$servicecode]['name'] = $shppingname;
                            }
                        }
                    }
                }
            }
            self::setValue('services',$services);
        }
        return $services;
    }
    /**
     * Return key
     */
    public static function getKey()
    {
        if(Configuration::get('ENVIAYA_ACCOUNT_SANDBOX')) {
            return Configuration::get('ENVIAYA_SENDER_SANDBOXKEY');
        } else {
            return Configuration::get('ENVIAYA_SENDER_PRODUCTIONKEY');
        }
    }
    /**
    * Call ws
    */
    public static function callWs($url,$data = [],$post = true) 
    {    
        $data['api_key'] = self::getKey();
        $data['enviaya_account'] = Configuration::get('ENVIAYA_SENDER_ACCOUNT');
        $ch = curl_init();
        if ($post) {
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        } else {
            curl_setopt($ch, CURLOPT_URL,$url.'?'.http_build_query($data));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1");
        //curl_setopt($ch, CURLOPT_PROXYPORT, "8888");
        $server_output = curl_exec ($ch);
        $array = json_decode($server_output);
        EnviaYa::log(print_r($array,TRUE));
        curl_close ($ch);
        return $array;
    }
    /**
     * Write a log file
     */
    public static function log($message,$level = 1,$file = "enviaya.log") 
    {
        if (Configuration::get('ENVIAYA_ACCOUNT_LOGS')) {
            if ($file == "enviaya.log") {
                $filename = _PS_ROOT_DIR_."/var/logs/{$file}";
            } else {
                $filename = $file;
            }
            $formatted_message = "[".date('Y-m-d H:i:s').'] '.$message."\r\n";
            return (bool)file_put_contents($filename, $formatted_message, FILE_APPEND);
        }
    }
}