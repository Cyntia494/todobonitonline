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
class EnviaYaServices 
{
	
	public static function activateService($availableServices) 
	{
		$carriers = EnviaYa::getCarriersServices();
		foreach($carriers as $key => $carrier) {
			$carrier = (object)$carrier;
			if($idCarrier = self::getCarrierByCode($key)) {
				$carrierModel = new Carrier($idCarrier);
				if(in_array($key,$availableServices)) {
					$carrierModel->active = 1;
					$carrierModel->delete = 0;
				} else {
					$carrierModel->active = 0;
					$carrierModel->delete = 1;
				}
				$data['enviaya_carrier'] = $carrier->carrier;
				$data['enviaya_service_code'] = $carrier->code;
				$data['enviaya_carrier_code'] = $carrier->service_code;
				Db::getInstance()->update('carrier', $data, 'id_carrier='.(int)$idCarrier);
				$carrierModel->save();
			} else if(in_array($key,$availableServices)) {
				$idCarrier = self::createCarrier($carrier->carrier,$carrier->name);
				if($idCarrier) {
					$data['enviaya_carrier'] = $carrier->carrier;
					$data['enviaya_service_code'] = $carrier->code;
					$data['enviaya_carrier_code'] = $carrier->service_code;
					Db::getInstance()->update('carrier', $data, 'id_carrier='.(int)$idCarrier);
				}
			}
		}
	}
	/**
    * Delete tables
    */
    public static function getCarrierByCode($code)
    {
		$query = new DbQuery();
        $query->select("id_carrier");
        $query->from("carrier", "ca");
        $query->where("ca.enviaya_service_code like \"%{$code}%\"");
		$idCarrier = (int)Db::getInstance()->getValue($query);
        return $idCarrier;
	}
	/**
     * add Customer content
     */
    public static function getShipmentData($idOrder)
    {
		$query = new DbQuery();
		$query->select("ord.id_order");
		$query->select("ordca.id_order_carrier");
		$query->select("ca.enviaya_carrier");
		$query->select("ca.name");
		$query->select("ca.enviaya_carrier as carrier");
		$query->select("ca.enviaya_carrier_code as carrier_code");
		$query->select("ca.enviaya_service_code as service");
		$query->select("ordca.enviaya_label_url as label");
		$query->select("ordca.enviaya_rate_id as rateid");
		$query->select("ordca.tracking_number");
		$query->from("orders", "ord");
		$query->leftJoin('carrier','ca','(ca.id_carrier = ord.id_carrier)');
		$query->leftJoin('order_carrier','ordca','(ordca.id_carrier = ord.id_carrier && ordca.id_order = ord.id_order)');
        $query->where("ord.id_order = {$idOrder}");
        $data = (object)Db::getInstance()->getRow($query);
        return $data;
    }
    /**
     * Return true if rate id has numbers
     */
    public function isEnviaYa($idOrder) 
    {
    	if ($idOrder) {
	    	$data = $this->getShipmentData($idOrder);
	    	if ($data && $data->rateid) {
	    		return true;
	    	} else {
	    		return false;
	    	}
    	}
    }
    /**
     * add Customer content
     */
	public function hasShipment($idOrder)
	{
		if ($idOrder) {
	    	$data = $this->getShipmentData($idOrder);
	    	if ($data && $data->tracking_number) {
	    		return true;
	    	} else {
	    		return false;
	    	}
    	}
	}
	/**
     * add Customer content
     */
	public function isServiceActive($code)
	{
		$query = new DbQuery();
        $query->select("active");
        $query->from("carrier", "ca");
        $query->where("ca.enviaya_service_code like \"%{$code}%\"");
		$active = (bool)Db::getInstance()->getValue($query);
		return $active;
	}
	/**
     * Return servicedata
     */
	public static function getService($id_carrier)
	{
		$query = new DbQuery();
        $query->select("*");
        $query->from("carrier", "ca");
        $query->where("ca.id_carrier = {$id_carrier}");
		$service = Db::getInstance()->getRow($query);
		if($service) {
			return (object)$service;
		} else {
			return false;
		}
	}
	/**
     * Return orders
     */
	public static function isOrder($id_cart)
	{
		$query = new DbQuery();
        $query->select("id_order");
        $query->from("orders", "ord");
        $query->where("ord.id_cart = {$id_cart}");
		$order = (boolean)Db::getInstance()->getValue($query);
		return $order;
	}
	/**
     * Create carrier
     */
	private function disableCarrier($id_carrier)
	{
		$carrier = new Carrier($id_carrier);
		if ($carrier->id_reference) {
			$carrier->active = 0;
			$carrier->delete = 1;
			$carrier->save();
		}
	}
	/**
     * Create carrier
     */
	private function updateCarrier($id_carrier,$code)
	{
		$carrier = new Carrier($id_carrier);
		if ($carrier->id_reference && !$carrier->deleted) {
			return $carrier->id_reference;
		} else {
			return $this->createCarrier($code);
		}
	}
	/**
     * Create carrier
     */
    private static function createCarrier($carrier_name,$service_name)
    {
        $carrier = new Carrier();
        $carrier->name = $carrier_name;
        $carrier->id_tax_rules_group = 0;
        $carrier->active = 1;
        $carrier->deleted = 0;
        foreach (Language::getLanguages(true) as $language) {
            $carrier->delay[(int)$language['id_lang']] = $service_name;
        }
        
        $carrier->shipping_handling = false;
        $carrier->range_behavior = 0;
        $carrier->is_module = true;
        $carrier->shipping_external = true;
        $carrier->external_module_name = 'eter_enviaya';
        $carrier->need_range = True;
        if (!$carrier->save()) {
            return false;
        }
        // Associate carrier to all groups
        $groups = Group::getGroups(true);
        foreach ($groups as $group) {
            Db::getInstance()->insert('carrier_group', array('id_carrier' => (int)$carrier->id, 'id_group' => (int)$group['id_group']));
        }

        // Create price range
        $rangePrice = new RangePrice();
        $rangePrice->id_carrier = $carrier->id;
        $rangePrice->delimiter1 = '0';
        $rangePrice->delimiter2 = '10000';
        $rangePrice->add();

        // Create weight range
        $rangeWeight = new RangeWeight();
        $rangeWeight->id_carrier = $carrier->id;
        $rangeWeight->delimiter1 = '0';
        $rangeWeight->delimiter2 = '10000';
        $rangeWeight->add();

        // Associate carrier to all zones
        $zones = Zone::getZones(true);
        foreach ($zones as $zone)
        {
            Db::getInstance()->insert('carrier_zone', array('id_carrier' => (int)$carrier->id, 'id_zone' => (int)$zone['id_zone']));
            Db::getInstance()->insert('delivery', array('id_carrier' => (int)$carrier->id, 'id_range_price' => (int)$rangePrice->id, 'id_range_weight' => NULL, 'id_zone' => (int)$zone['id_zone'], 'price' => '0'));
            Db::getInstance()->insert('delivery', array('id_carrier' => (int)$carrier->id, 'id_range_price' => NULL, 'id_range_weight' => (int)$rangeWeight->id, 'id_zone' => (int)$zone['id_zone'], 'price' => '0'));
        }
        return $carrier->id;
    }

}