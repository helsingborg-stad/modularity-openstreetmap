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

     public function getTermIcon($postId, $postType) {
        $taxonomies = get_object_taxonomies($postType);
        
        $termIcon = [];
        foreach ($taxonomies as $taxonomy) {
            $terms = get_the_terms($postId, $taxonomy);
            if (!empty($terms)) {
                if(class_exists('Municipio\Helper\Term')) {
                    foreach ($terms as $term) {
                        if (empty($termIcon)) {
                            $icon = \Municipio\Helper\Term::getTermIcon($term, $taxonomy);
                            $color = \Municipio\Helper\Term::getTermColor($term, $taxonomy);
                            if (!empty($icon) && !empty($icon['src']) && $icon['type'] == 'icon') {
                                $termIcon['icon'] = $icon['src'];
                                $termIcon['size'] = 'md';
                                $termIcon['color'] = 'white';
                            }
                            if(!empty($color)) {
                                $termIcon['backgroundColor'] = \Municipio\Helper\Term::getTermColor($term, $taxonomy);
                            }
                        }
                    }
                }
            }
        }

        return $termIcon;
    }
}
