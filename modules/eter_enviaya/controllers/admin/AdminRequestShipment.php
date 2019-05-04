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
* @package    Eter_Enviaya
* @author     ETERLABS S.A.S. de C.V. <contacto@eterlabs.com>
*/
class AdminRequestShipmentController extends ModuleAdminController
{

	public function initContent()
	{
        parent::initContent();
        $order = new Order(Tools::getValue('order'));
        $data = EnviaYaServices::getShipmentData($order->id);
        $ws = $this->module->requetShipment($order->id,$data->rateid,$data->carrier,$data->carrier_code);

        $response['success'] = false;
        if(property_exists($ws,"enviaya_shipment_number")) {
            $tracking_number = "";
            $id_order_carrier = $this->getIdOrderCarrier($order);
            $orderCarrier = new OrderCarrier($id_order_carrier);
            $update['tracking_number'] = $ws->carrier_shipment_number;
            $update['enviaya_label_url'] = $ws->label_share_link;
            
            Db::getInstance()->update('order_carrier', $update, "id_order_carrier={$orderCarrier->id}");
            if ($orderCarrier->sendInTransitEmail($order)) {
                $customer = new Customer((int)$order->id_customer);
                $carrier = new Carrier((int)$order->id_carrier, $order->id_lang);
                Hook::exec('actionAdminOrdersTrackingNumberUpdate', array(
                    'order' => $order,
                    'customer' => $customer,
                    'carrier' => $carrier
                ), null, false, true, false, $order->id_shop);
                
                $history = new OrderHistory();
                $history->id_order = (int)$order->id;
                $history->changeIdOrderState(Configuration::get('PS_OS_SHIPPING'), (int)$order->id);
            } 
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['message'] = $ws->status_message;
        }
        header('Content-Type: application/json');
        $this->ajaxDie(Tools::jsonEncode($response));
    }
    /**
    * Load order carrier id
    */
    private function getIdOrderCarrier($order) 
    {
        $query = new DbQuery();
        $query->select("id_order_carrier");
        $query->from("order_carrier", "ordca");
        $query->where("id_carrier={$order->id_carrier} and id_order={$order->id}");
		$idOrderCarrier = (int)Db::getInstance()->getValue($query);
        return $idOrderCarrier;
    }
}