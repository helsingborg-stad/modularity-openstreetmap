<?php

namespace ModularityLikePosts\Module;

use ModularityOpenStreetMap\Helper\Taxonomies as TaxonomiesHelper;

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
            $termToShow = $fields['mod_osm_terms_to_show'];
            $taxonomyToShow = get_term($termToShow)->taxonomy;
            $placesData = $this->getPlacePosts($termToShow, $taxonomyToShow);
        } else {
            $placesData = $this->buildPlacePosts($secondaryQuery->posts);
        }
        $data['postsColumns'] = apply_filters('Modularity/Display/replaceGrid', $fields['mod_osm_post_columns']);
        $data['isFullWidth'] = $fields['mod_osm_full_width'];
        $data['places'] = $placesData['places'];
        $data['coords'] = json_encode($placesData['coords']);
        
        return $data;
    }

    private function getPlacePosts($termToShow, $taxonomyToShow) {
        $args = [
            'post_type' => 'place',
            'posts_per_page' => 8,
            'tax_query' => [
                [
                    'taxonomy' => $taxonomyToShow,
                    'field' => 'term_id',
                    'terms' => $termToShow
                ]
            ]
        ];

        $posts = get_posts($args);

        return $this->buildPlacePosts($posts);
    }

    private function buildPlacePosts($posts) {
        $coords = [];
        foreach ($posts as &$post) {
            $post = \Municipio\Helper\Post::preparePostObject($post);
            $post->postExcerpt = $this->createExcerpt($post);
            $post->termMarker = TaxonomiesHelper::getTermIcon($post->id);
            $post->location = get_field('location', $post->id);
            $coords[] = ['lat' => $post->location['lat'], 'lng' => $post->location['lng'], 'title' => $post->postTitle, 'icon' => $post->termMarker];
            echo('<br><br>');
            var_dump($post->termMarker);
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
