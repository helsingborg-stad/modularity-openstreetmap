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
            if (empty($filter['mod_osm_filter_taxonomy']) || !is_string($filter['mod_osm_filter_taxonomy'])) {
                continue;
            }

            // Used to get a more specific filter.
            [$taxonomy, $termId] = $this->getHierarchicalTermFilter($filter['mod_osm_filter_taxonomy']);
            $terms = $this->getTermsFromTaxonomy($taxonomy, $termId);
            $label = !empty($filter['mod_osm_filter_text']) ? 
                $filter['mod_osm_filter_text'] : 
                $filterByLang . ' ' . $taxonomy;
                
            if ($terms) {
                $filters[] = [
                    'label' => $label, 
                    'taxonomy' => $taxonomy,
                    'terms' => $terms
                ];
            }
        }

        return $filters;
    }

    private function getHierarchicalTermFilter(string $taxonomy): array {
        if ($taxonomy[0] === '_') {
            $taxonomy = explode('_', $taxonomy);
            $termId = !empty($taxonomy[2]) ? $taxonomy[2] : "";
            $taxonomy = !empty($taxonomy[1]) ? $taxonomy[1] : "";
        }

        return [$taxonomy, $termId ?? ""];
    }

    private function getTermsFromTaxonomy(string $taxonomy, $termId): array
    {
        return $this->getTaxonomiesInstance->getAllTermsFromTaxonomy($taxonomy, 'name', [
            'taxonomy' => $taxonomy,
            'parent' => $termId,
            'hide_empty' => true
        ]);
    }
}