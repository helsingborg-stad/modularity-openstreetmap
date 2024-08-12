<?php

namespace ModularityOpenStreetMap\Helper;

use ModularityOpenStreetMap\Helper\GetPlacePostType as GetPlacePostType;

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
        $activeTaxonomiesForPostType = get_theme_mod('archive_' . $postType . '_taxonomies_to_display', []);
        
        if (!is_array($activeTaxonomiesForPostType)) {
            return [];
        }

        $taxonomies = $this->getTaxonomiesFromSlug($activeTaxonomiesForPostType);

        return $taxonomies;
    }

    private function getTaxonomiesFromSlug(array $activeTaxonomiesForPostType): array
    {
        $arr = [];
        if (empty($activeTaxonomiesForPostType)) {
            return $arr;
        }

        foreach ($activeTaxonomiesForPostType as $taxonomy) {
            $taxonomyObject = get_taxonomy($taxonomy);
            $arr[$taxonomyObject->name] = $taxonomyObject->label;
        }

        return $arr;
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

    public function getAllTermsFromTaxonomy(string $taxonomy, string $output = 'name'): array
    {
        $filteredTerms = [];

        $terms = get_terms([
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