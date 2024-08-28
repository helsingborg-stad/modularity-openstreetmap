<?php

namespace ModularityOpenStreetMap\Helper;

use ModularityOpenStreetMap\Helper\GetPlacePostType as GetPlacePostType;
use WP_Taxonomy;

class GetTaxonomies {
    private $postTypesTaxonomies = [];

    public function __construct(private GetPlacePostType $getPlacePostTypeInstance)
    {}

    public function getAllTaxonomiesForAllPlacePostTypes(array $postTypes): array
    {
        if (empty($postTypes) || !empty($this->postTypesTaxonomies)) {
            return $this->postTypesTaxonomies;
        }

        foreach ($postTypes as $slug => $label) {
            $this->postTypesTaxonomies[$slug] = $this->getTaxonomiesFromPostTypeArchive($slug);
        }

        return $this->postTypesTaxonomies;
    }

    public function getTaxonomiesFromPostTypeArchive(string $postType): array
    {
        $filterableItems = [];
        $activeTaxonomiesForPostType = get_theme_mod('archive_' . $postType . '_taxonomies_to_display', []);
        
        if (empty($activeTaxonomiesForPostType) || !is_array($activeTaxonomiesForPostType)) {
            return $filterableItems;
        }

        foreach ($activeTaxonomiesForPostType as $taxonomy) {
            $taxonomyObject = get_taxonomy($taxonomy);
            if (empty($taxonomyObject)) {
                continue;
            }

            $filterableItems[$taxonomyObject->name] = $taxonomyObject->label;
            $filterableItems = array_merge($filterableItems, $this->addHierarchicalTerms($taxonomyObject->name));
        }

        return $filterableItems;
    }

    private function addHierarchicalTerms(string $taxonomy): array
    {
        $hierarchicalTerms = [];

        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
            'parent' => 0,
        ]);

        if (empty($terms)) {
            return $hierarchicalTerms;
        }

        foreach ($terms as $term) {
            $hierarchicalTerms['_' . $taxonomy . '_' . $term->term_id] = $term->name . ' (' .  $taxonomy . ')';
        }
        
        return $hierarchicalTerms;
    }

    public function getAllTermsFromPostTypeArchiveTaxonomies(string $postType) 
    {
        $placePostTypesTaxonomies = $this->getAllTaxonomiesForAllPlacePostTypes($this->getPlacePostTypeInstance->getPlacePostTypes());

        if (empty($placePostTypesTaxonomies[$postType])) {
            return [];
        }

        $terms = [];
        foreach ($placePostTypesTaxonomies[$postType] as $slug => $label) {
            $terms[$slug] = $this->getAllTermsFromTaxonomy($slug);
        }

        return $terms;
    }

    public function getAllTermsFromTaxonomy(string $taxonomy, string $output = 'name', array|null $args = null): array
    {
        $filteredTerms = [];

        $terms = get_terms($args ?? [
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        ]);
        
        if (!is_wp_error($terms) && !empty($terms)) {
            if ($output === 'object') { 
                return $terms;
            }

            foreach($terms as $term) {
                $filteredTerms[$output === 'id' ? $term->term_id : $term->slug] = $term->name;
            }
        }

        return $filteredTerms;
    }

}