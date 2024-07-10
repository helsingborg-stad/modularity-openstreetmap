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
        // $filters = $this->createFilters($fields);

        $data['mapStyle'] = $this->getMapStyle();
        $data['endpoint'] = $this->createEndpoint($postTypeToShow, $termsToShow);
        $data['startPosition'] = $this->getStartPosition($fields['map_start_values'] ?? []);
        $data['lang'] = [
            'noPostsFound' => __('No posts were found.', 'modularity-open-street-map'),
            'filterBy' => __('Filter by', 'modularity-open-street-map'),
        ];

        $data['filters'] = $this->createFilters($fields, $data['lang']['filterBy']);


        return $data;
    }

    private function createFilters($fields, string $filterByLang): array {
        if (empty($fields['mod_osm_filters'])) {
            return [];
        }

        $filters = [];
        foreach ($fields['mod_osm_filters'] as $filter) {
            if (empty($filter['mod_osm_filter_taxonomy']) && is_string($filter['mod_osm_filter_taxonomy'])) {
                continue;
            }

            $terms = $this->getTermsFromTaxonomy($filter['mod_osm_filter_taxonomy']);

            if ($terms) {
                $filters[] = [
                    'label' => $filter['mod_osm_filter_text'] ?? $filterByLang . $filter['mod_osm_filter_taxonomy'], 
                    'taxonomy' => $filter['mod_osm_filter_taxonomy'],
                    'terms' => $terms
                ];
            }
        }

        return $filters;
    }

    private function getTermsFromTaxonomy(string $taxonomy): array
    {
        $filteredTerms = [];

        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        ]);

        
        if (!empty($terms)) {
            foreach($terms as $term) {
                $filteredTerms[$term->slug] = $term->name;
            }
        }

        return $filteredTerms;
    }

    /**
     * Creates the endpoint URL for retrieving OpenStreetMap data based on the specified post type and terms.
     *
     * @param string $postTypeToShow The post type to show in the endpoint URL.
     * @param array $termsToShow An array of term IDs to filter the results by.
     * @return string The generated endpoint URL.
     */
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

    /**
     * Retrieves the start position for the OpenStreetMap module.
     *
     * If the $mapStartValues array is empty, the default start position is returned.
     *
     * @param array $mapStartValues An array containing the latitude, longitude, and zoom level for the start position.
     * @return array The start position for the OpenStreetMap module.
     */
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
