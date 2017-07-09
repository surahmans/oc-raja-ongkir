<?php namespace Rahman\Rajaongkir;

use Backend;
use System\Classes\PluginBase;

/**
 * rajaongkir Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'rajaongkir',
            'description' => 'No description provided yet...',
            'author'      => 'rahman',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Rahman\Rajaongkir\Components\Cost' => 'shippingCost',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'rahman.rajaongkir.some_permission' => [
                'tab' => 'rajaongkir',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'rajaongkir' => [
                'label'       => 'rajaongkir',
                'url'         => Backend::url('rahman/rajaongkir/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['rahman.rajaongkir.*'],
                'order'       => 500,
            ],
        ];
    }
}
