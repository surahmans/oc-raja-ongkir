<?php namespace Rahman\Rajaongkir\Models;

use Model;
use Rahman\RajaOngkir\Classes\RajaOngkir;

/**
 * Settings Model
 */
class Settings extends Model
{
	public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'rahman_rajaongkir_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    public function getOriginProvinceOptions()
    {
        $response = json_decode((new RajaOngkir)->province()->get(), true);

        return collect($response['rajaongkir']['results'])->lists('province', 'province_id');
    }

    public function getOriginCityOptions()
    {
        $response = json_decode((new RajaOngkir)->province($this->origin_province)->city()->get(), true);

        return collect($response['rajaongkir']['results'])->lists('city_name', 'city_id');
    }
}
