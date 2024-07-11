<?php

namespace ModularityOpenStreetMap;

use ModularityOpenStreetMap\Helper\GetPlacePostType as GetPlacePostType;
use ModularityOpenStreetMap\Helper\GetTaxonomies as GetTaxonomies;
use ModularityOpenStreetMap\Helper\GetSelectedTaxonomies as GetSelectedTaxonomies;
use Municipio\Api\RestApiEndpointsRegistry;

class App
{
    protected \ModularityOpenStreetMap\Helper\CacheBust $cacheBust;
    private GetPlacePostType $getPlacePostTypeInstance;
    private GetTaxonomies $getTaxonomiesInstance;
    private GetSelectedTaxonomies $getSelectedTaxonomiesInstance;
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueueFrontend'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueBackend'));
        add_action('plugins_loaded', array($this, 'registerModule'));

        add_filter('acf/load_field/name=mod_osm_post_type', array($this, 'postTypes'));
        add_filter('acf/prepare_field/name=mod_osm_terms_to_show', array($this, 'termsToShow'));

        $this->getPlacePostTypeInstance = new GetPlacePostType();
        $this->getTaxonomiesInstance = new GetTaxonomies($this->getPlacePostTypeInstance);
        $this->getSelectedTaxonomiesInstance = new GetSelectedTaxonomies();

        
        RestApiEndpointsRegistry::add(new \ModularityOpenStreetMap\Api\OsmEndpoint());

        $this->cacheBust = new \ModularityOpenStreetMap\Helper\CacheBust();
    }

    public function postTypes($field)
    {

        $postTypes = $this->getPlacePostTypeInstance->getPlacePostTypes();

        $field['default_value'] = key($postTypes);
        $field['choices'] = $postTypes;

        return $field;
    }

    public function termsToShow($field)
    {
        $postType = get_field('mod_osm_post_type');
        $taxonomies = $this->getTaxonomiesInstance->getTaxonomiesFromPostTypeArchive($postType);

        if (empty($taxonomies)) {
            $taxonomies = ['none' => 'No post found'];
        }

        $mergedTerms = [];
        foreach ($taxonomies as $slug => $label) {
            $mergedTerms += $this->getTaxonomiesInstance->getAllTermsFromTaxonomy($slug, 'id');
            
        }
        
        $field['choices'] = $mergedTerms;

        return $field;
    }








    public function enqueueBackend()
    {
        $placeTaxonomies = $this->getTaxonomiesInstance->getAllTaxonomiesForAllPlacePostTypes($this->getPlacePostTypeInstance->getPlacePostTypes());

        $selected = $this->getSelectedTaxonomiesInstance->getSelectedTaxonomies();

        wp_register_script(
            'modularity-open-street-map-js',
            MODULARITYOPENSTREETMAP_URL . '/dist/' .
            $this->cacheBust->name('js/modularity-open-street-map.js'),
            ['jquery', 'acf-input']
        );

        wp_localize_script(
            'modularity-open-street-map-js',
            'osm',
            json_encode(['taxonomies' => $placeTaxonomies, 'selected' => $selected])
        );
    

        wp_enqueue_script('modularity-open-street-map-js');
    }

    /**
     * Enqueue required style
     * @return void
     */
    public function enqueueFrontend()
    {
        wp_register_style(
            'modularity-open-street-map-css',
            MODULARITYOPENSTREETMAP_URL . '/dist/' .
            $this->cacheBust->name('css/modularity-open-street-map.css')
        );

        wp_enqueue_style('modularity-open-street-map-css');
    }

    /**
     * Register the module
     * @return void
     */
    public function registerModule()
    {
        if (function_exists('modularity_register_module')) {
            modularity_register_module(
                MODULARITYOPENSTREETMAP_PATH . 'source/php/Module/',
                'OpenStreetMap'
            );
        }
    }
}
