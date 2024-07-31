<?php

namespace ModularityOpenStreetMap\Module;

use ModularityOpenStreetMap\Helper\GetTaxonomies as GetTaxonomies;

class CreateFilters {
    public function __construct(private GetTaxonomies $getTaxonomiesInstance)
    {}

    public function create(array $fields, string $filterByLang): array {
        if (empty($fields['mod_osm_filters'])) {
            return [];
        }

        $filters = [];
        foreach ($fields['mod_osm_filters'] as $filter) {
            if (empty($filter['mod_osm_filter_taxonomy']) && is_string($filter['mod_osm_filter_taxonomy'])) {
                continue;
            }

            $terms = $this->getTermsFromTaxonomy($filter['mod_osm_filter_taxonomy']);

            if ($terms) {
                $filters[] = [
                    'label' => $filter['mod_osm_filter_text'] ?? $filterByLang . $filter['mod_osm_filter_taxonomy'], 
                    'taxonomy' => $filter['mod_osm_filter_taxonomy'],
                    'terms' => $terms
                ];
            }
        }

        return $filters;
    }

    private function getTermsFromTaxonomy(string $taxonomy): array
    {
        return $this->getTaxonomiesInstance->getAllTermsFromTaxonomy($taxonomy);
    }
}