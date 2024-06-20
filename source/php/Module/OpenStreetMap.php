<?php

namespace ModularityOpenStreetMap\Module;

use ModularityOpenStreetMap\Api\OsmTransformationHandler;

class OpenStreetMap extends \Modularity\Module
{
    public $slug = 'open-street-map';
    public $supports = array();
    public $blockSupports = array(
        'align' => ['full'],
        'mode' => false
    );

    private string $taxonomyEndpointKey = 'taxonomies';

    public function init()
    {
        //Define module
        $this->nameSingular = __("OpenStreetMap", 'modularity-open-street-map');
        $this->namePlural = __("OpenStreetMaps", 'modularity-open-street-map');
        $this->description = __("Outputs a map.", 'modularity-open-street-map');

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
        
        $termsToShow = $fields['mod_osm_terms_to_show'];
        $postTypeToShow = $fields['mod_osm_post_type'];
        $data['isFullWidth'] = $fields['mod_osm_full_width'] ?? false;
        $data['mapStyle'] = $this->getMapStyle();
        $data['endpoint'] = $this->createEndpoint($postTypeToShow, $termsToShow);
        $data['startPosition'] = $this->getStartPosition($fields['map_start_values'] ?? []);

        return $data;
    }

    private function createEndpoint($postTypeToShow, $termsToShow): string
    {   
        $endpoint = rest_url(OSM_ENDPOINT . $postTypeToShow . '?');

        $taxonomyToShow = [];
        foreach ($termsToShow as $term) {
            $taxonomy = get_term($term)->taxonomy;
            $taxonomyToShow[$taxonomy][] = $term;
        }

        foreach ($taxonomyToShow as $taxonomy => $terms) {
            $endpoint .= '&' . $this->taxonomyEndpointKey . '[' . $taxonomy . ']=' . implode(',', $terms);
        }

        return $endpoint;
    }

    private function getStartPosition(array $mapStartValues) 
    {
        if (empty($mapStartValues)) {
            return [
                'lat' => '56.046029',
                'lng' => '12.693904',
                'zoom' => '14'
            ];
        }

        return $mapStartValues;
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
