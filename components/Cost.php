<?php namespace Rahman\Rajaongkir\Components;

use Cache;
use Session;
use Event;
use Rahman\RajaOngkir\Classes\RajaOngkir;
use Rahman\RajaOngkir\Models\Settings;
use Cms\Classes\ComponentBase;

class Cost extends ComponentBase
{
    public $provinces;

    public $couriers;

    public function componentDetails()
    {
        return [
            'name'        => 'Cost',
            'description' => 'Fetch any shipping cost address'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $this->provinces = $this->page['provinces'] = $this->getProvinces();
        $this->couriers = $this->page['couriers'] = $this->getCouriers();
    }

    public function onSelectProvince()
    {
        $response =  $this->rajaongkir()->province(post('province'))->city()->get();

        $this->page['cities'] = $tes = $this->decodeResponse($response)->results;
    }

    public function onSelectCity()
    {
        $response =  $this->rajaongkir()->province()->city(post('city'))->subdistrict()->get();

        $this->page['subdistricts'] = $this->decodeResponse($response)->results;
    }

    public function onSelectCourier()
    {
        $origin = $this->decodeResponse($this->rajaongkir()->city(Settings::get('origin_city'))->get())->results;
        $destination = $this->decodeResponse($this->rajaongkir()->subdistrict(post('subdistrict'))->get())->results;

        $response =  $this->rajaongkir()->cost(
            $origin->city_id,
            $originType = 'city',
            $destination->subdistrict_id,
            $destinationType = 'subdistrict', 
            $weight = 1000,
            $courier = post('courier')
        );

        $this->page['service'] = $result = $this->decodeResponse($response)->results[0];

        Cache::put(Session::getId(), $result, 5);

    }

    public function onSelectService()
    {
        $result = Cache::get(Session::getId());
        $shippingData = $this->getShippingDataByService($result, post('service'));

        Event::fire('rahman.rajaongkir.afterCalculate', [$shippingData, post()]);
    }

    protected function getShippingDataByService($result, $service)
    {
        /**
         * Convert object to recursive array 
         **/
        $resultArr = json_decode(json_encode($result), true);

        $serviceData = collect($resultArr['costs'])->filter(function($cost) use ($service) {
            return $cost['service'] == $service;
        })->first();

        $resultArr['cost'] = $serviceData;

        return array_except($resultArr, ['costs']);
    }

    protected function rajaongkir()
    {
        return new RajaOngkir();
    }

    protected function getProvinces($province_id = null)
    {
        $response =  $this->rajaongkir()->province($province_id)->get();

        return $this->decodeResponse($response)->results;
    }

    protected function getCouriers()
    {
        return [
            'jne'  => 'JNE',
            'tiki' => 'TIKI',
            'pos'  => 'POS',
        ];
    }

    private function decodeResponse($response)
    {
        return json_decode($response)->rajaongkir;
    }

}
