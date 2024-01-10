<?php

namespace ModularityOpenStreetMap\Module;

class OpenStreetMap extends \Modularity\Module
{
    public $slug = 'open-street-map';
    public $supports = array();
    public $blockSupports = array(
        'align' => ['full'],
        'mode' => false
    );

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

        add_filter('wpPageForTerm/secondaryQueryArgs', array($this, 'setPostsPerPage'), 10, 1);
        add_filter('Municipio/Controller/Singular/displaySecondaryQuery', array($this, 'replaceArchivePosts'), 10, 1);
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
        $secondaryQuery = get_query_var('secondaryQuery');

        if (empty($secondaryQuery)) {
            $termsToShow = $fields['mod_osm_terms_to_show'];
            $postTypeToShow = $fields['mod_osm_post_type'];
            $taxonomyToShow = [];
            foreach ($termsToShow as $term) {
                $taxonomy = get_term($term)->taxonomy;
                $taxonomyToShow[$taxonomy][] = $term;
            }
            $places = $this->getPlacePosts($termsToShow, $taxonomyToShow, $postTypeToShow);
        } else {
            $places = $this->buildPlacePosts($secondaryQuery->posts, false);
        }
        $data['isFullWidth'] = $fields['mod_osm_full_width'];
        $data['places'] = $places;
        $data['mapStyle'] = $this->getMapStyle();
        $data['perPage'] = !empty($fields['mod_osm_per_page']) ? $fields['mod_osm_per_page'] : 8;

        $mapStartValues = $fields['map_start_values'] ?? [];
        $data['startPosition'] = [];
        if (!empty($mapStartValues)) {
            foreach ($mapStartValues as $key => $value) {
                $data['startPosition'][$key] = $value;
            }
            $data['startPosition'] = $data['startPosition'];
        } else {
            $data['startPosition'] = [
                'lat' => '56.046029',
                'lng' => '12.693904',
                'zoom' => '14'
            ];
        }

        return $data;
    }

    private function getMapStyle()
    {
        if (function_exists('get_theme_mod')) {
            return get_theme_mod('osm_map_style', 'default');
        } else {
            return 'default';
        }
    }

    private function getPlacePosts($termsToShow, $taxonomyToShow, $postTypeToShow)
    {
        $args = [
            'post_type' => $postTypeToShow,
            'posts_per_page' => 999,
            'tax_query' => [
                'relation' => 'OR',
            ]
        ];

        foreach ($taxonomyToShow as $taxonomy => $terms) {
            $args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => $terms
            ];
        }

        $posts = get_posts($args);

        return $this->buildPlacePosts($posts);
    }

    private function buildPlacePosts($posts, $complementPost = true)
    {
        foreach ($posts as &$post) {
            $post = \Municipio\Helper\ContentType::complementPlacePost($post, $complementPost);
        }

        return $posts;
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
