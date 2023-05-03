<?php

namespace ModularityLikePosts\Module;

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

        add_filter('wpPageForTerm/secondaryQueryArgs', array($this, 'setPostsPerPage'), 10, 2);
        add_filter('Municipio/Controller/Singular/displaySecondaryQuery', array($this, 'replaceArchivePosts'), 10, 1);
    }

    public function setPostsPerPage($secondaryQueryArgs, $query) {
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
            $placesData = $this->getPlacePosts($termsToShow, $taxonomyToShow, $postTypeToShow);
        } else {
            $placesData = $this->buildPlacePosts($secondaryQuery->posts, false);
        }
        $data['isFullWidth'] = $fields['mod_osm_full_width'];
        $data['places'] = $placesData['places'];
        $data['pins'] = $placesData['pins'];
        $data['mapStyle'] = $this->getMapStyle();
        $data['perPage'] = !empty($fields['mod_osm_per_page']) ? $fields['mod_osm_per_page'] : 8;

        $mapStartValues = $fields['map_start_values'] ?? [];
        $data['startPosition'] = [];
        if (!empty($mapStartValues)) {
            foreach ($mapStartValues as $key => $value) {
                $data['startPosition'][$key] = $value;
            }
            $data['startPosition'] = $data['startPosition'];
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

    private function buildPlacePosts($posts, $complemenPost = true)
    {
        $coords = [];
        foreach ($posts as &$post) {
            if ($complemenPost) {
                $post = \Municipio\Helper\Post::preparePostObject($post);
            }
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
