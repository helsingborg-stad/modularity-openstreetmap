<?php

namespace ModularityOpenStreetMap\Api\PostTransformer;

use ModularityOpenStreetMap\Api\SettingsInterface;
use ModularityOpenStreetMap\ApplyOpenStreetMapDataToPostObject;

class DefaultTransformer implements PostTransformerInterface {
    public function __construct(private SettingsInterface $settings)
    {}

    public function transform($post): mixed
    {
        $post = \Municipio\Helper\Post::preparePostObject($post);
        $post = (new ApplyOpenStreetMapDataToPostObject($post))->apply();
        $post->osmFilterValues = $this->addOsmFilteringCapabilities($post);

        return $post;
    }

    private function addOsmFilteringCapabilities($post): string
    {
        $filterTermsStructure = [];
        $attributeString = "";
        
        if (!empty($post->termsUnlinked)) {
            foreach ($post->termsUnlinked as $term) {
                $filterTermsStructure[$term['taxonomy']][] = $term['slug'];
            }

            foreach ($filterTermsStructure as $taxonomy => $term) {
                $attributeString .= ' osm-filter-item-' . $taxonomy . '=' . implode(',', $term);
            }
        }

        return $attributeString;
    }
}