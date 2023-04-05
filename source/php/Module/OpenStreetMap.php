<?php

namespace ModularityLikePosts\Module;

use ModularityOpenStreetMap\Helper\Taxonomies as TaxonomiesHelper;
use Municipio\Helper\Purpose as PurposeHelper;

class OpenStreetMap extends \Modularity\Module
{
    public $slug = 'open-street-map';
    public $supports = array();
    public $blockSupports = array(
        'align' => ['full']
    );

    public function init()
    {   
        //Define module
        $this->nameSingular = __("OpenStreetMap", 'modularity-open-street-map');
        $this->namePlural = __("OpenStreetMaps", 'modularity-open-street-map');
        $this->description = __("Outputs a map.", 'modularity-open-street-map');
    }

     /**
     * View data
     * @return array
     */
    public function data(): array
    {
        $fields = get_fields($this->ID);
        $secondaryQuery = get_query_var('secondaryQuery');

        if ($secondaryQuery) {
            add_filter('Municipio/Controller/Singular/displaySecondaryQuery', function() {
                return false;
            });
        }
        
        if(empty($secondaryQuery)) {
            $termsToShow = $fields['mod_osm_terms_to_show'];
            $postTypeToShow = $fields['mod_osm_post_type'];
            $taxonomyToShow = [];
            foreach ($termsToShow as $term) {
                $taxonomy = get_term($term)->taxonomy;
                $taxonomyToShow[$taxonomy][] = $term;
            }
            $placesData = $this->getPlacePosts($termsToShow, $taxonomyToShow, $postTypeToShow);
        } else {
            $placesData = $this->buildPlacePosts($secondaryQuery->posts);
        }
        $data['postsColumns'] = apply_filters('Modularity/Display/replaceGrid', $fields['mod_osm_post_columns']);
        $data['isFullWidth'] = $fields['mod_osm_full_width'];
        $data['places'] = $placesData['places'];
        $data['coords'] = json_encode($placesData['coords']);
        $data['mapStyle'] = $this->getMapStyle();

        if (!empty($fields['start_zoom_value'])) {
            $zoom = $fields['start_zoom_value'];
            $zoom = $zoom < 5 ? 5 : ($zoom > 20 ? 20 : $zoom);
        }

        $data['startPosition'] = json_encode([
            'lat' => $fields['latitude_start'] ? $fields['latitude_start'] : '56.044383', 
            'lng' =>  $fields['longitude_start'] ?  $fields['longitude_start'] : '12.759173',
            'zoom' => !empty($zoom) ? $zoom : 14,
        ]);

        return $data;
    }

    private function getMapStyle() {
        if (function_exists('get_theme_mod')) {
            return get_theme_mod('osm_map_style', 'default');
        }
        else {
            return 'default';
        }
    }

    private function getPlacePosts($termsToShow, $taxonomyToShow, $postTypeToShow) {
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

        return $this->buildPlacePosts($posts, $postTypeToShow);
    }

    private function buildPlacePosts($posts, $postTypeToShow) {
        $coords = [];
        foreach ($posts as &$post) {

            $post = \Municipio\Helper\Post::preparePostObject($post);
            $post->postExcerpt = $this->createExcerpt($post);
            $post->termMarker = TaxonomiesHelper::getTermIcon($post->id, $postTypeToShow);
            $post->location = get_field('location', $post->id);
            if($post->location['lat'] && $post->location['lng']) {
                $direction = 'https://www.google.com/maps/dir/?api=1&destination=' . $post->location['lat'] . ',' . $post->location['lng'] . '&travelmode=transit';
            }
            $coords[] = ['lat' => $post->location['lat'], 'lng' => $post->location['lng'], 'tooltip' => ['title' => $post->postTitle, 'thumbnail' => $post->thumbnail, 'link' => $post->permalink, 'direction' => ['url' => $direction, 'label' => $post->location['street_name'] . ' ' . $post->location['street_number']]], 'icon' => $post->termMarker];
        }
        return [
            'places' => $posts,
            'coords' => $coords,
        ];
    }

    private function createExcerpt($post) {
        if ($post->postContent) {
            return wp_trim_words(wp_strip_all_tags(strip_shortcodes($post->postContent)), 10, '...');
        }
        return false;
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
