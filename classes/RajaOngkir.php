<?php namespace Rahman\RajaOngkir\Classes;

use Rahman\RajaOngkir\Classes\Endpoints as Api;

class RajaOngkir 
{
    private $api;
    private $endpoint;
    private $province_id;
    private $city_id;
    private $subdistrict_id;

    public function __construct()
    {
        $this->api = new Api(env('RAJAONGKIR_API_KEY'), env('RAJAONGKIR_ACCOUNT_TYPE'));
    }

    public function province($province_id = null)
    {
        $this->province_id = $province_id;
        $this->endpoint = 'province';

        return $this;
    }

    public function city($city_id = null)
    {
        $this->city_id = $city_id;
        $this->endpoint = 'city';

        return $this;
    }

    public function subdistrict($subdistrict_id = null)
    {
        $this->subdistrict_id = $subdistrict_id;
        $this->endpoint = 'subdistrict';

        return $this;
    }

    public function cost($origin, $originType, $destination, $destinationType, $weight, $courier)
    {
        return $this->api->cost($origin, $originType, $destination, $destinationType, $weight, $courier);
    }

    public function get()
    {
        switch ($this->endpoint) {
            case 'province':
                $result = $this->api->province($this->province_id);
                break;
            case 'city':
                $result = $this->api->city($this->province_id, $this->city_id);
                break;
            case 'subdistrict':
                $result = $this->api->subdistrict($this->city_id, $this->subdistrict_id);
                break;
        }

        $this->refreshEndpointIds();

        return $result;
    }

    /**
     * Refresh endpoint ids agar tidak mempengaruhi pemanggilan endpoint berikutnya
     */
    public function refreshEndpointIds()
    {
        $this->province_id = null;
        $this->city_id = null;
        $this->subdistrict_id = null;
    }
}
