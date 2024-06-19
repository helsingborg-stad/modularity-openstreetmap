<?php

namespace ModularityOpenStreetMap\Module;

use ModularityOpenStreetMap\Api\GetPosts;

class OpenStreetMap extends \Modularity\Module
{
    public $slug = 'open-street-map';
    public $supports = array();
    public $blockSupports = array(
        'align' => ['full'],
        'mode' => false
    );

    private string $endpoint = 'https://localhost:59181/wp-json/osm/v1/';
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

    public function setPostsPerPage($secondaryQueryArgs) {
        if ($this->hasModule()) {
            $secondaryQueryArgs['posts_per_page'] = 999;
        }

        return $secondaryQueryArgs;
    }

    public function replaceArchivePosts($item)
    {
        return !$this->hasModule();
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
        $data['perPage'] = 20;
        $data['endPoint'] = $this->createEndpoint($postTypeToShow, $termsToShow);
        $data['startPosition'] = $this->getStartPosition($fields['map_start_values'] ?? []);

        return $data;
    }

    private function createEndpoint($postTypeToShow, $termsToShow)
    {
        $endpoint = $this->endpoint . $postTypeToShow . '?';

        $taxonomyToShow = [];
        foreach ($termsToShow as $term) {
            $taxonomy = get_term($term)->taxonomy;
            $taxonomyToShow[$taxonomy][] = $term;
        }
        foreach ($taxonomyToShow as $taxonomy => $terms) {
            $endpoint .= ($endpoint === $this->endpoint . $postTypeToShow) ? '' : '&';
            $endpoint .= $this->taxonomyEndpointKey . '[' . $taxonomy . ']=' . implode(',', $terms);
        }
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
