<?php

namespace ModularityLikePosts\Module;

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
            add_filter('Municipio/Controller/Singular/displaySecondaryQuery', function () {
                return false;
            });
        }

        if (empty($secondaryQuery)) {
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
        $data['pins'] = json_encode($placesData['pins'], JSON_UNESCAPED_UNICODE);
        $data['mapStyle'] = $this->getMapStyle();
        $data['perPage'] = !empty($fields['mod_osm_per_page']) ? $fields['mod_osm_per_page'] : 8;

        $mapStartValues = $fields['map_start_values'] ?? [];
        $data['startPosition'] = [];
        if (empty($mapStartValues)) {
            $data['startPosition'] = json_encode([
                'lat'  => '56.046029',
                'lng'  => '12.693904',
                'zoom' => 14,
            ]);
        } else {
            foreach ($mapStartValues as $key => $value) {
                $data['startPosition'][$key] = $value;
            }
            $data['startPosition'] = json_encode($data['startPosition']);
        }

        // if (!empty($fields['start_zoom_value'])) {
        //     $zoom = $fields['start_zoom_value'];
        //     $zoom = $zoom < 5 ? 5 : ($zoom > 20 ? 20 : $zoom);
        // }

        // $data['startPosition'] = json_encode([
        //     'lat' => $fields['latitude_start'] ? $fields['latitude_start'] : '56.046029',
        //     'lng' =>  $fields['longitude_start'] ?  $fields['longitude_start'] : '12.693904',
        //     'zoom' => !empty($zoom) ? $zoom : 14,
        // ]);

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

    private function buildPlacePosts($posts)
    {
        $coords = [];
        foreach ($posts as &$post) {
            $post = \Municipio\Helper\Post::preparePostObject($post);
            $post->postExcerpt = $this->createExcerpt($post);
            $postFields = get_fields($post->id);
            $post->location = $postFields['location'];
            if ($post->location['lat'] && $post->location['lng']) {
                $direction = 'https://www.google.com/maps/dir/?api=1&destination=' . $post->location['lat'] . ',' . $post->location['lng'] . '&travelmode=transit';
            }
            $post->list[] = $this->createListItem($postFields['location']['street_name'] . ' ' . $postFields['location']['street_number'], 'location_on', $direction);
            $post->list[] = $this->createListItem($postFields['phone'], 'call');
            if ($postFields['website']) {
                $post->list[] = $this->createListItem(__('Visit website', 'modularity-open-street-map'), 'language', $postFields['website']);
            }
            $pins[] = ['lat' => $post->location['lat'], 'lng' => $post->location['lng'], 'tooltip' => ['title' => $post->postTitle, 'thumbnail' => $post->thumbnail, 'link' => $post->permalink, 'direction' => ['url' => $direction, 'label' => $post->location['street_name'] . ' ' . $post->location['street_number']]], 'icon' => $post->termIcon];
        }
        return [
            'places' => $posts,
            'pins' => $pins,
        ];
    }

    private function createListItem($label, $icon, $href = false)
    {
        if (!empty($label) && $label != " ") {
            return ['label' => $label, 'icon' => ['icon' => $icon, 'size' => 'md'], 'href' => $href];
        }

        return false;
    }

    private function createExcerpt($post)
    {
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
