<?php

namespace ModularityOpenStreetMap\Helper;

class GetTaxonomies {
    public function getAllTaxonomiesForAllPlacePostTypes(array $postTypes): array
    {
        $taxonomies = [];

        if (empty($postTypes)) {
            return [];
        }

        foreach ($postTypes as $slug => $label) {
            $taxonomies[$slug] = $this->getTaxonomiesFromArchive($slug);
        }

        return $taxonomies;
    }
    
    public function getTaxonomiesFromArchive(string $postType): array
    {
        $activeTaxonomiesForPostType = get_theme_mod('archive_' . $postType . '_taxonomies_to_display', []);
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

}