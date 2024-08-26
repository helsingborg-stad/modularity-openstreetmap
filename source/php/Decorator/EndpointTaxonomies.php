<?php

namespace ModularityOpenStreetMap\Decorator;

class EndpointTaxonomies implements EndpointDecoratorInterface
{
    public function __construct()
    {}

    public function decorate(string $endpoint, array $fields): string 
    {
        $termsToShow = $fields['mod_osm_terms_to_show'];
        
        if (empty($termsToShow)) {
            return $endpoint;
        }

        $taxonomyToShow = [];
        foreach ($termsToShow as $termId) {
            $taxonomy = get_term($termId)->taxonomy;
            $taxonomyToShow[$taxonomy][] = $termId;
        }

        foreach ($taxonomyToShow as $taxonomy => $terms) {
            $endpoint .= '&' . 'taxonomies' . '[' . $taxonomy . ']=' . implode(',', $terms);
        }

        return $endpoint;
    }
}