<?php

namespace ModularityOpenStreetMap\Api;

class OsmQueryArgsCreator
{
    public function __construct(private SettingsInterface $settings)
    {}

    public function CreateQueryArgs(): array
    {
        return [
            'post_type' => $this->settings->getPostType(),
            'posts_per_page' => $this->settings->getPostsPerPage(), 
            'paged' => $this->settings->getPage(),
            'post_status' => 'publish',
            'tax_query' => $this->createTaxQuery()
        ];
    }

    private function createTaxQuery(): array
    {
            $taxonomies = $this->settings->getTaxonomies();

            if (empty($taxonomies)) {
                return [];
            }
    
            $taxQuery = [
                'relation' => 'OR',
            ];
                    
            foreach ($taxonomies as $taxonomy => $termsString) {
                $taxQuery[] = [
                    'taxonomy' => $taxonomy,
                    'field' => 'term_id',
                    'terms' => explode(',', $termsString)
                ];
            }
    
            return $taxQuery;   
    }
}