<?php

namespace ModularityOpenStreetMap\Helper;

class Taxonomies
{
    public function getTaxonomies() {
        $taxonomies = get_object_taxonomies('place');
        
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

     public function getTermIcon($postId) {
        $taxonomies = get_object_taxonomies('place');
        
        $termIcons = [];
        foreach ($taxonomies as $taxonomy) {
            $terms = get_the_terms($postId, $taxonomy);
            if (!empty($terms)) {
                foreach ($terms as $term) {
                    $termIcons[] = ['icon' => \Municipio\Helper\Term::getTermIcon($term, $taxonomy), 'color' => \Municipio\Helper\Term::getTermColor($term, $taxonomy)];
                }
            }
        }
        $icon = "";
        if (!empty($termIcons)) {
            foreach ($termIcons as $termIcon) {
                if ($termIcon['icon']['type'] == 'icon' || !empty($termIcon['icon']['src'])) {
                    $termIcon['icon']['src'] = 'restaurant';
                    $icon = $termIcon;
                    break;
                }
            }
        }
        return $icon;
    }
}
