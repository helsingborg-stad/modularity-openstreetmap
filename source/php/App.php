<?php

declare(strict_types=1);

namespace ModularityOpenStreetMap;

use ModularityOpenStreetMap\Helper\GetPlacePostType as GetPlacePostType;
use ModularityOpenStreetMap\Helper\GetSelectedTaxonomies as GetSelectedTaxonomies;
use ModularityOpenStreetMap\Helper\GetTaxonomies as GetTaxonomies;
use Municipio\Api\RestApiEndpointsRegistry;
use WpUtilService\Features\Enqueue\EnqueueManager;

class App
{
    protected \ModularityOpenStreetMap\Helper\CacheBust $cacheBust;
    private GetPlacePostType $getPlacePostTypeInstance;
    private GetTaxonomies $getTaxonomiesInstance;
    private GetSelectedTaxonomies $getSelectedTaxonomiesInstance;

    public function __construct(
        private EnqueueManager $wpEnqueue,
    ) {
        add_action('wp_enqueue_scripts', [$this, 'enqueueFrontend']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueBackend']);
        add_action('init', [$this, 'registerModule']);

        add_filter('acf/load_field/name=mod_osm_post_type', [$this, 'postTypes']);
        add_filter('acf/prepare_field/name=mod_osm_terms_to_show', [$this, 'termsToShow']);

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

        $this->wpEnqueue
            ->add('js/modularity-open-street-map.js', ['jquery', 'acf-input'])
            ->with()
            ->translation('osm', ['taxonomies' => $placeTaxonomies, 'selected' => $selected]);
    }

    /**
     * Enqueue required style
     * @return void
     */
    public function enqueueFrontend()
    {
        $this->wpEnqueue->add('css/modularity-open-street-map.css');
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
                'OpenStreetMap',
            );
        }
    }
}
