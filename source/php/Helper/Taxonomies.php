<?php

namespace ModularityOpenStreetMap\Helper;

class Taxonomies
{
    public function getTerms($postType) {
        $taxonomies = get_object_taxonomies($postType);
        
        $arr = [];
        foreach ($taxonomies as $taxonomy) {
            $terms = get_terms([
                'taxonomy' => $taxonomy,
                'hide_empty' => false
            ]);

            foreach ($terms as $term) {
                if(empty($term->parent)) {
                    $arr[$term->term_id] = $term->name;
                }
            }
        }

        return $arr;
    }
}
