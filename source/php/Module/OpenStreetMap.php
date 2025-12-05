<?php

namespace ModularityOpenStreetMap\Module;

use ModularityOpenStreetMap\Helper\GetTaxonomies as GetTaxonomies;
use ModularityOpenStreetMap\Helper\GetPlacePostType as GetPlacePostType;
use ModularityOpenStreetMap\Decorator\EndpointDecoratorInterface as EndpointDecoratorInterface;
use ModularityOpenStreetMap\Decorator\EndpointOrder as EndpointOrder;
use ModularityOpenStreetMap\Decorator\EndpointPostType as EndpointPostType;
use ModularityOpenStreetMap\Decorator\EndpointTaxonomies as EndpointTaxonomies;
use ModularityOpenStreetMap\Decorator\EndpointOrderBy as EndpointOrderBy;
use ModularityOpenStreetMap\Module\CreateFilters as CreateFilters;

class OpenStreetMap extends \Modularity\Module
{
    public $slug = 'open-street-map';
    public $supports = array();
    public $blockSupports = array(
        'align' => ['full'],
        'mode' => false
    );

    private GetTaxonomies $getTaxonomiesInstance;
    private GetPlacePostType $getPlacePostTypeInstance;
    private CreateFilters $createFiltersInstance;
    private EndpointDecoratorInterface $endpointOrder;
    private EndpointDecoratorInterface $endpointOrderBy;
    private EndpointDecoratorInterface $endpointPostType;
    private EndpointDecoratorInterface $endpointTaxonomies;

    public function init()
    {
        //Define module
        $this->nameSingular = __("OpenStreetMap", 'modularity-open-street-map');
        $this->namePlural = __("OpenStreetMaps", 'modularity-open-street-map');
        $this->description = __("Outputs a map.", 'modularity-open-street-map');

        $this->getPlacePostTypeInstance = new GetPlacePostType();
        $this->getTaxonomiesInstance    = new GetTaxonomies($this->getPlacePostTypeInstance);
        $this->createFiltersInstance    = new CreateFilters($this->getTaxonomiesInstance);

        $this->endpointOrder        = new EndpointOrder();
        $this->endpointOrderBy      = new EndpointOrderBy();
        $this->endpointPostType     = new EndpointPostType();
        $this->endpointTaxonomies   = new EndpointTaxonomies();

        add_filter('Modularity/Block/Settings', function ($blockSettings, $slug) {
            if ($slug == $this->slug) {
                $blockSettings['mode'] = 'edit';
            }
            return $blockSettings;
        }, 10, 2);
    }

     /**
     * View data
     * @return array
     */
    public function data(): array
    {
        $fields = get_fields($this->ID);
        $data['ID'] = !empty($this->ID) ? $this->ID : uniqid();

        $data['mapStyle'] = $this->getMapStyle();
        $data['endpoint'] = $this->createEndpoint($fields);
        $data['startPosition'] = !empty($fields['map_start_values']) && is_array($fields['map_start_values']) ? 
        $fields['map_start_values'] : $this->getDefaultStartPosition();
        $data['lang'] = [
            'noPostsFound' => __('No posts were found.', 'modularity-open-street-map'),
            'filterBy' => __('Filter by', 'modularity-open-street-map'),
            'descending' => __('Descending', 'modularity-open-street-map'),
            'ascending' => __('Ascending', 'modularity-open-street-map'),
            'randomized' => __('Randomized', 'modularity-open-street-map'),
            'sort' => __('Sort', 'modularity-open-street-map'),
            'bookHere' => __('Book here', 'modularity-open-street-map'),
        ];

        $data['sort'] = !empty($fields['mod_osm_sort']);
        $data['filters'] = $this->createFiltersInstance->create($fields, $data['lang']['filterBy']);
        $data['expanded'] = !empty($fields['mod_osm_expanded']);

        return $data;
    }

    /**
     * Creates the endpoint URL for retrieving OpenStreetMap data based on the specified post type and terms.
     *
     * @param string $postTypeToShow The post type to show in the endpoint URL.
     * @param array $termsToShow An array of term IDs to filter the results by.
     * @return string The generated endpoint URL.
     */
    private function createEndpoint($fields): string
    {
        $endpoint = rest_url(OSM_ENDPOINT);
        $endpoint = $this->endpointPostType->decorate($endpoint, $fields);
        $endpoint = $this->endpointTaxonomies->decorate($endpoint, $fields);
        $endpoint = $this->endpointOrder->decorate($endpoint, $fields);
        $endpoint = $this->endpointOrderBy->decorate($endpoint, $fields);

        return $endpoint;
    }

    /**
     * Retrieves the start position for the OpenStreetMap module.
     *
     * If the $mapStartValues array is empty, the default start position is returned.
     *
     * @param array $mapStartValues An array containing the latitude, longitude, and zoom level for the start position.
     * @return array The start position for the OpenStreetMap module.
     */
    private function getDefaultStartPosition() 
    {
        return [
            'lat' => '56.046029',
            'lng' => '12.693904',
            'zoom' => '14'
        ];
    }

    private function getMapStyle()
    {
        if (function_exists('get_theme_mod')) {
            return get_theme_mod('osm_map_style', 'default');
        } else {
            return 'default';
        }
    }

    public function template(): string
    {
        return "open-street-map.blade.php";
    }

    /**
     * Available "magic" methods for modules:
     * init()            What to do on initialization
     * data()            Use to send data to view (return array)
     * style()           Enqueue style only when module is used on page
     * script            Enqueue script only when module is used on page
     * adminEnqueue()    Enqueue scripts for the module edit/add page in admin
     * template()        Return the view template (blade) the module should use when displayed
     */
}
